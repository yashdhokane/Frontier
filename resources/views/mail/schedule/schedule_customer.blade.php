@extends('mail.main')
@section('content')
<div style="padding: 40px; background: #fff">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
      <tbody>
        <!-- start row -->
        <tr>
          <td>
            <b>Hello {{ $maildata[1]->name ?? null }},</b>
            <p>
              We have @if($maildata[3] == 'schedule') @else rescheduled @endif your service request. Our technician {{ $maildata[0]->name ?? null}} will come on {{ \Carbon\Carbon::parse($maildata[2]->start_date_time)->format('F j, Y, g:i a') }}.
          </p>
          
          
            <b>- Thanks (Frontier team)</b>
          </td>
        </tr>
        <!-- end row -->
      </tbody>
    </table>
  </div>

  @endsection