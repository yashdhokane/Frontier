<?php



namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ChatFile;
use App\Models\Jobfields;

use App\Models\ChatMessage;

use Illuminate\Http\Request;
use App\Models\SupportMessage;
use App\Models\ChatConversation;

use App\Models\ChatParticipants;
use App\Models\SupportMessageReply;

class ChatSupportController extends Controller
{


    public function index()
    {

        $chatConversion = ChatConversation::with('Participant.user')->latest('last_activity')->get();
        $users = User::all();


        //dd($chatConversion);

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
        $attachmentfileChatFile = ChatFile::select('filename', 'sender')
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

        $participant->save();

        $conversation->last_activity = now();
        $conversation->save();

        return back()->with('success', 'User added to the conversation successfully');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'auth_id' => 'required',
    //         'support_message_id' => 'required',
    //     ]);

    //     // Check if a file is present
    //     if ($request->hasFile('file')) {
    //         // Handle file upload
    //         $file = $request->file('file');

    //         // Store the file in the specified directory
    //         $filePath = 'public/images/Uploads/chat/' . $request->support_message_id;
    //         $fileName = $file->getClientOriginalName();
    //         $file->storeAs($filePath, $fileName);

    //         // Create a new ChatFile record
    //         $chatFile = new ChatFile();
    //         $chatFile->conversation_id = $request->support_message_id;
    //         $chatFile->filename = $fileName;
    //         $chatFile->type = $file->getClientMimeType();
    //         $chatFile->size = $file->getSize();
    //         $chatFile->save();
    //     }

    //     // Check if a message is present and not null
    //     if ($request->filled('reply')) {
    //         // Create a new message
    //         $message = new ChatMessage();
    //         $message->sender = $request->auth_id;
    //         $message->conversation_id = $request->support_message_id;
    //         $message->message = $request->reply;
    //         $message->time = now();
    //         $message->save();
    //     }

    //     // Optionally, you can return a success response
    //     return response()->json(['message' => 'Reply stored successfully'], 200);
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'auth_id' => 'required',
    //         'support_message_id' => 'required',
    //     ]);

    //     // Check if a file is present
    //     if ($request->hasFile('file')) {
    //         // Handle file upload
    //         $file = $request->file('file');

    //         // Store the file in the specified directory
    //         $directory = 'images/Uploads/chat/' . $request->support_message_id;
    //         $filePath = public_path($directory); // Use public_path() to get the full public directory path
    //         $fileName = $file->getClientOriginalName();
    //         $file->move($filePath, $fileName);

    //         // Create a new ChatFile record
    //         $chatFile = new ChatFile();
    //         $chatFile->conversation_id = $request->support_message_id;
    //         $chatFile->filename = $fileName;
    //         $chatFile->type = $file->getClientMimeType();
    //         $chatFile->size = $file->getSize();
    //         $chatFile->save();
    //     }

    //     // Check if a message is present and not null
    //     if ($request->filled('reply')) {
    //         // Create a new message
    //         $message = new ChatMessage();
    //         $message->sender = $request->auth_id;
    //         $message->conversation_id = $request->support_message_id;
    //         $message->message = $request->reply;
    //         $message->time = now();
    //         $message->save();
    //     }

    //     // Optionally, you can return a success response
    //     return response()->json(['message' => 'Reply stored successfully'], 200);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'auth_id' => 'required',
            'support_message_id' => 'required',
        ]);

        // Check if a file is present
        if ($request->hasFile('file')) {
            // Handle file upload
            $file = $request->file('file');

            // Validate file
            $request->validate([
                'file' => 'file|max:2048', // Example: max file size of 2MB
            ]);

            // Store the file in the specified directory
       $directory = public_path('images/Uploads/chat/' . $request->support_message_id);

// Check if the directory already exists
if (!File::exists($directory)) {
    // Create the directory if it doesn't exist
    File::makeDirectory($directory, $mode = 0777, true, true);
}

$filePath = $file->storeAs($directory, $file->getClientOriginalName());
 if ($request->hasFile('file')) {
    $file = $request->file('file');
    
    // Create a new ChatFile record
    $chatFile = new ChatFile();
    $chatFile->sender = $request->auth_id;
    $chatFile->time = now();
    $chatFile->conversation_id = $request->support_message_id;
    $chatFile->filename = $file->getClientOriginalName();
    $chatFile->type = $file->getClientMimeType();
    $chatFile->size = $file->getSize();
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

        // Optionally, you can return a success response
        return response()->json(['message' => 'Reply stored successfully'], 200);
    }
}