@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản lý đơn hàng Excel"]) !!}
        <button onclick="Save()" type="button"> Lưu </button> &nbsp;
        <button onclick="Export()" type="button"> Export </button>
    </h1>
@endsection
@section('content')
    @include('shop_ja::form.order-excel')
@endsection
