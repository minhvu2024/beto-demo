@isset($_breadcrumb)

    <ol class="breadcrumb float-right">
        <li><a href="{{$_breadcrumb->home['uri']}}"><i class="fa fa-dashboard"></i> {{$_breadcrumb->home['name']}}</a></li>
        @php $i = 0; $n = count($_breadcrumb->child)-1; @endphp
        @foreach($_breadcrumb->child as $child)
            @if($i++<$n)
                <li><a href="{{$child['uri']}}">{{$child['name']}}</a></li>
            @else
                <li class="active">{{$child['name']}}</li>
            @endif
        @endforeach
    </ol>
@endisset