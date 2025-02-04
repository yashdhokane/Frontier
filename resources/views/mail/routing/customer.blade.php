<!DOCTYPE html>
<html>
<head>
    <title>Job Schedule Confirmation</title>
</head>
<body>
    <h1>Job Schedule Confirmation</h1>

    <p>We would like to confirm that the <strong>{{ $mailData['job']->job_title ?? '' }}</strong> is scheduled for:</p>
    <ul>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($mailData['schedule']->start_date_time ?? now())->format('F j, Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($mailData['schedule']->start_date_time ?? now())->format('g:i A') }}</li>
    </ul>

    <p><strong>{{ $mailData['technician']->name ?? '' }}</strong>, our technician, will be there to provide the service.</p>

    <p>
        Please confirm or reject your availability using the following link:
        <br>
        <a href="{{ $mailData['confirmation_link'] ?? '#' }}" target="_blank">
            Confirm/Reject Availability
        </a>
    </p>
</body>
</html>
