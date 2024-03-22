@section('content-header')
    <h1>
        {!! @z_language(["Manager Page"]) !!}
        {{--<button type="button" class="btn btn-default btn-md" onclick="event.preventDefault(); document.getElementById('form_store').submit();"> {!! @z_language(["Save"]) !!} </button>--}}
        {!! Form::btn_save("form_store"); !!}
</h1>
@endsection
@section('content')
@breadcrumb()@endbreadcrumb
@include('backend::form.page')
@endsection