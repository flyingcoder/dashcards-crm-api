<template>
    <v-layout row wrap class="invoice-template">
        <div class="col-md-12">
            <div class="col-md-6">
                <h1 class="title">Invoice</h1>
            </div>
            <div class="col-md-6 text-right">
                <div class="page">
                    <span>Page 1 of 1</span>
                </div>
            </div>
        </div>
        <div class="col-md-12  invoice-info">
            <div class="col-md-2 text-center">
                <img src="/img/logo/invoice-logo.png" alt="Logo">
            </div>
            <div class="col-md-7">
                <ul class="list-unstyled informations text-left">
                    <li class="list-item bold">
                        BUZZOOKA Pvt Ltd.
                    </li>
                    <li class="list-item">
                        4073 Longhurst Ave, Niagara Falls,
                    </li>
                    <li class="list-item">
                        ON L2E 6G5, Canada
                    </li>
                    <li class="list-item">
                        Phone: +63123456789
                    </li>
                    <li class="list-item">
                        Email: support@buzzooka.com
                    </li>
                    <li class="list-item">
                        Website: www.buzzooka.com
                    </li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul class="list-unstyled invoice-num text-left">
                    <li class="list-item highlights">
                       Invoice ID
                    </li>
                    <li class="list-item text-highlight">
                        #INV101
                    </li>
                    <li class="list-item highlights">
                        Balance Due
                    </li>
                    <li class="list-item text-highlight">
                        $1,000.00
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-12 invoice-info">
            <div class="col-md-2 text-center">
                <img src="/img/temporary/atomic.png" alt="Logo">
            </div>
            <div class="col-md-5">
                <ul class="list-unstyled informations text-left">
                    <li class="list-item bold">
                        Billed To
                    </li>
                    <li class="list-item bold">
                        Mr. Alan Podemski
                    </li>
                    <li class="list-item">
                        6-3510 27 St NE, Calgary
                    </li>
                    <li class="list-item">
                        AB TIY 5E2, Canada
                    </li>
                </ul>
            </div>
            <div class="col-md-5">
                <table class="table text-left">
                    <tbody>
                        <tr>
                            <td class="highlights">
                                Payment Terms
                            </td>
                            <td>
                                Due on Receipt
                            </td>
                        </tr>
                        <tr>
                            <td class="highlights">
                                Invoice Date
                            </td>
                            <td>
                                02/16/2018
                            </td>
                        </tr>
                        <tr>
                            <td class="highlights">
                                Due Date
                            </td>
                            <td>
                                02/23/2018
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table list-invoice">
				<thead>
					<tr class="highlights">
						<th>
							Description
						</th>
						<th>
							Rate
						</th>
						<th>
							Hours
						</th>
						<th>
							Amount
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							XYZ Website Design
						</td>
						<td>
							$10/hr
						</td>
						<td>
							25
						</td>
						<td>
							$250.00
						</td>
					</tr>
					<tr>
						<td>
							XYZ Website Design
						</td>
						<td>
							$10/hr
						</td>
						<td>
							25
						</td>
						<td>
							$250.00
						</td>
					</tr>
					<tr>
						<td>
							XYZ Website Design
						</td>
						<td>
							$10/hr
						</td>
						<td>
							25
						</td>
						<td>
							$250.00
						</td>
					</tr>
				</tbody>
			</table>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                <ul class="list-unstyled payments text-left">
                    <li class="list-item">
                        Payment Methods: 
                    </li>
                    <li class="list-item">
                        Mode of Payment:
                    </li>
                    <li class="list-item">
                        Account Name:
                    </li>
                    <li class="list-item">
                        Transaction Code:
                    </li>
                    <li class="list-item">
                        SWIFT Code:
                    </li>
                </ul>
            </div>
            <div class="col-md-6 zero">
                <table class="table break-list">
                    <tbody>
                        <tr class="highlights">
                            <td class="bold"> Total </td>
                            <td class="bold"> USD $1,000.00 </td>
                        </tr>
                        <tr>
                            <td class="bold"> Subtotal </td>
                            <td class="bold"> $1,000.00 </td>
                        </tr>
                        <tr>
                            <td> Tax(GST Included) </td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td> Total </td>
                            <td> $1,000.00 </td>
                        </tr>
                        <tr>
                            <td> Payment Made </td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td> Payment Due </td>
                            <td> $1,000.00 </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="terms-and-conditions">
                    <span> Terms & Conditions Applied </span>
                </div>
            </div>
            <div class="col-md-6 authorized text-center">
                <p> Authorized Signature </p>
                <img src="/img/temporary/ross.png">
                <h3 class="zero"> Ross Mosqueda </h3>
                <span> Managing Director </span>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button class="invoice-btn"> Send Invoice </button>
        </div>
    </v-layout>
</template>