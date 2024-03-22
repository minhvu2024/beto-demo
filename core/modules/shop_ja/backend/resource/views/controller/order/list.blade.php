@section('content-header')
    <h1>
        &starf; {!! @z_language(["Chức năng quản lý đơn hàng"]) !!}
        <small>it all starts here</small>
        <a href="{{route('backend:shop_ja:order:create')}}"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i> {!! @z_language(["Add New"]) !!} </a>
        @btn_option(["config"=>['name'=>'module:shop_ja:order']])
        @slot('label')
            {{@z_language(["Option"])}}
        @endslot
        @slot('header')
            {{@z_language(["Option"])}}
        @endslot
        @endbtn_option
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb

    @component('backend::layout.component.list',['name'=>'module:shop_ja:order','models'=>$models,'callback'=>$callback])
        @slot("tool")

            <div class="box-body">
                <div class="col-md-12" style="padding:0">
                    <div class="row">
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Code</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input type="text" name="filter.code" class="form-control" id="filter.code" placeholder="Code">
                            </div>
                        </div>
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Name</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input type="text" name="filter.search" class="form-control" id="filter.search"
                                       placeholder="Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <BR>
                        <button type="button" class="btnFilter btn btn-sm btn-primary pull-right">
                            Dữ liệu
                        </button>
                    </div>
                </div>
            </div>
            @foreach(['YAMADA','KOGYJA','OHGA','FUKUI','KURICHIKU','BANH_CHUNG'] as $val)
                <a href="{!! route('backend:shop_ja:excel:'.$val) !!}">
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> {!! $val !!}
                    </button>
                </a>
           @endforeach
        @endslot
    @endcomponent
@endsection
@push('links')

@endpush
@push('scripts')

@endpush
