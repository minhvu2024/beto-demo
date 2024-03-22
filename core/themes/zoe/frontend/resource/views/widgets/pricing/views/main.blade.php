<div class="section">
    <div class="container">
        <div class="row">
            @foreach($data['data']['lists'] as $k=>$v)
                <div class="col-md-4 col-sm-6">
                    <div class="service-wrapper">
                        <img {!! asset("/theme/zoe/".$v['icon']) !!} alt="Service 1">
                        <h3>{!! $v['name'] !!}</h3>
                        <p>{!! $v['info'] !!}</p>
                        <a target="{!! $v['target'] !!}" href="{!! route($v['link']) !!}" class="btn">Read more</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>