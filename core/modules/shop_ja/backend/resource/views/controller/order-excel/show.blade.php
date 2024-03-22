@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản lý đơn hàng Excel"]) !!}
        <button type="button"  onclick="Export()">Export</button>
        <button type="button"  onclick="Save()">Save</button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('shop_ja::form.show')
@endsection
