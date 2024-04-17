<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta
      name="keywords"
      content="Technician Management, System Management, Frontier Tech Services"
    />
    <meta
      name="description"
      content="Frontier Tech Services - Technician management and system management."
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>Frontier Tech Services - Web Application to manage technicians</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('public/admin/assets/images/favicon.png')}}" />
  </head>

  <body style="margin: 0px; background: #f8f8f8">
    <div
      width="100%"
      style="
        background: #f8f8f8;
        padding: 0px 0px;
        font-family: arial;
        line-height: 28px;
        height: 100%;
        width: 100%;
        color: #514d6a;
      "
    >
      <div style="max-width: 700px; padding: 50px 0; margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
          <tbody>
            <!-- start row -->
            <tr>
              <td style="vertical-align: top; padding-bottom: 30px" align="center">
                <a href="#" target="_blank"
                  ><img
                    src="{{ url('public/admin/assets/images/logo-icon.png')}}"
                    alt="Frontier Tech Services admin"
                    style="border: none" /><br />
                  <img
                    src="{{ url('public/admin/assets/images/logo-text.png')}}"
                    alt="Frontier Tech Services admin"
                    style="border: none"
                /></a>
              </td>
            </tr>
            <!-- end row -->
          </tbody>
        </table>

        @yield('content')

        <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
          <p>
            Powered by Frontier
            <br />
            <a href="javascript: void(0);" style="color: #b2b2b5; text-decoration: underline"
              >Unsubscribe</a
            >
          </p>
        </div>
      </div>
    </div>
  </body>
</html>
