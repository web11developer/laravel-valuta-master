@extends('layouts.app')
{{-- Web site Title --}}
<script type="text/javascript"
        src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=60445215-6d3a-4f88-87fe-8d52b72e5bc9"></script>
@section('title') Новый обменный пункт :: @parent @stop

{{-- Content --}}
@section('content')
    <div class="row">
        <div class="page-header">
            @if (isset($exchange))
                <h2>Редактирование данных обменного пункта</h2>
            @else
                <h2>Новый обменный пункт</h2>
            @endif
        </div>
        <div class="page-content">
            @if (isset($exchange))
                {!! Form::model($exchange, array('url' => URL::to('exchange') . '/' . $exchange->id, 'method' => 'put','id'=>'fupload', 'class' => 'bf', 'files'=> true)) !!}
            @else
                {!! Form::open(array('url' => URL::to('exchange'), 'method' => 'post', 'class' => 'bf','id'=>'fupload', 'files'=> true)) !!}
                {!! Form::hidden('user_id', $user->id) !!}
            @endif

            <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
                {!! Form::label('title', "Название обменного пункта", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('title', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('title', ':message') }}</span>
                </div>
            </div>
            <div class="form-group  {{ $errors->has('service_exchanger') ? 'has-error' : '' }}">
                {!! Form::label('location', "Дополнительная информация об обменном пункте", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::textarea('service_exchanger', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('service_exchanger', ':message') }}</span>
                </div>
            </div>

            <div class="form-group {!! $errors->has('picture') ? 'error' : '' !!} ">
                <div class="controls">
                    {!! Form::label('picture', "Логотип / Изображение", array('class' => 'control-label')) !!}
                    <input name="picture" type="file" class="uploader form-control" id="picture" value="Upload"/>
                    <img id="image_preview"
                         src="{!! isset($exchange) && $exchange->picture ? asset('/images/exchange/'.$exchange->id.'/'. $exchange->picture) : '' !!}"
                         class="{!! isset($exchange) && $exchange->picture ? '' : 'hidden' !!} img-thumbnail"
                         alt="Логотип"/>

                    @if (isset($exchange) && $exchange->picture)
                        <label>
                            {!! Form::checkbox('del_picture', 1, old('del_picture')) !!} Удалить логотип?
                        </label>
                    @endif
                </div>
            </div>

            <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', "E-mail", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
            </div>

            <div class="form-group  {{ $errors->has('phones') ? 'has-error' : '' }}">
                {!! Form::label('phones', "Телефоны", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('phones', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('phones', ':message') }}</span>
                </div>
            </div>

            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                {!! Form::label('city', "Город", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('city', $cities, isset($exchange)? $exchange->city : '1', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('city', ':message') }}</span>
                </div>
            </div>

            <div class="form-group {{ $errors->has('is_open') ? 'has-error' : '' }}">
                {!! Form::label('is_open', "Режим работы", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('is_open', $openTime, $exchange->is_open ? $exchange->is_open : null , ['class' => 'form-control'])!!}
                    <span class="help-block">{{ $errors->first('is_open', ':message') }}</span>
                </div>
            </div>


            {{--            <div class="form-group  {{ $errors->has('address') ? 'has-error' : '' }}">--}}
            {{--                {!! Form::label('address', "Адрес обменного пункта", array('class' => 'control-label')) !!}--}}
            {{--                <div class="controls">--}}
            {{--                    {!! Form::textarea('address', null, array('class' => 'form-control', 'cols' => 10, 'rows'=>3)) !!}--}}
            {{--                    <span class="help-block">{{ $errors->first('address', ':message') }}</span>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class=" row">
                <div class="form-group col-md-6  {{ $errors->has('address') ? 'has-error' : '' }}">
                    {!! Form::label('address', "Адрес обменного пункта", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {{--                    <input type="text" id="suggest1">--}}

                        {!! Form::text('address', null, array('class' => 'form-control', 'id' => 'suggest1', 'cols' => 10, 'rows'=>3)) !!}
                        <span class="help-block">{{ $errors->first('address', ':message') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="header @if($errors->first('coordinates')) text-danger @endif>" style="padding-top: 5px">
                        <b>Кликните по карте, для определения вашего места нахождения.</b></p>
                    {{--                    <span class="help-block">{{ $errors->first('coordinates', ':message') }}</span>--}}
                    <div id="map"></div>
                    <input type="hidden" name="coordinates" id="cord" value="{{$exchange->coordinates}}">
                </div>
            </div>
            <div class="form-group  {{ $errors->has('parent') ? 'has-error' : '' }}">
                {!! Form::label('parent', "Родитель", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('parent', $exchangers, isset($exchange) ? $exchange->parent : null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('parent', ':message') }}</span>
                </div>
            </div>
                @if((isset($exchange) && $exchange->parent==0) || !isset($exchange))
                <div class="form-group   {{ $errors->has('group_name') ? 'has-error' : '' }}">
                    {!! Form::label('group_name', "Название группы обменных пунктов", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('group_name', null, array('class' => 'form-control')) !!}

                        <span class="help-block">{{ $errors->first('group_name', ':message') }}</span>
                    </div>
                </div>
                @endif

            <div class="row">

                <div class="form-group col-md-6  {{ $errors->has('telegram_link') ? 'has-error' : '' }}">
                    {!! Form::label('telegram_link', "Telegram channel link", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('telegram_link', null, array('class' => 'form-control')) !!}

                        <span class="help-block">{{ $errors->first('telegram_link', ':message') }}</span>
                    </div>
                </div>

                <div class="form-group col-md-6 {{ $errors->has('whatsapp_link') ? 'has-error' : '' }}">
                    {!! Form::label('whatsapp_link', "WhatsApp channel link", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('whatsapp_link', null, array('class' => 'form-control')) !!}

                        <span class="help-block">{{ $errors->first('whatsapp_link', ':message') }}</span>
                    </div>
                </div>

            </div>


            <div class="form-group  {{ $errors->has('order_number') ? 'has-error' : '' }}">
                {!! Form::label('order_number', "Порядок сортировки", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('order_number', isset($exchange) ? $exchange->order_number : 0, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('order_number', ':message') }}</span>
                </div>
            </div>

            @if(Auth::user()->admin==1)
                <div class="form-group  {{ $errors->has('location') ? 'has-error' : '' }}">
                    {!! Form::label('location', "Местоположение", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('location', null, array('class' => 'form-control')) !!}
                        <span class="help-block">{{ $errors->first('location', ':message') }}</span>
                    </div>
                </div>

                <div class="form-group  {{ $errors->has('is_visible') ? 'has-error' : '' }}">
                    {!! Form::label('is_visible', "Отображать обменный пункт на главной странице", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::select('is_visible', array('1' => 'ДА', '0' => 'НЕТ'),
                                                        isset($exchange)? $exchange->is_visible : 1,
                                                        array('class' => 'form-control')) !!}
                        <span class="help-block">{{ $errors->first('is_visible', ':message') }}</span>
                    </div>
                </div>
            @endif
                <div class="form-group {{ $errors->has('diff_exchange') ? 'has-error' : '' }}">
                    <div class="controls">
                        {!! Form::label('diff_exchange', "Показать разницу текущей и старой валюты") !!}
                        {!!   Form::checkbox('diff_exchange', 1, isset($exchange) && $exchange->diff_exchange == 1 ? true : false  ) !!}
                        <span class="help-block">{{ $errors->first('diff_exchange', ':message') }}</span>
                    </div>
                </div>

                <div class="form-group  {{ $errors->has('currency_list') ? 'has-error' : '' }}">
                {!! Form::label('currency_list', "Валюты", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('currency_list', isset($selectedCurrencies) ? $selectedCurrencies :null, array('class' => 'form-control input_tags_input')) !!}
                    <span class="help-block">{{ $errors->first('currency_list', ':message') }}</span>
                </div>
            </div>

            <div class="form-group pull-right">
                <button type="reset" class="btn btn-sm btn-default">
                    <span class="glyphicon glyphicon-remove-circle"></span> Сбросить
                </button>
                <button type="submit" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-ok-circle"></span> Сохранить
                </button>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
    <style>
        #map {
            width: 100%;
            height: 50%;
        }
    </style>
    <script type="text/javascript"
            src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=60445215-6d3a-4f88-87fe-8d52b72e5bc9"></script>

@stop

@section('scripts')

    <script>
        var title =  {!! json_encode($exchange->address) !!};
        var exchangeCoord =  {!! json_encode(explode(',', $exchange->coordinates)) !!};

        ymaps.ready(init);
        ymaps.ready(function () {


            if (exchangeCoord.length > 1) {
                var myPlacemark,
                    myMap = new ymaps.Map('map', {
                        center: exchangeCoord,
                        zoom: 10,
                        controls: ['smallMapDefaultSet']

                    }, {
                        searchControlProvider: 'yandex#search'
                    });

                console.log(exchangeCoord);

                // Создадим массив геообъектов.
                myGeoObjects = [];
                myGeoObjects[0] = new ymaps.GeoObject({
                    geometry: {
                        type: "Point",
                        coordinates: exchangeCoord
                    },
                    properties: {
                        clusterCaption: 'Геообъект №1',
                        balloonContentBody: title,
                        iconCaption: title

                    }
                });

// Создадим кластеризатор и запретим приближать карту при клике на кластеры.
                var clusterer = new ymaps.Clusterer({
                    clusterDisableClickZoom: true
                });
                clusterer.add(myGeoObjects);
                myMap.geoObjects.add(clusterer);

            } else {
                var myPlacemark,
                    myMap = new ymaps.Map('map', {
                        center: [43.238949, 76.889709],
                        zoom: 10,
                        controls: ['smallMapDefaultSet']

                    }, {
                        searchControlProvider: 'yandex#search'
                    });


            }


            // Слушаем клик на карте.
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                var names = e.get('balloonContentHeader');
                $("#cord").val(coords.join(','));
                // Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress(coords);
            });

            // Создание метки.
            function createPlacemark(coords) {
                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

            // Определяем адрес по координатам (обратное геокодирование).
            function getAddress(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    $("#suggest1").val([
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '));

                    console.log(firstGeoObject.getLocalities());
                    console.log(firstGeoObject.getThoroughfare() || firstGeoObject.getPremise());
                    myPlacemark.properties
                        .set({
                            // Формируем строку с данными об объекте.
                            iconCaption: [
                                // Название населенного пункта или вышестоящее административно-территориальное образование.
                                firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                                // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                            ].filter(Boolean).join(', '),
                            // В качестве контента балуна задаем строку с адресом объекта.
                            balloonContent: firstGeoObject.getAddressLine()
                        });
                });
            }


        });


        function init() {
            // Создаем выпадающую панель с поисковыми подсказками и прикрепляем ее к HTML-элементу по его id.
            var suggestView1 = new ymaps.SuggestView('suggest1');
            // Задаем собственный провайдер поисковых подсказок и максимальное количество результатов.
            var suggestView2 = new ymaps.SuggestView('suggest2', {provider: provider, results: 3});
        }


        find = function (arr, find) {
            return arr.filter(function (value) {
                return (value + "").toLowerCase().indexOf(find.toLowerCase()) != -1;
            });
        };
        var provider = {
            suggest: function (request, options) {
                var res = find(arr, request),
                    arrayResult = [],
                    results = Math.min(options.results, res.length);
                for (var i = 0; i < results; i++) {
                    arrayResult.push({displayName: res[i], value: res[i]})
                }
                return ymaps.vow.resolve(arrayResult);
            }
        };

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image_preview').attr('src', e.target.result).removeClass('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#picture").change(function () {
            readURL(this);
        });


        $(document).ready(function () {

            var tags = {!!  $currencyCurrencies !!};
            $('.input_tags_input').amsifySuggestags({
                type: 'amsify',
                suggestions: tags,
                whiteList: true
            });

            $('input').keypress(function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    //do something
                }
            });
        });


    </script>
@stop

