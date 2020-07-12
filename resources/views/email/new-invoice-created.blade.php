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
                            <p>Hello, {{ $toUser->fullname }}</p>
                            <p>An invoice has been created from {{$fromUser->fullname }}.</p>
                            <p>Invoice ID : #INV-{{ $invoice->id }}<br>
                                Invoice amount : $ {{ number_format($invoice->total_amount,2, '.', ',')}}<br>
                                Due date : {{ $invoice->due_date }}<br></p>
                            <p> Invoice link: <a href="{{$invoice->pdf}}" target="_blank">{{ $invoice->pdf }}</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- END MAIN CONTENT AREA -->
    </table>
@endsection
