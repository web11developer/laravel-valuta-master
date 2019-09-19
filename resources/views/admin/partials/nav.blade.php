<nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{url('admin/dashboard')}}">Панель управления</a>
    </div>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{ URL::to('') }}"><i class="fa fa-backward"></i> На сайт</a>
                </li>
                <li>
                    <a href="{{url('admin/dashboard')}}">
                        <i class="fa fa-dashboard fa-fw"></i> Панель
                    </a>
                </li>
                <li>
                    <a href="{{url('admin/exchange')}}">
                        <i class="fa fa-dashboard fa-exchange"></i> Обменные пункты
                    </a>
                </li>
                <li>
                    <a href="{{url('admin/language')}}">
                        <i class="fa fa-language"></i> Языки
                    </a>
                </li>
                <li>
                    <a href="{{url('admin/currency')}}">
                        <i class="fa fa-language"></i> Валюта
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="glyphicon glyphicon-bullhorn"></i> Новости и аналитика
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('admin/articlecategory')}}">
                                <i class="glyphicon glyphicon-list"></i>  Категории статей
                            </a>
                        </li>
                        <li>
                            <a href="{{url('admin/article')}}">
                                <i class="glyphicon glyphicon-bullhorn"></i> Статьи
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{url('admin/user')}}">
                        <i class="glyphicon glyphicon-user"></i> Пользователи
                    </a>
                </li>
                <li>
                    <a href="{{ URL::to('auth/logout') }}"><i class="fa fa-sign-out"></i> Выйти</a>
                </li>
            </ul>
        </div>
    </div>
</nav>