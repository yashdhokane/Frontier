<?php



namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;

use App\Models\Jobfields;

use App\Models\SupportMessage;
use App\Models\SupportMessageReply;
use Illuminate\Http\Request;

use Carbon\Carbon;

class ChatSupportController extends Controller

{


    public function index()
    {

        $chatConversion = ChatConversation::with('Participant.user')->latest('last_activity')->get();

        // dd($chatConversion);

        return view('chat.app_chats', compact('chatConversion'));
    }


    public function get_chats(Request $request)
    {
        $id = $request->id;

        $chat = ChatMessage::with('user')->where('conversation_id', $id)->get();


        // Return the user data and chat messages as a JSON response
        return response()->json([
            'chat' => $chat,
        ]);
    }
    public function store(Request $request)
    {
        $chat = new SupportMessageReply();

        $chat->support_message_id = $request->support_message_id;
        $chat->reply = $request->reply;
        $chat->user_id = $request->auth_id;

        $chat->save();

        return response()->json([
            'status' => true,
        ]);
    }
}
