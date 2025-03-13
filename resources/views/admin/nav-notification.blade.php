@php
    $userId = auth()->id();

    $userNotifications = App\Models\UserNotification::with('notice')->where('user_id', $userId)->where('is_read', 0)->orderBy('id', 'desc')->take(5)->get();
    $unreadNotificationsCount = App\Models\UserNotification::where('user_id', $userId)->where('is_read', 0)->count();

@endphp

        
        
        
        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-bs-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">

			<i data-feather="bell" class="feather-sm"></i>

		</a>

		<div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up">

			<span class="with-arrow"><span class="bg-site"></span></span>

			<ul class="list-style-none">

				<li>

					<div class="drop-title bg-site text-white">

						<h4 class="mb-0 mt-1">{{$unreadNotificationsCount ?? ''}} New</h4>

						<span class="fw-light">Notifications</span>

					</div>

				</li>
                @foreach ($userNotifications as $notification)
              @php
            $id = preg_replace('/[^0-9]/', '', $notification->notice->notice_title);
        @endphp
				<li style="overflow-y: auto;">

					<div class="message-center notifications" >
                 @if ($notification->notice->notice_section === 'job')
                <a href="{{ route('tickets.show', ['id' => $id]) }}" class="message-item" >
            @else
                <a href="#" class="message-item">
            @endif

							<span class="btn btn-light-primary text-secondary btn-circle">

								 @if ($notification->notice->notice_section === 'job')
                        <i class="ri-calendar-check-fill"></i>
                    @else
                        <i class="ri-user-add-line"></i>
                    @endif

							</span>

							<div class="mail-contnet" onclick="updateNotification({{ $notification->user_id }}, {{ $notification->notice_id }});">

								<h6 class="message-title">{{$notification->notice->notice_heading ?? null}}</h6>

								<span class="mail-desc">{{$notification->notice->notice_title ?? null}}</span>

								<span class="time">{{ $isEnd($notification->notice->notice_date ?? null)}}</span>

							</div>
						</a>

					</div>

				</li>
                @endforeach


				<li>

 					<a class="nav-link text-center mb-1 text-dark" href="{{route('myprofile.activity')}}">

						<strong>Check all notifications</strong>

						<i data-feather="chevron-right" class="feather-sm"></i>

					</a>

				</li>

			</ul>

		</div>


   