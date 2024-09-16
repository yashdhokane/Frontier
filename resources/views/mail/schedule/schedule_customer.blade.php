@extends('mail.main')
@section('content')
<div style="padding: 40px; background: #fff">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
      <tbody>
        <!-- start row -->
        <tr>
          <td>
            <b>Hello {{ $maildata['data']['customer']->name ?? '' }},</b>
            <p>
              We have 
              {{ $maildata['data']['typeName'] === 'schedule' ? 'scheduled' : 'rescheduled' }} 
              your service request. Our technician {{ $maildata['data']['technician']->name ?? '' }} will come on 
              {{ \Carbon\Carbon::parse($maildata['data']['schedule']->start_date_time)->format('F j, Y, g:i a') }}.
            </p>
          
            <b>- Thanks (Frontier team)</b>
          </td>
        </tr>
        <!-- end row -->
      </tbody>
    </table>
</div>
@endsection
