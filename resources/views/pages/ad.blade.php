@extends('layouts.app')
@section('title') Реклама :: @parent @stop
@section('content')
    <div class="col-md-10">
        <div class="page-content">
            <p>
            <br><center><H2>Реклама на valuta.kz</H2>(цены указаны в тенге с учетом НДС)</center>
            </p>
            <table class="table tablesorter-bootstrap  table-responsive table-rate table-hover">
                <tr>
                    <td><strong>Платное размещение</strong> (1 обменный пункт)
                    </td>
                    <td align=center><strong>3 500</strong>/месяц</td>
                </tr>
                <tr>
                    <td><strong>Большой баннер</strong> (до 3 рекламодателей на одно место)</td>
                    <td align=center><strong>15 200</strong>/месяц</td>
                </tr>
                <tr>
                    <td><strong>Средний баннер</strong> (468х60)</td>
                    <td align=center><strong>13 200</strong>/месяц</td>
                </tr>
                <tr>
                    <td><strong>Маленький баннер</strong> (100х100)</td>
                    <td align=center><strong>6 200</strong>/месяц</td>
                </tr>
            </table><br>
            <p>
                <i>По вопросам размещения рекламы обращайтесь по телефону +7 (701) 035-14-00 или по
                электронной почте <a href="mailto:valuta@portex.kz">valuta@portex.kz</a>.</i></p>
        </div>
    </div>
@endsection

@section('right_sidebar')
    @include('partials.right_column')
@stop
