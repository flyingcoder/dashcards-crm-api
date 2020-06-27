
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
                  {!! $content !!}
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    <!-- END MAIN CONTENT AREA -->
    </table>
@endsection
