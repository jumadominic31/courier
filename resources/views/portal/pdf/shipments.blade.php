<!DOCTYPE html>
<html>
<head>

	<title>Shipments</title>

	<style>
    body {
      font-family: 'Helvetica', sans-serif; font-size: 14px;
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
	<header >
    <strong>{{$company_details[0]['name']}} </strong><br>
    {{$company_details[0]['address']}}, {{$company_details[0]['city']}} <br>
    Phone: {{$company_details[0]['phone']}}
  </header>
  <footer>Powered by Avanet Technologies</footer>
  <strong>Shipments Report </strong><br>
	Date: {{$curr_date}}<br>
	Total Cost : <strong> KShs. {{$tot_coll}} </strong><br><br>

      <table class="table table-striped" style="font-size:11px" >
          <tr>
          <th>AWB#</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Parcel Type</th>
            <th>Price</th>
            <th>VAT</th>
            <th>Sender <br>Name</th>
            <th>Sender <br>ID#</th>
            <th>Sender <br>Phone</th>
            <th>Receiver <br>Name</th>
            <th>Receiver <br>ID#</th>
            <th>Receiver <br>Phone</th>
        <th>Date <br>Booked</th>
            <th>Parcel <br>Status</th>
            <th>Clerk</th>
          </tr>
          @foreach($txns as $txn)
          <tr>
            <td>{{$txn['awb_num']}}</td>
              <td>{{$txn['origin']['name']}}</td>
              <td>{{$txn['dest']['name']}}</td>
              <td>{{$txn['parcel_type']['name']}}</td>
              <td>{{$txn['price']}}</td>
              <td>{{$txn['vat']}}</td>
              <td>{{$txn['sender_name']}}</td>
              <td>{{$txn['sender_id_num']}}</td>
              <td>{{$txn['sender_phone']}}</td>
              <td>{{$txn['receiver_name']}}</td>
              <td>{{$txn['receiver_id_num']}}</td>
              <td>{{$txn['receiver_phone']}}</td>
              <td>{{$txn['created_at']}}</td>
              <td>{{$txn['parcel_status']['name']}}</td>
              <td>{{$txn['clerk']['fullname']}}</td>
        </tr>
          @endforeach
      </table>

</body>
</html>