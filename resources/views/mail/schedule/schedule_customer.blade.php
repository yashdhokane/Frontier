@extends('mail.main')
@section('content')
<div style="padding: 40px; background: #fff">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
      <tbody>
        <!-- start row -->
        @php
            // Retrieve time interval from the session
            $time_interval = Session::get('time_interval');
            
            // Add the time interval to the start_date_time using Carbon
            $newFormattedDateTimeAdd = \Carbon\Carbon::parse($maildata['data']['schedule']->start_date_time)->addHours($time_interval);
        @endphp
        <tr>
          <td>
            <b>Hello {{ $maildata['data']['customer']->name ?? '' }},</b>
            <p>
              We have 
              {{ $maildata['data']['typeName'] === 'schedule' ? 'scheduled' : 'rescheduled' }} 
              your service request. Our technician {{ $maildata['data']['technician']->name ?? '' }} will come on 
              {{ $newFormattedDateTimeAdd->format('F j, Y, g:i a') }}.
            </p>
          
            <b>- Thanks (Frontier team)</b>
          </td>
        </tr>
        <!-- end row -->
      </tbody>
    </table>
</div>
@endsection
