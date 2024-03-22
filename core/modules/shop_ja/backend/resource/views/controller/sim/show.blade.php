@section('content-header')
    <h1>
        {!! @z_language(["Quản lý Sim"]) !!}
        {!! Form::btn_save("form_store"); !!}
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('shop_ja::form.sim')
@endsection
