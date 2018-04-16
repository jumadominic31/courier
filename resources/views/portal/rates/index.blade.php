@extends('layouts.cusapp')

@section('content')
<div class="panel-heading"><h1>Manage Rates </h1> </div>


<script type="text/javascript">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
@endsection