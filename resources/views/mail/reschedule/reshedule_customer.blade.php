@extends('mail.main')
@section('content')
<div style="padding: 40px; background: #fff">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
      <tbody>
        <!-- start row -->
        <tr>
          <td>
            <b>Dear Sir/Madam/Customer,</b>
            <p>
              This is to inform you that, Your account with AdminX has been created
              successfully. Log it for more details.
            </p>
            <a
              href="javascript: void(0);"
              style="
                display: inline-block;
                padding: 11px 30px;
                margin: 20px 0px 30px;
                font-size: 15px;
                color: #fff;
                background: #4fc3f7;
                border-radius: 60px;
                text-decoration: none;
              "
            >
              Call to action button
            </a>
            <p>
              This email template can be used for Create Account, Change Password, Login
              Information and other informational things.
            </p>
            <b>- Thanks (Wrappixel team)</b>
          </td>
        </tr>
        <!-- end row -->
      </tbody>
    </table>
  </div>

  @endsection