<div id="sequence">
    <ul class="sequence-canvas">
        @foreach($data['data']['lists'] as $k=>$v)
            <li class="{!! $v['bg'] !!}">
                <h2 class="title">@zlang($v['name'])</h2>
                <!-- Slide Text -->
                <h3 class="subtitle">@zlang($v['info'])</h3>
                <!-- Slide Image -->
                <img class="slide-img" @ZoeImage($v['image']) alt="{!! $v['info'] !!}"/>
            </li>
        @endforeach
    </ul>
    <div class="sequence-pagination-wrapper">
        <ul class="sequence-pagination">
            @foreach($data['data']['lists'] as $k=>$v)
                <li>{!! $k+1 !!}</li>
            @endforeach
        </ul>
    </div>
</div>