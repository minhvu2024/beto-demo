@section('content-header')
    <h1>
        {!! @z_language(["Manager Page"]) !!}
        {!! Form::btn_save("form_store"); !!}
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb

    @include('backend::form.page')
@endsection