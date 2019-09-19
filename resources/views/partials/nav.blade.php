{{--@php(        )--}}
<?php
//use Illuminate\Support\Facades\Cache;
//$cities = Cache::remember('cities', 120, function()
//{
//    return  App\Cities::pluck('name', 'id')->toArray();
//});

?>
<nav class="navbar yamm navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('') }}">
                <img src="{{ asset('img/logo.png') }}" alt="">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('/') ? 'active' : '') }}">
                    <a href="{{ URL::to('') }}">Курсы</a>
                </li>
                @if(Auth::check())
                    <li class="{{ (Request::is('exchange*') ? 'active' : '') }}">
                        <a href="{{ URL::to('exchangers/' . Auth::user()->id ) }}">Мои обменные пункты</a>
                    </li>
                @endif









{{--                <li class="dropdown {{ (Request::is('article*') ? 'active' : '') }}">--}}
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"--}}
{{--                       aria-expanded="false">--}}
{{--                        Городы<span class="caret"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu">--}}
{{--                        <div class="yamm-content">--}}
{{--                            <div class="row">--}}

{{--                                <ul class="col-sm-6 list-unstyled list-group left-menu" style="width: 180px">--}}
{{--                                    <li><span class="dropdown-header">Волновой анализ</span></li>--}}
{{--                                    @foreach($cities as $key=> $city)--}}
{{--                                        <li class="list-group-item-sub">--}}
{{--                                            <a href="{{ URL::to('map-exchange', $key) }}">{{$city}}</a>--}}
{{--                                        </li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </ul>--}}
{{--                </li>--}}

























                {{--                <li class="{{ (Request::is('articles') ? 'active' : '') }}">
                                    <a href="{{ URL::to('articles') }}"> Статьи</a>
                                </li>--}}
                <li class="dropdown {{ (Request::is('article*') ? 'active' : '') }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        Новости <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="yamm-content">
                            <div class="row">
                                <ul class="col-sm-6 list-unstyled list-group right-menu">
                                    <li>
                                        <span class="dropdown-header">Новости и аналитика</span>
                                    </li>
                                    <li class="list-group-item"><a href="{{ URL::to('forecasts-eur-usd') }}">
                                            Прогнозы по паре EUR/USD
                                            <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ URL::to('kase-sessions') }}">
                                            Сессии KASE
                                            <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ URL::to('articles/1') }}">
                                            Прогнозы по USD/KZT
                                            <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="col-sm-6 list-unstyled list-group left-menu">
                                    <li><span class="dropdown-header">Волновой анализ</span></li>
                                    <li><span><strong>EURUSD</strong></span></li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/eurusd-longterm') }}">Долгосрочный период</a>
                                    </li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/eurusd-current') }}">Текущая ситуация</a>
                                    </li>
                                    <li><span><strong>GBPUSD</strong></span></li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/gbpusd-longterm') }}">Долгосрочный период</a>
                                    </li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/gbpusd-current') }}">Текущая ситуация</a>
                                    </li>
                                    <li><span><strong>USDRUB</strong></span></li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/usdrub-longterm') }}">Долгосрочный период</a>
                                    </li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/usdrub-current') }}">Текущая ситуация</a>
                                    </li>
                                    <li><span><strong>Нефть (Brent)</strong></span></li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/brent-longterm') }}">Долгосрочный период</a>
                                    </li>
                                    <li class="list-group-item-sub">
                                        <a href="{{ URL::to('wave/brent-current') }}">Текущая ситуация</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </ul>
                </li>
                <li class="{{ (Request::is('map-exchange') ? 'active' : '') }}">
                                    <a href="{{ URL::to('map-exchange') }}">Карта</a>
                                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ URL::to('auth/login') }}"><i
                                    class="fa fa-sign-in"></i> Войти</a></li>
                    <li class="{{ (Request::is('auth/register') ? 'active' : '') }}"><a
                                href="{{ URL::to('auth/register') }}">Регистрация</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><i class="glyphicon glyphicon-user"></i> {{ Auth::user()->username }}
                            <i
                                    class="glyphicon glyphicon-menu-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::check())
                                @if(Auth::user()->admin==1)
                                    <li>
                                        <a href="{{ URL::to('admin/dashboard') }}">
                                            <i class="glyphicon glyphicon-dashboard"></i> Панель управления
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->admin==1 || in_array("article", explode(",", Auth::user()->access)))
                                    <li>
                                        <a href="{{ URL::to('article/create') }}">
                                            <i class="glyphicon glyphicon-pencil"></i> Новая статья
                                        </a>
                                    <li>
                                        <a href="{{ URL::to('article') }}">
                                            <i class="glyphicon glyphicon-pencil"></i> Новости</a>
                                    </li>
                                @endif
                                <li><a href="{{ URL::to('user/'.Auth::user()->id.'/edit') }}"><i
                                                class="glyphicon glyphicon-gift"></i> Настройки</a></li>
                                <li role="presentation" class="divider"></li>
                            @endif
                            <li>
                                <a href="{{ URL::to('auth/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Выйти</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>