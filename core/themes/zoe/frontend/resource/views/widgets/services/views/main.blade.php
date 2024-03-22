<div class="section">
    <div class="container">
        <div class="row">
            @foreach($data['data']['lists'] as $k=>$v)
                <div class="col-md-4 col-sm-6">
                    <div class="service-wrapper">
                        <img {!! ZoeImage("/theme/zoe/".$v['icon'],$config) !!} alt="Service 1">
                        <h3>@zlang($v['name'])</h3>
                        <p>{!! $v['info'] !!}</p>
                        <a target="{!! $v['target'] !!}" href="{!! route($v['link']) !!}"
                           class="btn">@zlang('Read more')</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>