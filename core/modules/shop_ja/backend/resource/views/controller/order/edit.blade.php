@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản lý đơn hàng"]) !!}
        <button type="button" class="btn btn-default btn-md btnSave"> Save </button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('shop_ja::form.order')
@endsection
