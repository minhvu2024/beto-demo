@section('content')
<script type="text/javascript" charset="utf-8">
    // Documentation for client options:
    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
    $().ready(function () {
        $('#elfinder').elfinder({
            // set your elFinder options here
            {{--@if($locale)--}}
                {{--lang: '{{ $locale }}', // locale--}}
                {{--@endif--}}
            customData: {
                _token: '{{ csrf_token() }}'
            },
            url: '{{ route("backend:elfinder:showConnector") }}',  // connector URL
            soundPath: '{{ asset('/module/admin/assets/elfinder/sounds') }}',
            width: '100%',
            height: '100%',
            resizable: false,
            cssAutoLoad: false,
        }).
        elfinder('instance').exec('fullscreen');
    });
</script>
<div id="elfinder" style="width:100%; height:100%; border:none;"></div>
@endsection
