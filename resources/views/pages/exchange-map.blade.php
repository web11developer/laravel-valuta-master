@extends('layouts.app')
<?php
//echo "<pre>";
//print_r($points);
//die('1');
?>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@section('content')

    <div id="map" style="width: 100%; height: 600px"></div>
@endsection

<script type="text/javascript">

    ymaps.ready(function () {
        var jobs = {!! json_encode($infos) !!};
        var points = {!! json_encode($points) !!};


        var myMap = new ymaps.Map('map', {
            center: [43.238949, 76.889709],
            zoom: 12,
            controls: ['smallMapDefaultSet']
        });

        clusterer = new ymaps.Clusterer({
            preset: 'islands#invertedDarkBlueClusterIcons',
            groupByCoordinates: false,
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false
        }),

        myMap.setZoom(12);
        myMap.getZoom();
            getPointData = jobs,
            getPointOptions = function () {
                return {
                    preset: 'islands#redDotIcon',
                };
            },


            points = points,
            geoObjects = [];

        for(var i = 0, len = points.length; i < len; i++) {
            geoObjects[i] = new ymaps.Placemark(points[i], getPointData[i], getPointOptions());
        }

        clusterer.options.set({
            gridSize: 80,
            clusterDisableClickZoom: false,
        });

        clusterer.add(geoObjects);
        myMap.geoObjects.add(clusterer);

        myMap.setBounds(clusterer.getBounds(), {
            checkZoomRange: false,

        });

               myMap.behaviors.disable('scrollZoom');
        myMap.behaviors.disable('Drag');
        ymaps.geolocation.get({
            // Выставляем опцию для определения положения по ip    provider: 'yandex',
            // Карта автоматически отцентрируется по положению пользователя.
            mapStateAutoApply: true,
            resetZoom: 16

        })
            .then(function (result) {
                myMap.geoObjects.add(result.geoObjects);
                myMap.setZoom( 12 );
            });

    });

</script>