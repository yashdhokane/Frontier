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
use App\Models\PredefineReplies;
use App\Models\SupportMessageReply;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class ChatSupportController extends Controller
{


    public function index(Request $request)
    {

        $chatConversion = ChatConversation::with('Participant.user')->latest('last_activity')->get();
        $users = User::all();

        $employee = User::whereNotIn('role', ['superadmin', 'customer', 'technician'])->where('status', 'active')->get();

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $sendToIds = $chatConversion->pluck('send_to')->unique();

        $customer = User::where('role', 'customer')
            ->whereIn('id', $sendToIds)
            ->get();

        $predefinedReplies = PredefineReplies::all();

        $quickId = $request->get('quick_id');
        $quickUserRole = $request->get('quick_user_role');

        $subject = ChatMessage::where('type', 'subject')->get();


        return view('chat.app_chats', compact('chatConversion', 'users', 'employee', 'customer', 'predefinedReplies', 'technician', 'quickId', 'quickUserRole', 'subject'));
    }

    public function index_iframe(Request $request)
    {

        $chatConversion = ChatConversation::with('Participant.user')->latest('last_activity')->get();
        $users = User::all();

        $employee = User::whereNotIn('role', ['superadmin', 'customer', 'technician'])->where('status', 'active')->get();

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $sendToIds = $chatConversion->pluck('send_to')->unique();

        $customer = User::where('role', 'customer')
            ->whereIn('id', $sendToIds)
            ->get();

        $predefinedReplies = PredefineReplies::all();

        $quickId = $request->get('quick_id');
        $quickUserRole = $request->get('quick_user_role');

        $subject = ChatMessage::where('type', 'subject')->get();


        return view('chat.iframe_chat', compact('chatConversion', 'users', 'employee', 'customer', 'predefinedReplies', 'technician', 'quickId', 'quickUserRole', 'subject'));
    }


 
    

    // new  code start 


    public function add_employee_cnvrsn(Request $request)
    {
        $authId = auth()->user()->id;
        $currentDate = now()->format('Y-m-d H:i:s');

        // Check if a conversation already exists
        $check = ChatConversation::where('created_by', $authId)
            ->where('send_to', $request->id)
            ->first();

        if (!$check) {
            // Save a new conversation
            $conversation = new ChatConversation();
            $conversation->created_by = $authId;
            $conversation->send_to = $request->id;
            $conversation->created_date = $currentDate;
            $conversation->last_activity = $currentDate;
            $conversation->save();

            // Define both participants: auth user and the request user
            $participants = [$authId, $request->id];

            // Add both participants to the conversation
            foreach ($participants as $participantId) {
                $participant = new ChatParticipants();
                $participant->user_id = $participantId;
                $participant->conversation_id = $conversation->id;
                $participant->join_time = now();
                $participant->added_by = $authId;
                $participant->is_unread = 0;
                $participant->is_active = 'yes';
                $participant->save();
            }

            $id = $conversation->id;
        } else {
            $id = $check->id;
        }

        // Fetch chat messages
        $chat = ChatMessage::with('user', 'chating')->where('conversation_id', $id)->get();

        // Fetch participants
        $participants = ChatParticipants::with(['user', 'user.userAddress'])
            ->where('conversation_id', $id)
            ->get();

        // Attach schedules based on role
        foreach ($participants as $participant) {
            $role = $request->user_role; // Get role from the request
            // Fetch schedules based on the role
            $participant->schedules = $participant->user->schedulesByRole($role)->with('jobModel')->get();
        }

        $chatMessages = ChatMessage::select('id', 'conversation_id', 'sender', 'message', 'time', 'type')
            ->where('conversation_id', $id);

        $chatFiles = ChatFile::select(DB::raw('null as id'), 'conversation_id', 'sender', 'filename as message', 'time', 'type')
            ->where('conversation_id', $id);


        $combinedData = $chatMessages->union($chatFiles)
            ->with('user') // Eager load the user relation
            ->where('conversation_id', $id)
            ->orderBy('time', 'desc')
            ->get();

        $attachmentfileChatFile = ChatFile::select('filename', 'sender', 'conversation_id')
            ->where('conversation_id', $id)->get();

        // Return the user data and chat messages as a JSON response
        return response()->json([
            'conversation_id' => $id,
            'chat' => $chat,
            'partician' => $participants,
            'combineData' => $combinedData,
            'attachmentfileChatFile' => $attachmentfileChatFile
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'auth_id' => 'required',
            'support_message_id' => 'required',
            'file' => 'nullable|array',
            'file.*' => 'mimes:jpeg,png,jpg,gif,bmp,svg,webp,pdf,doc,docx,xlsx,txt,mp3,mp4,mov,avi|max:5120',
        ]);

        $fileSizeLimit = 5120;
        $allowedVideoSizeLimit = 16384;

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                if ($file->isValid()) {
                    $fileSize = $file->getSize();
                    $fileMimeType = $file->getMimeType();

                    if (str_starts_with($fileMimeType, 'video/') && $fileSize > $allowedVideoSizeLimit * 1024) {
                        return response()->json(['error' => 'Video file size exceeds 16MB'], 422);
                    } elseif (!str_starts_with($fileMimeType, 'video/') && $fileSize > $fileSizeLimit * 1024) {
                        return response()->json(['error' => 'File size exceeds 5MB'], 422);
                    }

                    $directory = public_path('images/Uploads/chat/' . $request->support_message_id);
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($directory, $filename);

                    $chatFile = new ChatFile();
                    $chatFile->sender = auth()->user()->id;
                    $chatFile->time = now();
                    $chatFile->conversation_id = $request->support_message_id;
                    $chatFile->filename = $filename;
                    $chatFile->type = $fileMimeType;
                    $chatFile->size = $fileSize;
                    $chatFile->save();
                } else {
                    return response()->json(['error' => 'Invalid file upload'], 422);
                }
            }
        }


        if ($request->filled('reply')) {
            $message = $request->reply;
            $youtubeEmbedUrl = null;
            if (preg_match('/(https?:\/\/(www\.)?youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/', $message, $matches)) {
                $youtubeVideoId = $matches[3];
                $youtubeEmbedUrl = "https://www.youtube.com/embed/{$youtubeVideoId}";

                ChatFile::create([
                    'sender' => auth()->user()->id,
                    'time' => now(),
                    'conversation_id' => $request->support_message_id,
                    'filename' => $youtubeEmbedUrl,
                    'type' => 'youtube',
                    'size' => null,
                ]);
            }

            if (!$youtubeEmbedUrl) {
                ChatMessage::create([
                    'sender' => auth()->user()->id,
                    'conversation_id' => $request->support_message_id,
                    'message' => $message,
                    'time' => now(),
                ]);
            }
        }

        $participants = ChatParticipants::where('conversation_id', $request->support_message_id)->get();
        $authUserId = Auth::id();
        $filteredParticipants = $participants->where('user_id', '!=', $authUserId);

        if ($request->has('is_send') && $request->is_send == 'yes') {
            foreach ($filteredParticipants as $user) {
                $receiverNumber = '+917030467187';
                $message = $request->reply;
                $formattedMessage = "You have a new message in your chat:\n\n{$message}";

                $sid = env('TWILIO_SID');
                $token = env('TWILIO_TOKEN');
                $fromNumber = env('TWILIO_FROM');

                $client = new Client($sid, $token);
                $client->messages->create($receiverNumber, [
                    'from' => $fromNumber,
                    'body' => $formattedMessage,
                    'statusCallback' => url("https://dispatchannel.com/portal/api/sms/receive?conversation_id=" . $request->support_message_id)
                ]);
            }
        }

        $id = $request->support_message_id;

        $chat = ChatMessage::with('user', 'chating')->where('conversation_id', $id)->get();

        $participants = ChatParticipants::with(['user', 'user.userAddress'])
            ->where('conversation_id', $id)
            ->get();

        foreach ($participants as $participant) {
            $role = $request->user_role;
            $participant->schedules = $participant->user->schedulesByRole($role)->with('jobModel')->get();
        }
        $chatMessages = ChatMessage::select('id', 'conversation_id', 'sender', 'message', 'time', 'type')
            ->where('conversation_id', $id);

        $chatFiles = ChatFile::select(DB::raw('null as id'), 'conversation_id', 'sender', 'filename as message', 'time', 'type')
            ->where('conversation_id', $id);

        $combinedData = $chatMessages->union($chatFiles)
            ->with('user')
            ->where('conversation_id', $id)
            ->orderBy('time', 'desc')
            ->get();

        $attachmentfileChatFile = ChatFile::select('filename', 'sender', 'conversation_id')
            ->where('conversation_id', $id)->get();

        return response()->json([
            'conversation_id' => $id,
            'chat' => $chat,
            'partician' => $participants,
            'combineData' => $combinedData,
            'attachmentfileChatFile' => $attachmentfileChatFile
        ]);
    }

    public function searchCustomer(Request $request)
    {
        $query = $request->input('query');

        // Search for customers based on the input query
        $customers = User::where('role', 'customer')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', "%{$query}%") // Partial matches
                    ->orWhere('email', 'LIKE', "%{$query}%"); // Optionally search by email as well
            })
            ->orderByRaw("LOCATE('{$query}', name)") // Prioritize names that start with or contain the query
            ->limit(3)
            ->get();


        // Return the view with the filtered customers
        return response()->json($customers);
    }

    public function store_subject(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'support_message_id' => 'required|integer',
        ]);

        // Create a new ChatMessage
        ChatMessage::create([
            'sender' => auth()->user()->id,
            'conversation_id' => $validatedData['support_message_id'],
            'message' => $validatedData['subject'],
            'type' => 'subject',
            'time' => now(),
        ]);

        // Optionally, return a JSON response
        return response()->json(['success' => 'Message stored successfully.']);
    }

    public function update_subject(Request $request)
    {
        $subject = ChatMessage::find($request->id);

        // Check if the subject was found
        if ($subject) {
            $subject->message = $request->subject; // Update the message property
            $subject->save(); // Save the changes

            return response()->json(['success' => 'Subject updated successfully.']);
        } else {
            return response()->json(['error' => 'Subject not found.'], 404); // Return error if not found
        }
    }


    // new code end here 

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
