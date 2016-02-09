<div class="col-lg-12">
    <div class="container">
        <div class="nav navbar-nav hidden-lg hidden-md hidden-sm visible-xs">
            <button class="navbar-toggle" data-target="#menu-main" data-toggle="collapse" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-collapse hidden-xs">
            <li><a href="/dashboard">Dashboard</a></li>
            @foreach( Auth::user()->userFunctions() as $category => $functions )
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">{{ $category }}<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    @foreach( $functions as $user_function )
                        @if( $user_function->name == 'divider' || $user_function->url == 'divider ')
                        <li class="divider"></li>
                        @else
                        <li><a href="{{ $user_function->url }}">{{ $user_function->name }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
        <ul class="nav navbar-nav navbar-right hidden-xs">
            <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> 1290 / DC</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-cog"></span> {{ !empty(Auth::user()->name) ? Auth::user()->name : '' }}</a></li>
            <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> logout</a></li>
        </ul>
    </div>
</div>
