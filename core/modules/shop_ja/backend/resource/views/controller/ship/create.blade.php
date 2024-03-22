@section('content-header')
    <h1>
        {!! @z_language(["Quản lý Tiền ship"]) !!}
        <button type="button" class="btn btn-default btn-md btnSave"> Save </button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('shop_ja::form.ship')
@endsection
