@extends('layouts.app')
{{-- Web site Title --}}
@section('title') {!!  $exchange->title !!} :: @parent @stop

@section('meta_author')
    <meta name="author" content="{!!  $exchange->title !!}"/>
@stop
{{-- Content --}}
@section('content')
    <div class="col-md-10">
        <div class="page-header">
            @if(Auth::check())
                @if(Auth::user()->admin==1)
                    <h3>
                        <a href="{{ URL::to('exchangers/'. $exchange->user_id) }}">{!! $exchange_user->username !!}</a>
                        / {!!  $exchange->title !!}
                    </h3>
                @else
                    <h3>{!!  $exchange->title !!}</h3>
                @endif
            @endif

            @if (Session::has('message'))
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message') }}</p>
                </div>
            @endif
            @if (Session::has('message-error'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message-error') }}</p>
                </div>
            @endif

        </div>
        <div class="page-content">
            <div class="col-sm-12">
                <div class="well row">
                    <div class="col-md-2">
                        @if ($exchange->picture)
                            <img src="{{ asset('/images/exchange/'.$exchange->id.'/'. $exchange->picture) }}"
                                 class="img-responsive" alt="">
                        @else
                            <img src="{{ asset('img/no_photo.png') }}"
                                 class="img-responsive" alt="">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <address>
                            <strong>{!! $exchange->cities->name !!}</strong><br>
                            {!! $exchange->address !!}
                            <br>
                            <br>
                            <abbr title="Phone">Телефоны:</abbr><br>
                            {!! $exchange->phones !!}<br>
                            <b title="is_open">Состояние:</b>
                            @switch($exchange->is_open)
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
                        </address>
                    </div>
                    <div class="col-md-4">
                        @if (!empty($exchange->telegram_link))

                            <a href="{{ $exchange->telegram_link }}" class="btn btn-block btn-primary pull-right">Telegram</a>
                        @endif
                        @if (!empty($exchange->whatsapp_link))

                            <a href="{{ $exchange->whatsapp_link }}" class="btn btn-block btn-success pull-right">WhatsApp</a>
                        @endif
                        {!! Form::model($cash, array('url' => URL::to('subscribes') . '/' . $exchange->id, 'method' => 'POST', 'class' => 'bf', 'files'=> true)) !!}

                        {{--                        <input type="email" placeholder="E-mail для подписка" class="form-control"--}}
                        {{--                               style="margin-top: 78px; margin-bottom: 2px;">--}}
                        <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                            <div class="controls" style="margin-top: 78px">
                                {!! Form::text('email', isset(Auth::user()->email) ? Auth::user()->email : null, ['class' => 'form-control', 'placeholder'=>"E-mail для подписке"]) !!}
                                <input type="hidden" value="{{$exchange->id}}" name="exchanger_id">
                                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                                <button type="submit" class="btn btn-default pull-right">Подписаться</button>

                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @if($cash)
                        <table class="table table-responsive table-striped">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    Последнее обновление <br>
                                    {!! $cash->updated_at !!}
                                </th>
                            </tr>
                            <tr>
                                <th>Покупка</th>
                                <th></th>
                                <th>Продажа</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($currency as $c)

                                @if($exchange->diff_exchange ==1 && ( Auth::guest() || (!Auth::guest() && Auth::user()->diff_exchange == 1)))
                                    @php(
                                        $diff = \App\Models\Currency\Helpers\ExchangeHelper::getDistanceText($cache_distanceRates,$c->currency_id)
                                        )
                                  @else
                                    @php(
                                       $diff = ['buy'=>'','sell'=>'']
                                       )
                                @endif


                                <tr>
                                    <td data-currency-type="buy" data-exchange-id="{{$exchange->id}}"  class="{!! $c->code !!} calc text-center view-calc">
                                        @if(isset($cash->rates_json[strtolower($c->code . '_buy')]))
                                            {{$cash->rates_json[strtolower($c->code . '_buy')]}}
                                            @if(strlen($diff['buy']) > 0)
                                                <br>{!!  $diff['buy']!!}
                                            @endif
                                        @else
                                            {{ ' ' }}
                                        @endif
                                    </td>

                                    <th class="{!! $c->code !!}  text-center ">{!! $c->code !!}</th>
                                    <td data-currency-type="sell" data-exchange-id="{{$exchange->id}}" class="{!! $c->code !!} calc text-center view-calc">
                                        @if(isset($cash->rates_json[strtolower($c->code . '_sell')]))
                                            {{$cash->rates_json[strtolower($c->code . '_sell')]}}
                                            @if(strlen($diff['sell']) > 0)
                                                <br>{!!  $diff['sell'] !!}
                                            @endif
                                        @else
                                            {{ ' ' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Вниамние!</strong> Обменный пункт не имеет актуальных данных по курсам
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="controls">
                            <div class="form-group col-md-3">
                                {!! Form::label('year_filter', "Год", array('class' => 'control-label')) !!}
                                <div class="controls">
                                    {!! Form::select('year_filter', $years_list, null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('currency_filter', "Валюта", array('class' => 'control-label')) !!}
                                <div class="controls">
                                    {!! Form::select('currency_filter', $currency_list, null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="graphic-month" style="height: 400px;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                <span class="sr-only">Загрузка</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('right_sidebar')
    @include('partials.right_column')
@stop

@section('scripts')

    <script src="{{ asset('js/highstock.js') }}"></script>
    <script src="{{ asset('js/jquery.timer.js') }}"></script>
    <script>
        var $chart = $('#graphic-month'), $body = $('body');


        Highcharts.setOptions({
            lang: {
                loading: 'Загрузка...',
                months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
                exportButtonTitle: "Экспорт",
                printButtonTitle: "Печать",
                rangeSelectorFrom: "С",
                rangeSelectorTo: "По",
                rangeSelectorZoom: "Период",
                downloadPNG: 'Скачать PNG',
                downloadJPEG: 'Скачать JPEG',
                downloadPDF: 'Скачать PDF',
                downloadSVG: 'Скачать SVG',
                printChart: 'Напечатать график'
            }
        });

        var seriesOptions = [],
            seriesCounter = 0,
            names = ['SELL', 'BUY'],
            createChart = function () {
                $chart.highcharts('StockChart', {
                    rangeSelector: {
                        allButtonsEnabled: false,
                        buttons: [{
                            type: 'month',
                            count: 1,
                            text: '1 Месяц'
                        }, {
                            type: 'month',
                            count: 3,
                            text: '3 Месяца'
                        }, {
                            type: 'month',
                            count: 6,
                            text: '6 Месяцев'
                        }, {
                            type: 'all',
                            text: 'Все'
                        }],
                        buttonTheme: {
                            width: 60
                        },
                        selected: 2
                    },
                    series: seriesOptions
                });
            };

        function load_chart(currency_filter, year_filter, exchange_id) {
            $.each(names, function (i, name) {
                $.post('/getRates', {
                    _token: '{{ Session::token() }}',
                    type: name,
                    currency_filter: parseInt(currency_filter),
                    exchange_id: parseInt(exchange_id),
                    year_filter: parseInt(year_filter)
                }, function (data) {

                    seriesOptions[i] = {
                        name: name,
                        data: data,
                        marker: {
                            enabled: null,
                            radius: 3,
                            lineWidth: 1,
                            lineColor: '#FFFFFF'
                        },
                        tooltip: {
                            valueDecimals: 2
                        }
                    };

                    seriesCounter += 1;

                    if (seriesCounter === names.length) {
                        createChart();
                    }
                });
            });
        }

        load_chart(
            '{!! $currency[0]->currency_id !!}}',
            '{!! $years_list[0] !!}}',
            '{!! $exchange->id !!}}'
        );

        $('.controls > select').on('change', function () {
            $chart.highcharts().destroy();
            $chart.html('<div class="progress">\
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"\
                            aria-valuemin="0" aria-valuemax="100" style="width: 100%">\
                    <span class="sr-only">Загрузка</span>\
                    </div>\
                    </div>');
            seriesCounter = 0;
            load_chart(
                $('#currency_filter option:selected').val(),
                $('#year_filter option:selected').text(),
                '{!! $exchange->id !!}}'
            );
        });

        var currency_list = {!! json_encode($dataCalc) !!};
        var popOverSettings = {
            placement: 'bottom',
            container: 'body',
            trigger: 'click',
            html: true,
            selector: '.calc',
            content: function () {
                var exchange_id = $(this).data('exchange-id');
                var currency_type = $(this).data('currency-type');
                var value = parseFloat($(this).text());
                rate = parseFloat(0);

                var current_currency_list = '';
                var textCur ='';
                for(var i=0;i<currency_list.length;i++){
                    if(currency_list[i]['exchange_id'] == exchange_id) {
                        for (var j =0; j<currency_list[i]['currencies'].length;j++) {

                            if($(this).attr('class').split(' ')[0] == currency_list[i]['currencies'][j]){
                                textCur += '<option selected  class="currency-option">' + currency_list[i]['currencies'][j] + '</option>';
                            }else{
                                textCur += '<option  class="currency-option">' + currency_list[i]['currencies'][j] + '</option>';
                            }

                            if(j == 0){
                                var first_cur = $(this).attr('class').split(' ')[0].toLowerCase();
                                var  rates_json =  currency_list[i]['rates_json'];
                                var currency = currency_list[i]['currencies'][j].toLowerCase();
                                console.log(rates_json);
                                if( rates_json[currency+'_'+currency_type] != undefined && rates_json[first_cur+'_'+currency_type] !=undefined ) {
                                    console.log('first ='+parseFloat(rates_json[currency + '_' + currency_type]));
                                    if (parseFloat(rates_json[currency + '_' + currency_type]) == 0) {
                                        rate = parseFloat(0);
                                    }else {
                                        console.log('in ='+parseFloat(rates_json[currency + '_' + currency_type]));
                                        rate = parseFloat(((parseFloat(rates_json[first_cur + '_' + currency_type]) / parseFloat(rates_json[currency + '_' + currency_type]))).toFixed(3));
                                    }

                                    console.log(rate);
                                }
                            }
                        }

                        break;
                    }
                }
                var textCur2 = textCur;
                textCur2 += '<option selected  class="currency-option"> KZT </option>';
                textCur  += '<option   class="currency-option"> KZT </option>';


                return '<div class="popover-content-vl">\
                            <div class="input-group rate-calc">\
                                <select data-kzt="'+value+'" style="width:50%;"  data-currency-type="'+currency_type+'" data-exchange-id="'+exchange_id+'" data-first-cur="'+$(this).attr('class').split(' ')[0]+'" onchange="changeSelect2(this)" class="form-control input-group-addon select_currency">'+textCur+'</select>\
                                <input id="first_cur" style="width: 50%;" type="text" class="form-control" value="1">\
                                <span style="width: 10%;" class="input-group-addon"><i class="glyphicon glyphicon-transfer"></i></span>\
                                <input style="width:50%;" id="calc_input" type="text" class="form-control" value="'+value+'">\
                                 <select data-kzt="'+value+'" style="width:50%;" data-currency-type="'+currency_type+'" data-exchange-id="'+exchange_id+'" data-first-cur="'+$(this).attr('class').split(' ')[0]+'" onchange="changeSelect(this)" class="form-control input-group-addon select_currency2">'+textCur2+'</select>\
                            </div>\
                        </div>';
            }
        };

        $body.popover(popOverSettings).on("show.bs.popover", function (e) {
            //  timer.pause();
            $('[data-original-title]').not(e.target).popover("destroy");
        });
        function changeSelect(item){
            var self = $(item);
            var currency = self.val().toLowerCase();
            var first_cur = $('select.select_currency').val().toLowerCase();
            var exchange_id = self.data('exchange-id');
            var currency_type = self.data('currency-type');
            var first_cur_val = $('#first_cur').val();
            var rates_json =null;
            var kzt = self.data('kzt');
            for (var i=0;i<currency_list.length; i++){
                if(currency_list[i]['exchange_id'] == exchange_id) {
                    rates_json =  currency_list[i]['rates_json'];
                    console.log(rates_json);
                    if(currency == 'kzt' && first_cur != 'kzt'){
                        var calcl =parseFloat(parseFloat(rates_json[first_cur + '_' + currency_type]*parseFloat(first_cur_val)).toFixed(3));
                        $('#calc_input').val(calcl);
                    }
                    if(first_cur == 'kzt' && currency != 'kzt'){
                        var calc2 = parseFloat(((parseFloat(first_cur_val)/parseFloat(rates_json[currency + '_' + currency_type]))).toFixed(3));
                        $('#calc_input').val(calc2);
                    }
                    if(first_cur == 'kzt' && currency == 'kzt'){
                        $('#calc_input').val(parseFloat(first_cur_val));
                    } else {
                        if (rates_json[currency + '_' + currency_type] != undefined && rates_json[first_cur + '_' + currency_type] != undefined) {
                            var calc = parseFloat(((parseFloat(rates_json[first_cur + '_' + currency_type]) / parseFloat(rates_json[currency + '_' + currency_type])) * parseFloat(first_cur_val)).toFixed(3));
                            $('#calc_input').val(calc);

                        }
                    }
                }
            }
        }
        function changeSelect2(item){
            var self = $(item);
            var first_cur = self.val().toLowerCase();
            var currency = $('select.select_currency2').val().toLowerCase();
            var exchange_id = self.data('exchange-id');
            var currency_type = self.data('currency-type');
            var first_cur_val = $('#first_cur').val();
            var rates_json =null;
            for (var i=0;i<currency_list.length; i++){
                if(currency_list[i]['exchange_id'] == exchange_id) {
                    rates_json =  currency_list[i]['rates_json'];
                    console.log(rates_json);
                    if(currency == 'kzt' && first_cur != 'kzt'){
                        var calcl =parseFloat(parseFloat(rates_json[first_cur + '_' + currency_type]*parseFloat(first_cur_val)).toFixed(3));
                        $('#calc_input').val(calcl);
                    }
                    if(first_cur == 'kzt' && currency != 'kzt'){
                        var calc2 = parseFloat(((parseFloat(first_cur_val)/parseFloat(rates_json[currency + '_' + currency_type]))).toFixed(3));
                        $('#calc_input').val(calc2);
                    }
                    if(first_cur == 'kzt' && currency == 'kzt'){
                        $('#calc_input').val(parseFloat(first_cur_val));
                    }
                    else {
                        if (rates_json[currency + '_' + currency_type] != undefined && rates_json[first_cur + '_' + currency_type] != undefined) {
                            var calc = parseFloat(((parseFloat(rates_json[first_cur + '_' + currency_type]) / parseFloat(rates_json[currency + '_' + currency_type])) * parseFloat(first_cur_val)).toFixed(3));
                            $('#calc_input').val(calc);

                        }
                    }
                }
            }
        }
        $(document).on('keyup', '.rate-calc > input', function () {
            $('select.select_currency2').trigger('change');
            // var thisVal = parseFloat($(this).val().replace(',', '.'));
            // $(this).siblings().val($(this).prev().prev().get(0) ? thisVal / rate : thisVal * rate);
        });

        $body.on('click', function (e) {
            if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover-content-vl')) {
                $('[data-original-title]').popover('destroy');
                // timer.play();
            }
        });

    </script>
@stop

@section('style')

@stop
