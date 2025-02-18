@extends('home')

@section('content')
<style>
    .alert-dismissible {
        position: relative;
        padding-right: 4em;
    }

    .close-alert {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
        cursor: pointer;
    }

    .paginate_laravel {
        text-align: center;
    }
</style>

<div class="page-wrapper" style="display:inline;">

   <div class="page-wrapper" style="display:inline;">

     <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left section with left margin -->
        <div class="col-auto" style="margin-left: 30px;">
            <h4 class="page-title">My Profile</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">My Profile</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right section with right margin -->
        <div class="col-auto" style="margin-right: 5px;">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <a href="{{ route('myprofile.index') }}"
                    class="btn {{ request()->routeIs('myprofile.index') ? 'btn-info' : 'btn-secondary text-white' }}">Profile</a>
                <a href="{{ route('myprofile.account') }}"
                    class="btn {{ request()->routeIs('myprofile.account') ? 'btn-info' : 'btn-secondary text-white' }}">Account Settings</a>
                <a href="{{ route('myprofile.activity') }}"
                    class="btn {{ request()->routeIs('myprofile.activity') ? 'btn-info' : 'btn-secondary text-white' }}">Activity and Notifications</a>
            </div>
        </div>
    </div>
</div>


    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert" id="error-alert">
            {{ session('error') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <script>
            setTimeout(function() {
                $('#success-alert, #error-alert').alert('close');
            }, 5000);
        </script>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body card-border shadow">
                        <h5 class="card-title">ACTIVITY FEED</h5>
                        <div class="table-responsive">
                            <table class="table customize-table mb-0 v-middle">
                                <thead>
                                    <tr>
                                        <th>Activity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="activity-body">
                                    @foreach($activity as $record)
                                    <tr>
                                        <td>{{ $record->activity }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->activity_date)->format('D n/j/y g:ia') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($activity->hasMorePages())
                            <div class="justify-content-center mt-3 paginate_laravel">
                                <button id="load-more-activities" class="btn btn-primary" data-next-page="{{ $activity->nextPageUrl() }}">Load More</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body card-border shadow">
                        <h5 class="card-title">NOTIFICATIONS</h5>
                        <div class="table-responsive">
                            <table class="table customize-table mb-0 v-middle">
                                <thead>
                                    <tr>
                                        <th>Notifications</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="notifications-body">
                                    @foreach($userNotifications as $record)
                                    <tr>
                                        <td @if($record->is_read == 0) class="text-muted" @endif>
                                            <span class="fw-normal">{{ $record->notice->notice_title ?? '' }}</span>
                                        </td>
                                        <td @if($record->is_read == 0) class="text-muted" @endif>
                                            {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D n/j/y g:ia') ?? 'null' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($userNotifications->hasMorePages())
                            <div class="justify-content-center mt-3 paginate_laravel">
                                <button id="load-more-notifications" class="btn btn-primary" data-next-page="{{ $userNotifications->nextPageUrl() }}">Load More</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('load-more-activities').addEventListener('click', function() {
            const button = this;
            const nextPageUrl = button.getAttribute('data-next-page');

            if (nextPageUrl) {
                fetch(nextPageUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const activityBody = document.getElementById('activity-body');
                    data.data.activities.forEach(activity => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td>${activity.activity}</td><td>${moment(activity.activity_date).format('ddd M/D/YY h:mma')}</td>`;
                        activityBody.appendChild(row);
                    });

                    if (!data.next_activity_page) {
                        button.style.display = 'none';
                    } else {
                        button.setAttribute('data-next-page', data.next_activity_page);
                    }
                })
                .catch(error => console.error('Error fetching activities:', error));
            }
        });

        document.getElementById('load-more-notifications').addEventListener('click', function() {
            const button = this;
            const nextPageUrl = button.getAttribute('data-next-page');

            if (nextPageUrl) {
                fetch(nextPageUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const notificationsBody = document.getElementById('notifications-body');
                    data.data.notifications.forEach(notification => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td class="${notification.is_read === 0 ? 'text-muted' : ''}">
                            <span class="fw-normal">${notification.notice.notice_title || ''}</span>
                        </td>
                        <td class="${notification.is_read === 0 ? 'text-muted' : ''}">
                            ${moment(notification.notice.notice_date).format('ddd M/D/YY h:mma') || 'null'}
                        </td>`;
                        notificationsBody.appendChild(row);
                    });

                    if (!data.next_notifications_page) {
                        button.style.display = 'none';
                    } else {
                        button.setAttribute('data-next-page', data.next_notifications_page);
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
            }
        });
    });
</script>

@endsection
