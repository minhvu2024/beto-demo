<table class="table table-bordered">
    <tr>
        <td style="width: 20%;vertical-align: center;"><strong>Router</strong></td>
        <td>
            @php
                $routes = collect(\Route::getRoutes())->map(function ($route) { return ['uri'=> $route->uri(),'name'=> $route->getName()]; });
            @endphp
            <select class="selectChange form-control" name="opt.router">
                @foreach($routes as $route)
                    @if(isset($data['attrs']['config']))
                        @if($data['attrs']['config'] != "all")
                            @php $arr_name =  explode(':',$route['name']); @endphp
                            @continue($data['attrs']['config']!=$arr_name[0])
                        @endif
                    @endif
                    <option value="{!! $route['name'] !!}" data-uri="{!! $route['uri'] !!}">
                        @php
                            echo $route['uri'];
                        @endphp
                    </option>
                @endforeach
            </select>
        </td>
    </tr>

</table>