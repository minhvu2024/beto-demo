@section('content-header')
    <h1>
        {!! @z_language(["Manager Post"]) !!}
        {!! Form::btn_save("form_store"); !!}
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @include('blog::form.post')
@endsection