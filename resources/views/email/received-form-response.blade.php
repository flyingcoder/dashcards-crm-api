
@extends('email.master')

@section('title', '')

@section('content')
   <!-- START CENTERED WHITE CONTAINER -->
    <table role="presentation" class="main">

      <!-- START MAIN CONTENT AREA -->
      <tr>
        <td class="wrapper">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <div>
                  <p>A form response was created for the form <strong>{{ $form_name }}</strong><br>
                    Check below link for details <br>
                    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                  </p>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    <!-- END MAIN CONTENT AREA -->
    </table>
@endsection
