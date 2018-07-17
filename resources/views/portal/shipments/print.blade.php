<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<style>
<!--
 /* Font Definitions */
 @font-face
  {font-family:"Cambria Math";
  panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
  {font-family:Calibri;
  panose-1:2 15 5 2 2 2 4 3 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
  {margin-top:0in;
  margin-right:0in;
  margin-bottom:8.0pt;
  margin-left:0in;
  line-height:107%;
  font-size:11.0pt;
  font-family:"Calibri","sans-serif";}
a:link, span.MsoHyperlink
  {color:#0563C1;
  text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
  {color:#954F72;
  text-decoration:underline;}
.MsoChpDefault
  {font-family:"Calibri","sans-serif";}
.MsoPapDefault
  {margin-bottom:8.0pt;
  line-height:107%;}
@page WordSection1
  {size:8.5in 11.0in;
  margin:.5in 1.0in .25in 1.0in;}
div.WordSection1
  {page:WordSection1;}
-->
</style>

</head>

<body lang=EN-US link="#0563C1" vlink="#954F72">

<div class=WordSection1>
@if ($txn['parcel_type_id'] == 7)
<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='margin-left:-.15pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=623 colspan=2 valign=top style='width:467.5pt;border:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:10.0pt;
  font-family:"Arial","sans-serif"'>CHEQUE</span></p>
  </td>
 </tr>
 </table>
@endif
<div></div>

<div>
<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none'>
 <tr>
  <td width=623 colspan=2 valign=top style='width:467.5pt;border:solid white 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:14.0pt;
  font-family:"Arial","sans-serif"'>{{$parent_company['name']}}</span></b></p>
  <p><img src="{{asset('images/Elite_logo.png')}}" alt="Logo" height="75px"></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid white 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Email:
  <a href="mailto:{{$parent_company['email']}}">{{$parent_company['email']}}</a></span></p>
  </td>
  <td width=312 valign=top style='width:233.75pt;border-top:none;border-left:
  none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Phone: {{$parent_company['phone']}}</span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid white 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Waybill
  No: {{$txn['awb_num']}}</span></p>
  </td>
  <td width=312 valign=top style='width:233.75pt;border-top:none;border-left:
  none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Date/time: {{$txn['created_at']}}</span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><span
style='font-size:9.5pt;line-height:107%;font-family:"Arial","sans-serif"'>&nbsp;</span></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=624
 style='width:467.75pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  background:#D9D9D9;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><b><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>From
  (Shipper's Information)</span></b></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border:solid windowtext 1.0pt;
  border-left:none;background:#D9D9D9;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><b><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>To
  (Receiver’s Information)</span></b></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Name: {{$txn['sender_name']}}</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Name: {{$txn['receiver_name']}}</span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Company: {{$txn['sender_company_name']}}</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Company: {{$txn['receiver_company_name']}}</span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Phone: {{$txn['sender_phone']}}</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Phone: {{$txn['receiver_phone']}}</span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Address: {{$txn['origin_addr']}}</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Address: {{$txn['dest_addr']}}</span></p>
  </td>
 </tr>
 <tr>
  <td width=624 colspan=2 valign=top style='width:467.75pt;border:solid windowtext 1.0pt;
  border-top:none;background:#D9D9D9;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.5pt;
  font-family:"Arial","sans-serif"'>Shipment Information</span></b></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Type: {{$txn['parcel_type_name']}}</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Mode: 
    @if ($txn['mode'] == 0)
    Normal
    @else ($txn['mode'] == 1)
    Express
    @endif
  </span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Description: {{$txn['parcel_desc']}}
  </span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Round: 
    @if ($txn['round'] == 0)
    No
    @else ($txn['round'] == 1)
    Yes
    @endif</span></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Quantity: {{$txn['units']}}
  </span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>&nbsp;</span></p>
  </td>
 </tr>
 <tr>
  <td width=624 colspan=2 valign=top style='width:467.75pt;border:solid windowtext 1.0pt;
  border-top:none;background:#D9D9D9;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.5pt;
  font-family:"Arial","sans-serif"'>Signatures</span></b></p>
  </td>
 </tr>
 <tr>
  <td width=312 valign=top style='width:233.75pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Sender’s
  signature</span></p>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>&nbsp;</span></p>
  </td>
  <td width=312 valign=top style='width:3.25in;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>Rider’s
  signature</span></p>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:9.5pt;font-family:"Arial","sans-serif"'>&nbsp;</span></p>
  </td>
 </tr>
</table>

<div style='border:none;border-bottom:solid windowtext 1.5pt;padding:0in 0in 1.0pt 0in'>

<p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;border:none;
padding:0in'><span style='font-size:9.5pt;line-height:107%;font-family:"Arial","sans-serif"'>&nbsp;</span></p>

</div>


<p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
text-align:center'><span style='font-size:9.5pt;line-height:107%;font-family:
"Arial","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><span
style='font-size:9.5pt;line-height:107%'>&nbsp;</span></p>
</div>
</div>

</body>

</html>
