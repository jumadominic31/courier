<!DOCTYPE html>
<html>
<head>

  <title>Shipments</title>

  <style>
    body {
      font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; 
      font-size: 14px;
    } 
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 60px;  text-align: center;}
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 30px; text-align: right; font-size: 12px;}
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    table { border-collapse: collapse; }
    table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
  <script type="text/php">
    if ( isset($pdf) ) {
      // OLD 
      // $font = Font_Metrics::get_font("helvetica", "bold");
      // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
      // v.0.7.0 and greater
      $x = 18;
      $y = 554;
      $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
      $font = $fontMetrics->get_font("helvetica");
      $size = 9;
      $color = array(0,0,0);
      $word_space = 0.0;  //  default
      $char_space = 0.0;  //  default
      $angle = 0.0;   //  default
      $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
  </script>
  <header >
    <strong>{{$company_details[0]['name']}} </strong><br>
    {{$company_details[0]['address']}}, {{$company_details[0]['city']}} <br>
    Phone: {{$company_details[0]['phone']}}
  </header>
  <footer>
    <div style="text-align: left;">Date: {{$curr_date}}</div>
    Powered by Avanet Technologies
  </footer>
  <h1 style="text-align:center;">Invoices Report </h1>
  Date: {{$curr_date}}<br>

  <!-- Options chosen -->
  <h3>Options</h3>
  <div class="container">
    <table class="table" width="100%" style="font-size:12px" >
      <tbody>
        <tr>
          <td width="14.6%"><strong>Invoice #</strong></td>
          <td width="16.6%">{{$invoice_num}}</td>
          <td width="16.6%"><strong>Customer Company</strong></td>
          <td width="18.6%">{{$sender_company_name}}</td>
        </tr>
        <tr>
          <td width="16.6%"><strong>First Booked Date</strong></td>
          <td width="18.6%">{{$first_date}}</td>
          <td width="16.6%"><strong>End Booked Date</strong></td>
          <td width="16.7%">{{$last_date}}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- End options -->
  <div class="container">
    <br>
    Total Amount:<strong> KShs. {{$tot_amount}} </strong><br>
    Total Paid:<strong> KShs. {{$tot_paid}} </strong><br>
    Total Balance:<strong> KShs. {{$tot_bal}} </strong><br>
    No of transactions:<strong> {{$tot_count}} </strong><br>
  </div>

  <h3>Invoice Details</h3>
  Up to 50 records <br>
  <?php $i = 1 ?>
  
  <table class="table table-striped" width=100% style="font-size:11px" >
      <tr>
        <th width="6%"></th>
        <th width="14.66%">Date</th>
        <th width="14.66%">Sender Company</th>
        <th width="14.66%">Invoice#</th>
        <th width="14.66%">Amount</th>
        <th width="14.66%">Paid</th>
        <th width="14.66%">Balance</th>
      </tr>
      @foreach($invoices as $invoice)
      <tr>
        <td>{{$i}}</td>
        <td>{{$invoice['created_at']}}</td>
        <td>{{$invoice['sender_company']['name']}}</td>
        <td>{{$invoice['invoice_num']}}</td>
        <td>{{$invoice['amount']}}</td>
        <td>{{$invoice['paid']}}</td>
        <td>{{$invoice['bal']}}</td>
    </tr>
    <?php $i++ ?>
      @endforeach
  </table>

</body>
</html>