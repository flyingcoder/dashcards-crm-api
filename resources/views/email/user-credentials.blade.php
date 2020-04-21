
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
                <p>Hello, {{ $user->first_name }}</p>
                <p>Your username is "{{ $user->email }}",</p>
                <p>Your password is "{{ $password }}".</p>
                <p>You can login here <a href="{{$login_link}}" target="_blank">{{ $login_link }}</a></p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    <!-- END MAIN CONTENT AREA -->
    </table>
@endsection
