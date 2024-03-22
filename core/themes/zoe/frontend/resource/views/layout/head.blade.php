<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{{ $title ?? "Home" }}</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="{{ asset('theme/zoe/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/zoe/css/icomoon-social.css') }}">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="{{ asset('theme/zoe/css/leaflet.css') }}"/>
<!--[if lte IE 8]>
<link rel="stylesheet" href="{{ asset('theme/zoe/css/leaflet.ie.css') }}"/>
<![endif]-->
<link rel="stylesheet" href="{{ asset('theme/zoe/css/main.css') }}">
<script src="{{ asset('theme/zoe/js/modernizr-2.6.2-respond-1.1.0.min.js') }}"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    [lazy-load="true"]{
        animation: timeline;
        animation-duration: 1s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        /*background: linear-gradient(to right, #eeeeee 8%, transparent 18%, #eeeeee 33%);*/
        background: transparent;
        background-size: 800px auto;
        background-position: 100px 0;
    }
    @keyframes timeline {
        0% {
            background-position: -350px 0;
        }
        100% {
            background-position: 400px 0; }
    }
</style>
