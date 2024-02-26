<?php



namespace App\Http\Controllers;


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
        $chats = SupportMessage::with('user')->latest()->get();

        return view('chat.app_chats', compact('chats'));
    }


    public function get_chats(Request $request)
    {
        $id = $request->id;
        $user_one = $request->user_one;
        $user_two = $request->user_two;

        // Find the user data for both users
        $users = User::whereIn('id', [$user_one, $user_two])->get();

        // Retrieve chat messages for the specified support message ID
        $sms = SupportMessageReply::with('user')->where('support_message_id', $id)->get();

        // Return the user data and chat messages as a JSON response
        return response()->json([
            'users' => $users,
            'messages' => $sms,
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
