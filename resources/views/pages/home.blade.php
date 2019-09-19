@extends('layouts.app')
@section('title') @parent / Курсы обмена валют в обменных пунктах Алматы. Покупка и продажа наличной валюты - евро доллар сша, тенге. Валютный рынок Казахстана. Прогноз валют. @stop
@section('content')
    <br>
    <div class="col-md-10">
        @if (Session::has('message'))
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message') }}</p>
            </div>
        @endif
        <div class="page-content">
            <div class="rate-controls">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Показать валюты <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        @php( $check_count = 1 )
                        @foreach($currency as $idx => $item)

                            <li>
                                <a href="#">
                                    <input type="checkbox" name="{!! $item->code !!}"
                                           {!! $item->visible_bool && $check_count <=3 ? 'checked': '' !!} id="input_{!! $item->code !!}">
                                    <label for="input_{!! $item->code !!}">{!! $item->code !!}</label>
                                </a>
                            </li>
                            @php($check_count++)
                        @endforeach
                    </ul>
                </div>
                <div class="btn-group" role="group" aria-label="table-rate-settings">
                    <button type="button" class="btn btn-default refresh" data-toggle="tooltip" data-placement="top"
                            title="Обновить">
                        <i class="glyphicon glyphicon-refresh"></i>
                    </button>
                    <div class="btn-group" role="group">
                        {!! Form::select('city',$cities, isset($id) ? $id : '', array('class' => 'form-control', 'id' => 'selector_city')) !!}

{{--                        {!! Form::select('city', $cities_list, isset($cities_default) ? $cities_default : '1', array('class' => 'form-control')) !!}--}}
                    </div>
                </div>
            </div>
            <table class="table tablesorter-bootstrap table-responsive table-rate table-hover">
                <thead>
                <tr>
                    <th data-label="Наименование" rowspan="2">Наименование</th>
                    @php( $check_count = 1 )
                    @foreach($currency as $idx => $item)
                        <td colspan="2"
                            class="{!! $item->code !!} text-center {!! $item->visible_bool  && $check_count <=3 ? '': 'tablesaw-cell-hidden' !!}">{!! $item->code !!}</td>
                        @php($check_count++)
                    @endforeach
                    <th data-label="Дата" rowspan="2">Дата</th>
                </tr>
                <tr>
                    @php( $check_count = 1 )
                    @foreach($currency as $idx => $item)
                        <th class="{!! $item->code !!} text-center {!! $item->visible_bool  && $check_count <=3 ? '': 'tablesaw-cell-hidden' !!}">
                            <a href="#">пок.</a></th>
                        <th class="{!! $item->code !!} text-center {!! $item->visible_bool  && $check_count <=3 ? '': 'tablesaw-cell-hidden' !!}">
                            <a href="#">прод.</a></th>
                        @php($check_count++)
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @include('partials.rates_table', [$cash, $currency,$oldRates,$parent_has])
                </tbody>
            </table>
            <div id="map" style="width: 100%; height: 600px"></div>
        </div>

    </div>

@endsection

@section('right_sidebar')
    @include('partials.right_column')
@stop

