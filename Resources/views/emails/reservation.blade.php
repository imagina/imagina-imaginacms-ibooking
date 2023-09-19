@php
  $reservation = $data["reservation"];
@endphp

@extends($data["layout"] ?? setting('notification::templateEmail'))

@section('content')

  <div class="content-email-reservation">

    <h1 class="title" style="text-align: center;width: 80%;font-size: 30px;margin: 12px auto;">
      {{trans('ibooking::reservations.single')}} #{{$reservation->id}}
    </h1>

    @includeFirst(['ibooking.emails.contentEmail','ibooking::emails.contentEmail'])

  </div>

@stop
