<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 30px;
        line-height: 30px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.txns td {
        font-size: 11px;
        text-align: left;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    /*@media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }*/
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                INVOICE
                            </td>
                            
                            <td>
                                Invoice #: {{$invoice['invoice_num']}}<br>
                                Created: {{date('d-m-Y', strtotime($invoice['created_at']))}}<br>
                                Due: {{date('d-m-Y', strtotime($due_date))}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{$company_details['name']}}<br>
                                {{$company_details['address']}}<br>
                                {{$company_details['city']}}
                            </td>
                            
                            <td>
                                {{$sender_company_name}}<br>
                                {{$sender_cusadmin_fullname}}<br>
                                {{$sender_cusadmin_phone}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="3">
                    <table>
                        <tr>
                            <td>Invoice Period: {{$formatted_month}}<br>Total Shipments: {{$invoice['total_txns']}}</td>
                            <td>Contract Num: {{$contract['contract_num']}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
      
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price (KShs.)
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Base charge upto {{$invoice['min_txns']}} shipments
                </td>
                
                <td>
                    {{number_format($invoice['min_charge'], 2, '.', ',')}}
                </td>
            </tr>
            <tr class="item last">
                <td>
                    Extra {{$invoice['extra_txns']}} shipments at Kes. {{number_format($contract['txn_cost_overlimit'], 2, '.', ',')}}
                </td>
                
                <td>
                    {{number_format($invoice['extra_charge'], 2, '.', ',')}}
                </td>
            </tr>

            
            <tr class="total">
                <td></td>
                
                <td>
                   Sub Total: {{number_format($invoice['subtotal_charge'], 2, '.', ',')}}
                </td>
            </tr>
            <tr class="total">
                <td></td>
                
                <td>
                   Discount: {{number_format($invoice['discount'], 2, '.', ',')}}
                </td>
            </tr>
            <tr class="total">
                <td></td>
                
                <td>
                   Total: {{number_format($invoice['total_charge'], 2, '.', ',')}}
                </td>
            </tr>
            <tr class="total">
                <td></td>
                
                <td>
                   VAT: {{number_format($invoice['vat'], 2, '.', ',')}}
                </td>
            </tr>
            <tr>
                <table>
                    <tr class="heading">
                        <td></td>
                    </tr>
                </table>
            </tr>
            <tr>
                Email: info@danvidlogistics.com, danvidlogistics@yahoo.com,danvidlogistics@gmail.com<br> 
                Website: www.danvidlogistics.com<br>
Timely Delivery
            </tr>
            
        </table>
    </div>
</body>
</html>