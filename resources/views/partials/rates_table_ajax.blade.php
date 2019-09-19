
@section('ratesTable')
@foreach($cash as $idx => $item)
    <tr>
        <td>
            @if($item->parent == 0)
                @if($parent_has == true)
                    <a href="{{ URL::to('exchange/'.$item->exchange_id) }}">{!! $item->title !!}</a>
                @else
                    @if($item->CountChild > 0)
                        <a href="{{ URL::to('/',['parent'=>$item->exchangerID]) }}">+
                            @if(strlen($item->groupName) > 0)
                                {!! $item->groupName !!}
                            @else
                                {!! $item->title !!}
                            @endIf
                        </a>
                    @else
                        <a href="{{ URL::to('exchange/'.$item->exchange_id) }}">{!! $item->title !!}</a>
                    @endif
                @endif

            @else
                <a href="{{ URL::to('exchange/'.$item->exchange_id) }}">{!! $item->title !!}</a>
            @endif
                <p style="font-size: 12px; color: #969695;margin: 5px 0 5px;">
                <i class="glyphicon glyphicon-map-marker"></i>
                @if($item->location)
                    <a class="address" target="_blank" href="{!! $item->location !!}">{!! $item->address !!}</a>
                @else
                    {!! $item->address  !!}
                @endif
                <br>
                {!! isset($item->service_exchanger)? $item->service_exchanger : ''  !!}
                </p>
                @switch($item->is_open)
                    @case(3)
                    <span> круглосуточно</span>
                    @break

                    @case(2)
                    <span> сейчас закрыто</span>
                    @break

                    @case(1)
                    <span> сейчас открыто</span>
                    @break

                    @default
                    <span> не указан</span>
                @endswitch

        </td>
        @php( $check_count = 1 )
        @foreach($currency as $cur)
            @if(isset($item->diffExchange) && $item->diffExchange == 1 && ( Auth::guest() || (!Auth::guest() && Auth::user()->diff_exchange == 1)))

            @php(
            $diff = \App\Models\Currency\Helpers\ExchangeHelper::getDifferenceTextHome($oldRates,$cur->currency_id,$item->exchange_id)
            )
            @else
                @php(
                 $diff = ['buy'=>'','sell'=>'']
                )
            @endif
            <td data-currency-type="buy" data-exchange-id="{{$item->exchange_id}}" data-label="Покупка {!! $cur->code !!}" class="{!! $cur->code !!} calc text-center {!! $cur->visible_bool&& $check_count <=3 ? '': 'tablesaw-cell-hidden' !!}">
                @if(isset($item->rates_json[strtolower($cur->code . '_buy')]))
                 {{$item->rates_json[strtolower($cur->code . '_buy')]}}
                    @if(isset($item->diffExchange) && $item->diffExchange == 1)
                        @if(strlen($diff['buy']) > 0)
                            {!!  $diff['buy'] !!}
                        @endif
                   @endif
                 @else
                    {{ ' ' }}
                @endif
            </td>

            <td data-currency-type="sell" data-exchange-id="{{$item->exchange_id}}" data-label="Продажа {!! $cur->code !!}" class="{!! $cur->code !!} calc text-center {!! $cur->visible_bool&& $check_count <=3 ? '': 'tablesaw-cell-hidden' !!}">
                @if(isset($item->rates_json[strtolower($cur->code . '_sell')]))
                    {{$item->rates_json[strtolower($cur->code . '_sell')]}}
                    @if(isset($item->diffExchange) && $item->diffExchange == 1)
                        @if(strlen($diff['sell']) > 0)
                            {!!  $diff['sell']!!}
                        @endif
                    @endif
                @else
                    {{ ' ' }}
                @endif
            </td>
                @php($check_count++)
        @endforeach
        <td data-label="Дата" class="text-center"><span class="label label-default">{!! date('H:i:s / d-m', strtotime($item->updated_at)) !!}</span></td>
    </tr>

@endforeach
@stop
