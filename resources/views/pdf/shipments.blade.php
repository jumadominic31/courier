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
    <div style="text-align: left;">Date: {{date('d-m-Y', strtotime($curr_date))}}</div>
    Powered by Avanet Technologies
  </footer>
  <h1 style="text-align:center;">Shipments Report </h1>
  Date: {{date('d-m-Y', strtotime($curr_date))}}<br>

  <!-- Options chosen -->
  <h3>Options</h3>
  <div class="container">
    <table class="table" width="100%" style="font-size:12px" >
      <tbody>
        <tr>
          <td width="14.6%"><strong>AWB #</strong></td>
          <td width="16.6%">{{$awb_num}}</td>
          <td width="16.6%"><strong>Customer Company</strong></td>
          <td width="18.6%">{{$sender_company_name}}</td>
          <td width="16.6%"><strong>Rider</strong></td>
          <td width="16.6%">{{$rider_name}}</td>
        </tr>
        <tr>
          <td width="14.6%"><strong>Parcel Status</strong></td>
          <td width="16.6%">{{$parcel_status_name}}</td>
          <td width="16.6%"><strong>First Booked Date</strong></td>
          <td width="18.6%">{{date('d-m-Y', strtotime($first_date))}}</td>
          <td width="16.6%"><strong>End Booked Date</strong></td>
          <td width="16.7%">{{date('d-m-Y', strtotime($last_date))}}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- End options -->
  <div class="container">
    <br>
    No of transactions<strong>{{$tot_count}} </strong><br>
  </div>

  <h3>Transaction Details</h3>
  Up to 100 records <br>
  
  <?php $i = 1 ?>
  
  <table class="table table-striped" width=100% style="font-size:11px" >
      <tr>
        <th width="3%"></th>
        <th width="10.33%">Sender Company</th>
        <th width="9.33%">AWB#</th>
        <th width="13.33%">Origin</th>
        <th width="13.33%">Destination</th>    
        <th width="11.33%">Date Booked</th>
      </tr>
      @foreach($txns as $txn)
      <tr>
        <td>{{$i}}</td>
        <td>{{$txn['sender_company_name']}}</td>
        <td>{{$txn['awb_num']}}</td>
        <td>{{$txn['origin_addr']}}</td>
        <td>{{$txn['dest_addr']}}</td>
        <td>{{date('d-m-Y', strtotime($txn['created_at']))}}</td>
    </tr>
    <?php $i++ ?>
      @endforeach
  </table>

</body>
</html>