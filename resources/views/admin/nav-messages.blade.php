<a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-bs-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">
    <i data-feather="message-square" class="feather-sm"></i>
</a>
<?php
$chats = \App\Models\ChatMessage::where('sender', '<>', 1)
    ->with('user')
    ->orderBy('time', 'desc')
    ->get()
    ->groupBy('sender')
    ->map(function ($group) {
        return $group->first();
    })
    ->values();

?>
<div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up" aria-labelledby="2">
    <span class="with-arrow"><span class="bg-site"></span></span>
    <ul class="list-style-none nav_bg">
        <li>
            <div class="drop-title text-white bg-site">
                <h5 class="mb-0 mt-1 uppercase">{{ count($chats) }} New</h5>
                <span class="fw-light">Messages</span>
            </div>
        </li>
        <li>
            <div class="message-center message-body">
                <!-- Message -->
                @foreach ($chats as $item)
                    @if ($item->user)
                        <a href="{{ route('app_chats', ['quick_id' => $item->user->id, 'quick_user_role' => $item->user->role]) }}" class="message-item">
                            <span class="btn btn-light-primary text-secondary btn-circle">
                                <i class="ri-user-fill"></i>
                    
							</span>
                            <div class="mail-contnet">
                                <h6 class="message-title">{{ $item->user->name }}</h6>
                                <span class="d-block text-truncate">{{ $item->message }}</span>
                                <small class="time">{{ \Carbon\Carbon::parse($item->time)->diffForHumans() }}</small>
                            </div>
                        </a>
                    @endif
                @endforeach


        </li>
        <li>
            <a class="nav-link text-center link text-dark" href="{{ route('app_chats') }}">
                <b>Go To Inbox</b>
                <i data-feather="chevron-right" class="feather-sm"></i>
            </a>
        </li>
    </ul>
</div>
