@section('content-header')
    <h1>
        &starf; {!! @z_language(["Chức năng quản lý sản phẩm"]) !!}
        <small>it all starts here</small>
        <a href="{{route('backend:shop_ja:product:create')}}"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i> {!! @z_language(["Tạo mới"]) !!} </a>
        @btn_option(["config"=>['name'=>'module:shop_ja:product']])
        @slot('label')
            {{@z_language(["Cấu hình"])}}
        @endslot
        @slot('header')
            {{@z_language(["Cấu hình"])}}
        @endslot
        @endbtn_option
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @component('backend::layout.component.list',['name'=>'module:shop_ja:product','models'=>$models,'callback'=>$callback])
        @slot("tool")

            <div class="box-body">
                <div class="col-md-12" style="padding:0">
                    <div class="row" style="padding: 5px">
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Mã</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input auto-action="1" type="text" name="filter.code" class="form-control" id="filter.code"
                                       placeholder="Code">
                            </div>
                        </div>
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Tiêu đề</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input auto-action="1" type="text" name="filter.search" class="form-control" id="filter.search"
                                       placeholder="Name">
                            </div>
                        </div>
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Mô tả</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input auto-action="1" type="text" name="filter.des" class="form-control" id="filter.des"
                                       placeholder="Mô tả">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px">
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Công ty</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;" >
                                {!! Form::CategoriesNestableOne($nestables,[Form::value('category_id')=>""],"filter.cate","",["auto-action"=>1]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <BR>
                        <button type="button" class="btnFilter btn btn-sm btn-primary pull-right" id="btnSearch">
                            Dữ liệu
                        </button>
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection
@push('links')

@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            let action = false;
            function myFunction(){
                let delay = true;
                if($('js-loading-overlay').length == 0)
                {
                    delay = false;
                }
                if(delay){
                    if(action) return;
                    setTimeout(function () {
                        action = false;
                        $("#btnSearch").click();
                    },1000);
                }else{
                    $("#btnSearch").click();
                }

            }
            $("[auto-action=\"1\"]").each(function () {
               let dom = $(this);
               switch (dom.prop("tagName")) {
                   case "INPUT":
                       dom.change(myFunction).keyup(myFunction);
                       break;
                   case "SELECT":
                       dom.change(myFunction);
                       break;
               }
            });
        });
    </script>
@endpush
