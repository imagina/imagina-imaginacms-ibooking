<div class="table-reservation-items" style="margin-bottom: 15px; font-size: 10px">

  @foreach($reservation->items as $item)

    @if(!empty($item->category_title))
      <tr>
        <td>{{trans('ibooking::categories.single')}}:</td>
        <td>{{$item->category_title}}</td>
      </tr>
    @endif

    @if(!empty($item->resource_title))
      <tr>
        <td>{{trans('ibooking::resources.single')}}:</td>
        <td>{{$item->resource_title}}</td>
      </tr>
    @endif

    @if(!empty($item->service_title))
      <tr>
        <td>{{trans('ibooking::services.single')}}:</td>
        <td>{{$item->service_title}}</td>
      </tr>
    @endif

    @if($item->price>0)
      <tr>
        <td>{{trans('ibooking::common.table.price')}}:</td>
        <td>{{isiteFormatMoney($item->price)}}</td>
      </tr>
    @endif

    @if(!empty($item->start_date))
      <tr>
        <td>{{trans('ibooking::common.table.start date')}}:</td>
        <td>{{date(config('asgard.ibooking.config.hourFormat'), strtotime($item->start_date))}}</td>
      </tr>
    @endif

    @if(!empty($item->end_date))
      <tr>
        <td>{{trans('ibooking::common.table.end date')}}:</td>
        <td>{{date(config('asgard.ibooking.config.hourFormat'), strtotime($item->end_date))}}</td>
      </tr>
    @endif

    <tr>
      <td>{{trans('ibooking::common.table.status')}}:</td>
      <td>{{$item->statusName}}</td>
    </tr>

  @endforeach



</div>