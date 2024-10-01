<?php



namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\ChatFile;
use App\Models\Jobfields;

use App\Models\ChatMessage;

use Illuminate\Http\Request;
use App\Models\SupportMessage;
use App\Models\ChatConversation;

use App\Models\ChatParticipants;
use App\Models\SupportMessageReply;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class ChatSupportController extends Controller
{


    public function index()
    {

        $chatConversion = ChatConversation::with('Participant.user')->latest('last_activity')->get();
        $users = User::all();


        return view('chat.app_chats', compact('chatConversion', 'users'));
    }


    public function get_chats(Request $request)
    {
        $id = $request->id;

        $chat = ChatMessage::with('user', 'chating')->where('conversation_id', $id)->get();
        $partician = ChatParticipants::with('user')->where('conversation_id', $id)->get();
        $chatMessages = ChatMessage::select('conversation_id', 'sender', 'message', 'time')
            ->where('conversation_id', $id);

        // Get chat files
        $chatFiles = ChatFile::select('conversation_id', 'sender', 'filename', 'time')
            ->where('conversation_id', $id);

        // Combine chat messages and chat files using union
        $combinedData = $chatMessages->union($chatFiles)
            ->with('user') // Eager load the user relation
            ->where('conversation_id', $id)
            ->orderBy('time', 'desc')
            ->get();
        $attachmentfileChatFile = ChatFile::select('filename', 'sender', 'conversation_id')
            ->where('conversation_id', $id)->get();



        // Return the user data and chat messages as a JSON response
        return response()->json([
            'chat' => $chat,
            'partician' => $partician,
            'combineData' => $combinedData,
            'attachmentfileChatFile' => $attachmentfileChatFile
        ]);
    }



    public function autocompleteUser(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('name', 'like', '%' . $term . '%')->limit(10)->get();

        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'value' => $user->id,
                'label' => $user->name,
            ];
        }

        return response()->json($formattedUsers);
    }

    public function participants(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('name', 'like', '%' . $term . '%')
            ->whereOr('role', 'dispatcher')
            ->whereOr('role', 'customer')
            ->limit(10)->get();

        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'value' => $user->id,
                'label' => $user->name,
            ];
        }

        return response()->json($formattedUsers);
    }


    public function addUserToConversation(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required',
            'users' => 'required',
        ]);

        $conversation = ChatConversation::findOrFail($request->conversation_id);

        if (!$conversation) {
            return back()->with('error', 'Conversation not found');
        }

        $user = User::where('name', 'like', '%' . $request->users . '%')->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        $existingParticipant = ChatParticipants::where('conversation_id', $request->conversation_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingParticipant) {
            return back()->with('error', 'User already exists in the conversation');
        }

        $participant = new ChatParticipants();
        $participant->user_id = $user->id;
        $participant->conversation_id = $request->conversation_id;
        $participant->join_time = now();
        $participant->added_by = auth()->id();
        $participant->is_unread = 0;
        $participant->is_active = "yes";



        $participant->save();

        $conversation->last_activity = now();
        $conversation->save();

        return back()->with('success', 'User added to the conversation successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'auth_id' => 'required',
            'support_message_id' => 'required',
        ]);

        if ($request->hasFile('file')) {
            // Handle file upload
            $file = $request->file('file');

            // Store the file in the specified directory
            $directory = public_path('images/Uploads/chat/' . $request->support_message_id);

            // Ensure the directory exists
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate a unique filename to prevent conflicts
            $filename = uniqid() . '_' . $file->getClientOriginalName();

            // Move the uploaded file to the directory
            $file->move($directory, $filename);

            // Create a new ChatFile record
            $chatFile = new ChatFile();
            $chatFile->sender = $request->auth_id;
            $chatFile->time = now();
            $chatFile->conversation_id = $request->support_message_id;
            $chatFile->filename = $filename; // Store the generated filename
            $chatFile->type = $file->getClientMimeType();

            // Retrieve the file size if the file exists
            if (file_exists($directory . '/' . $filename)) {
                $chatFile->size = filesize($directory . '/' . $filename);
            } else {
                // Handle the case where the file doesn't exist
                // You can set the size to null or a default value here
                $chatFile->size = null;
            }

            // Save the ChatFile record
            $chatFile->save();
        }

        // Check if a message is present and not null
        if ($request->filled('reply')) {
            // Create a new message
            $message = new ChatMessage();
            $message->sender = $request->auth_id;
            $message->conversation_id = $request->support_message_id;
            $message->message = $request->reply;
            $message->time = now();
            $message->save();
        }

        $participants = ChatParticipants::where('conversation_id', $request->support_message_id)->get();
        $authUserId = Auth::id();
        $filteredParticipants = $participants->where('user_id', '!=', $authUserId);

        foreach ($filteredParticipants as $user) {

            // $receiverNumber = '+917030467187'; // Replace with the recipient's phone number
            $receiverNumber = '+918830711935'; // Replace with the recipient's phone number
            $message = $request->reply; // Replace with your desired message
            $formattedMessage = "You have a new message in your chat:\n\n{$message}";

            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $fromNumber = env('TWILIO_FROM');


            $client = new Client($sid, $token);
            $client->messages->create($receiverNumber, [
                'from' => $fromNumber,
                'body' => $formattedMessage
            ]);
        }

        // Optionally, return a success response
        return response()->json(['message' => 'Reply stored successfully'], 200);
    }

    public function deleteParticipant(Request $request)
    {
        // Get the user ID and conversation ID of the participant to be deleted
        $userId = $request->input('user_id');
        $conversationId = $request->input('conversation_id');

        // Delete the participant from the ChatParticipants model based on user_id and conversation_id
        ChatParticipants::where('user_id', $userId)
            ->where('conversation_id', $conversationId)
            ->delete();

        // Delete chat messages associated with the user ID and conversation ID
        ChatMessage::where('sender', $userId)
            ->where('conversation_id', $conversationId)
            ->delete();

        // Delete chat files associated with the user ID and conversation ID
        ChatFile::where('sender', $userId)
            ->where('conversation_id', $conversationId)
            ->delete();

        // You can add more models and relationships as needed
        return back()->with('success', 'User deleted to the conversation successfully');
    }

    public function sendSms()
    {
        $receiverNumber = '+917030467187'; // Replace with the recipient's phone number
        $message = 'hi testing'; // Replace with your desired message

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $fromNumber = env('TWILIO_FROM');

        try {
            $client = new Client($sid, $token);
            $client->messages->create($receiverNumber, [
                'from' => $fromNumber,
                'body' => $message
            ]);

            return 'SMS Sent Successfully.';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function addUserToParticipant(Request $request)
    {
        $authId = auth()->id();
        $currentDate = now()->format('Y-m-d H:i:s');

        // Create a new chat conversation
        $chatConversation = ChatConversation::create([
            'created_by' => $authId,
            'send_to' => $request->user_id,
            'created_date' => $currentDate,
            'last_activity' => $currentDate,
        ]);

        // Prepare participant data
        $participants = [
            [
                'user_id' => $chatConversation->send_to,
                'conversation_id' => $chatConversation->id,
                'join_time' => $currentDate,
                'added_by' => $authId,
                'is_unread' => 0,
            ],
            [
                'user_id' => $chatConversation->created_by,
                'conversation_id' => $chatConversation->id,
                'join_time' => $currentDate,
                'added_by' => $authId,
                'is_unread' => 0,
            ],
        ];

        // Insert participants
        ChatParticipants::insert($participants);

        return back()->with('success', 'User added to the conversation successfully');
    }

    public function receiveSms(Request $request)
    {
        Storage::append('Data.log', 'data -- ' . json_encode($request->all()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

        // ChatMessage::insert([
        //     'sender' => 11,
        //     'conversation_id' => 11,
        //     'message' => json_encode($request->all())
        // ]);



    }


}
