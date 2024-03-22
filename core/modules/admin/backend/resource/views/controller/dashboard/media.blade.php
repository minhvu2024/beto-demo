@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{!! @z_language(["Quản lý file"]) !!}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body" style="height: 800px">
            <iframe style="border: 0; width: 100%; height: 100%" src="{{ route('backend:elfinder:list') }}"></iframe>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
@endsection

