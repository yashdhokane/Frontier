
	<a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-bs-toggle="dropdown"
		aria-haspopup="true" aria-expanded="false">
		<i data-feather="message-square" class="feather-sm"></i>
	</a>
	<?php
	$chats = \App\Models\SupportMessage::with('user')->latest()->limit(5)->get();
	?>
	<div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up" aria-labelledby="2">
		<span class="with-arrow"><span class="bg-danger"></span></span>
		<ul class="list-style-none">
			<li>
				<div class="drop-title text-white bg-danger">
					<h4 class="mb-0 mt-1">{{ count($chats) }} New</h4>
					<span class="fw-light">Messages</span>
				</div>
			</li>
			<li>
				<div class="message-center message-body">
					<!-- Message -->
					@foreach ($chats as $item)
					<a href="{{ route('app_chats', ['message_id' => $item->id, 'user_one' => $item->user_one, 'user_two' => $item->user_two]) }}"
						class="message-item">
						<span class="user-img">
							@if ($item->user)
							<img src="{{ asset('public/images/technician/' . $item->user->user_image) }}"
								alt="user" class="rounded-circle" />
							@else
							<img src="{{ asset('public/images/technician/1708105764_avatar-1.png') }}"
								alt="user" class="rounded-circle" />
							@endif
							<span class="profile-status online pull-right"></span>
						</span>
						<div class="mail-contnet">
							@if ($item->user)
							<h5 class="message-title">{{ $item->user->name }}</h5>
							@else
							<p class="m-00">Technician N\A</p>
							@endif
							<span class="mail-desc">Just see the my admin!</span>
							<span class="time">{{ $item->created_at->format('g:i A') }}</span>
						</div>
					</a>
					@endforeach
				</div>
			</li>
			<li>
				<a class="nav-link text-center link text-dark" href="{{ route('app_chats') }}">
					<b>Go To Chats</b>
					<i data-feather="chevron-right" class="feather-sm"></i>
				</a>
			</li>
		</ul>
	</div>
 