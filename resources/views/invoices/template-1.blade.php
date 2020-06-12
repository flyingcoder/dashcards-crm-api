<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,900,700,500,300,100);

    * {
        margin: 0;
        box-sizing: border-box;

    }
    html {
        background: #eee;
    }
    body {
        background: #fff;
        font-family: 'Roboto', sans-serif;
        background-size: 100%;
        width: 820px;
        margin: auto;
    }

    ::selection {
        background: #f31544;
        color: #FFF;
    }

    ::moz-selection {
        background: #f31544;
        color: #FFF;
    }

    h1 {
        font-size: 1.5em;
        color: #222;
    }

    h2 {
        font-size: .9em;
    }

    h3 {
        font-size: .6em;
        font-weight: 300;
        line-height: 1em;
        color: grey;
    }

    p {
        font-size: .7em;
        color: #666;
        line-height: 1em;
    }

    #invoiceholder {
        width: 100%;
        height: 100%;
        margin: 0 auto;
    }

    #invoice {
        position: relative;
        top: 10px;
        margin: 0 auto;
        width: 100%;
        background: #FFF;
    }

    [id*='invoice-'] {
        /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
        padding: 30px;
    }

    #invoice-top,
    #invoice-mid {
        min-height: 120px;
        width: 100%;
        display: block;
        margin-bottom: 2px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    #invoice-bot {
        min-height: 250px;
        width: 100%;
        display: block;
        margin-bottom: 2px;
    }
    .clear {
        clear: both;
    }
    #company_logo {
        float: left;
        height: 120px;
        width: 170px;
    }

    .info {
        display: inline-block;
        float: left;
        padding-left: 20px;
    }

    .info > * {
        display: block;
        clear: both;
    }
    .table { 
        border: none; 
    }
    .title {
        float: right;
    }

    .title p {
        text-align: right;
    }
    
    .w-75{
        width: 75%;
        float: left;
    }

    .w-50 {
        width: 50%;
        float: left;
    }

    .w-25{
        width: 25%;
        float: left;
    }


    #table table {
        width: 100%;
        border-collapse: collapse;
    }

    #table table td {
        padding: 5px 0 5px 15px;
        border: 1px solid #EEE
    }
    #table table .tabletitle {
        padding: 10px 5px;
        background: #EEE;
    }
    #table table .tabletitle * {
        padding: 10px 5px;
    }
    .service {
        border: 1px solid #EEE;
    }
    .item {
        width: 50%;
        padding-left: 5px; 
    }
    .itemtext {
        font-size: .9em;
    }
    </style>
</head>

<body>
    <div id="invoiceholder">
        <div id="invoice">
            <hr/>
            <div id="invoice-top">
                <div class="w-50">
                    <img src="{company_logo}" id="company_logo">
                </div>
                <div class="w-50">
                    <div class="title">
                        <h1 style="text-align: right;">Invoice {invoice_id}</h1>
                        <p>Issued: {invoice_date_created}<br>
                            Payment Due: {invoice_due_date}
                        </p>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <!--End InvoiceTop-->
            <div id="invoice-mid">
                <div class="w-50">
                    <table class="table">
                        <tr>
                            <td>
                                <div class="info">
                                    <h3>Billed From:</h3>
                                    <h2>{billed_from_name}</h2>
                                    <p> {billed_from_email} <br>
                                        {billed_from_contact}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="w-50">
                    <table>
                        <tr>
                            <td>
                                <div class="info">
                                    <h3>Billed To:</h3>
                                    <h2>{billed_to_name}</h2>
                                    <p> {billed_to_email} <br>
                                        {billed_to_contact}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <!--End Invoice Mid-->
            <div id="invoice-bot">
                <div id="table">
                    <table>
                      <thead>
                        <tr class="tabletitle">
                          <th class="item" align="left">Description</th>
                          <th class="Rate">Rate</th>
                          <th class="Hours">Hours</th>
                          <th class="subtotal">Sub-Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        {invoice_items}
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">Discount</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">${total_discount}</p>
                            </td>
                        </tr>
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">Shipping</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">${total_shipping}</p>
                            </td>
                        </tr>
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">Tax</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"></p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">${total_tax}</p>
                            </td>
                        </tr>
                        <tr class="tabletitle">
                            <td></td>
                            <td></td>
                            <td class="Rate">
                                <h2>Total</h2>
                            </td>
                            <td class="payment">
                                <h2>${total_amount}</h2>
                            </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <div style="margin: 5px auto;">
                  <h2>Terms And Conditions</h2>
                  <p>{invoice_terms}</p>
                </div>
                <div style="margin: 5px auto;">
                  <h2>Notes</h2>
                  <p>{invoice_notes}</p>
                </div>
            </div>
            <!--End InvoiceBot-->
        </div>
        <!--End Invoice-->
    </div><!-- End Invoice Holder-->
</body>

</html>