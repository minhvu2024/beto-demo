<div class="menuextras">
    <div class="extras">
        <ul>
            <li class="shopping-cart-items">
                <i class="glyphicon glyphicon-shopping-cart icon-white"></i> <a
                        href="#"><b>3 items</b></a>
            </li>
            <li>
                <div class="dropdown choose-country">
                    <a class="#" data-toggle="dropdown" href="#"><img {!! ZoeImage('/theme/zoe/img/flags/gb.png') !!}
                                alt="Great Britain"> UK</a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="menuitem"><a href="#"><img {!! ZoeImage('/theme/zoe/img/flags/us.png') !!}
                                                             alt="United States">US</a></li>
                        <li role="menuitem"><a href="#"><img {!! ZoeImage('/theme/zoe/img/flags/de.png') !!}
                                                             alt="Germany">DE</a></li>
                        <li role="menuitem"><a href="#"><img {!! ZoeImage('/theme/zoe/img/flags/es.png') !!}
                                                             alt="Spain">ES</a></li>
                    </ul>
                </div>
            </li>
            @if(auth())
                <li><a href="/logout">Logout</a></li>
            @else
                <li><a href="/login">Login</a></li>
            @endif
        </ul>
    </div>
</div>
