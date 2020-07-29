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
                            <p>Hello, {{ $user->fullname }}</p>
                            <p>Your username is "{{ $user->email }}", <br>To set your password follow below link</p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   class="btn btn-primary">
                                <tbody>
                                <tr>
                                    <td align="left">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                            <tr>
                                                <td><a href="{{$link}}" target="_blank">{{ $link }}</a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- END MAIN CONTENT AREA -->
    </table>
@endsection