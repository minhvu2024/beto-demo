@section('content-header')
    <h1>
        {!! @z_language(["Chức năng Sim"]) !!}
        <button onclick="Save()" type="button"> Lưu </button> &nbsp;
        <button onclick="Export()" type="button"> Export </button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('shop_ja::form.sim')
@endsection