@section('scripts')
    {{--    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>--}}
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ac4e3d00-4ece-4ddb-9dd3-6d7e3a788f72&load=package.standard&lang=ru-RU"
            type="text/javascript"></script>
    <script type="text/javascript">

        ymaps.ready(function () {
            var jobs = {!! json_encode($infos) !!};
            var points = {!! json_encode($points) !!};
            var  center = {!! json_encode($latlong) !!}
                // alert(center);
            var myMap = new ymaps.Map('map', {

                center: [center.substr(0, 2),center.substr(2, 4)],
                zoom: 10,
                controls: ['smallMapDefaultSet']
            });

            clusterer = new ymaps.Clusterer({
                preset: 'islands#invertedDarkBlueClusterIcons',
                groupByCoordinates: false,
                clusterDisableClickZoom: true,
                clusterHideIconOnBalloonOpen: false,
                geoObjectHideIconOnBalloonOpen: false
            }),

                getPointData = jobs,
                getPointOptions = function () {
                    return {
                        preset: 'islands#redDotIcon'
                    };
                },


                points = points,
                geoObjects = [];

            for (var i = 0, len = points.length; i < len; i++) {
                geoObjects[i] = new ymaps.Placemark(points[i], getPointData[i], getPointOptions());
            }

            clusterer.options.set({
                gridSize: 80,
                clusterDisableClickZoom: false
            });

            clusterer.add(geoObjects);
            myMap.geoObjects.add(clusterer);

            myMap.setBounds(clusterer.getBounds(), {
                checkZoomRange: true
            });

            myMap.behaviors.disable('scrollZoom');
            myMap.behaviors.disable('Drag');
{{--            @if(!isset($id))--}}

{{--            ymaps.geolocation.get({--}}
{{--                mapStateAutoApply: true,--}}


{{--            })--}}
{{--                .then(function (result) {--}}
{{--                    myMap.geoObjects.add(result.geoObjects);--}}
{{--                });--}}
{{--            @endif;--}}
        });

    </script>

    <script src="{{ asset('js/jquery.tablesorter.js') }}"></script>
    <script src="{{ asset('js/jquery.timer.js') }}"></script>
    <script>
        var $table = $('.table-rate'),
            limit = 3,
            lastSortList = [],
            $loader = $('.refresh > .glyphicon'),
            $body = $('body'),
            update = true,
            rate = 0;

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }

        function UpdateTable() {
            $loader.addClass('glyphicon-refresh-animate');
            $loader.parent().removeClass('btn-default')
                .addClass('btn-warning');
            $.get('/getNewCash?t=' + new Date().getTime()+'&&parent='+{{$parent}} +'&&city='+'{{$city}}', function (data) {
                $table.find('tbody').html('').html(data.ratesTable);
                $table.trigger("update");
                $loader.removeClass('glyphicon-refresh-animate');
                $loader.parent().removeClass('btn-warning')
                    .addClass('btn-default');
            });
        }

        var timer = $.timer(function () {
            UpdateTable();
        }, 30000, false);

        $('.refresh').click(function () {
            UpdateTable();
        });

        $('.dropdown-menu input, .dropdown-menu label').click(function (e) {
            e.stopPropagation();
        });

        $table.tablesorter({
            selectorSort: 'a',
            headerTemplate: '{content} {icon}',
            theme: 'bootstrap'
        });
        $(window).on("load", function () {
            $('.dropdown-menu input[type=checkbox]').trigger('change');
        });
        $('.dropdown-menu input[type=checkbox]').on('change', function (e) {
            var $elm = $('.dropdown-menu input[type=checkbox]:checked'),
                show = [];
            if ($elm.length > limit) {
                if ($elm.first().attr('id') != $(e.target).attr('id')) {
                    $elm.first().prop('checked', false);
                } else {
                    $elm.last().prop('checked', false);
                }
            }

            $.each($('.dropdown-menu input[type=checkbox]'), function (idx, elm) {
                var name = $(elm).attr('name');
                if ($(elm).is(':checked') ) {
                    $('.' + name).removeClass('tablesaw-cell-hidden');
                    show.push(name);
                } else {
                    $('.' + name).addClass('tablesaw-cell-hidden');
                }
            });
            //setCookie('currency_show', show.join(','), 30);
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
                                <select  data-kzt="'+value+'" style="width:50%;" data-currency-type="'+currency_type+'" data-exchange-id="'+exchange_id+'" data-first-cur="'+$(this).attr('class').split(' ')[0]+'" onchange="changeSelect2(this)" class="form-control input-group-addon select_currency">'+textCur+'</select>\
                                <input id="first_cur" style="width: 50%;" type="text" class="form-control" value="1">\
                                <span style="width: 10%;" class="input-group-addon"><i class="glyphicon glyphicon-transfer"></i></span>\
                                <input style="width:50%;" id="calc_input" type="text" class="form-control" value="'+value+'">\
                                 <select  data-kzt="'+value+'" style="width:50%;" data-currency-type="'+currency_type+'" data-exchange-id="'+exchange_id+'" data-first-cur="'+$(this).attr('class').split(' ')[0]+'" onchange="changeSelect(this)" class="form-control input-group-addon select_currency2">'+textCur2+'</select>\
                            </div>\
                        </div>';
            }
        };

        $body.popover(popOverSettings).on("show.bs.popover", function (e) {
            timer.pause();
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
                   }else {
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
                    } else {
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
                timer.play();
            }
        });

        $('#selector_city').change(function () {
            var id = $(this).val();
            location.href = "/map-exchange/" + id;
            // $.ajax({
            //     method: 'get', // Type of response and matches what we said in the route
            //     url: 'ajaxupdate', // This is the url we gave in the route
            //     data: {'id': id}, // a JSON object to send back
            //     success: function (response) { // What to do if we succeed
            //         console.log(response);
            //     },
            //     error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
            //         console.log(JSON.stringify(jqXHR));
            //         console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            //     }
            // });
        });


    </script>
@stop
