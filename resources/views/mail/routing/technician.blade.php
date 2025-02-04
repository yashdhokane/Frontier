<!DOCTYPE html>
<html>
<head>
    <title>Job Schedule Confirmation</title>
</head>
<body>

    <p>We would like to confirm that the <strong>{{ $mailData['job']->job_title ?? '' }}</strong> is scheduled for:</p>
    <ul>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($mailData['schedule']->start_date_time ?? now())->format('F j, Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($mailData['schedule']->start_date_time ?? now())->format('g:i A') }}</li>
    </ul>

    <p><strong>Customer Details:</strong></p>
    <ul>
        <li><strong>Name:</strong> {{ $mailData['customer']->name ?? 'John Doe' }}</li>
    </ul>
</body>
</html>
