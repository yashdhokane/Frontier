@extends('mail.main')
@section('content')
<div style="padding: 40px; background: #fff">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
      <tbody>
        <!-- start row -->
        <tr>
          <td>
            <b>Hello {{ $maildata['data']['technician']->name }},</b>
            <p>
              We have {{ $maildata['data']['typeName'] === 'schedule' ? 'scheduled' : 'rescheduled' }}  your service request.
            </p>
            <b>- Thanks (Frontier team)</b>
          </td>
        </tr>
        <!-- end row -->
      </tbody>
    </table>
  </div>

  @endsection