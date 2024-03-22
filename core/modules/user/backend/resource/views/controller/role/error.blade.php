@section('content-header')
    <h1>
        {!! @z_language(["LỖi"]) !!}
        <small>it all starts here</small>
    </h1>
@endsection
@section('content')
<div class="error-page">
    <h2 class="headline text-red">Không có quyền truy cập</h2>

    <div class="error-content">
        <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

    </div>
</div>
@endsection