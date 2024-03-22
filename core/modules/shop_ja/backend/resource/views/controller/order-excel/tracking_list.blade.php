@section('content-header')
    <h1>
        &starf; {!! @z_language(["Chức năng quản lý Mã kiểm tra"]) !!}
        <small>it all starts here</small>

        @btn_option(["config"=>['name'=>'module:shop_ja:tracking']])
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
    @component('backend::layout.component.list',['name'=>'module:shop_ja:tracking','models'=>$models,'callback'=>$callback])
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
                                <select class="form-control" name="status" id="">
                                    <option value="">{!! z_language("Tất cả") !!}</option>
                                    <option value="-1">{!! z_language("Đợi xử lý") !!}</option>
                                    <option value="1">{!! z_language("Thành công") !!}</option>
                                    <option value="2">{!! z_language("Đang xử lý") !!}</option>
                                    <option selected value="3">{!! z_language("Đã kiểm tra") !!}</option>
                                    <option value="10">{!! z_language("Sai mã") !!}</option>
                                </select>
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
        @slot("main")
            @if(request()->isXmlHttpRequest())
                <script class="script_editable">
                    @foreach($models as $model)
                    $('#editable_{!! $model->id !!}').editable({
                        type:  'textarea',
                        pk:    '{!! $model->id !!}',
                        name:"save:note",
                        template: '<table> <tr> <td>Trạng thái</td><td> <select class="form-control" name="status" id=""> <option value="1">check lại</option> <option value="2">không check nữa</option> </select> </td></tr><tr> <td>Note</td><td> <textarea name="note" class="form-control"></textarea> </td></tr></table>',
                        url:   '{!! url()->current() !!}',
                        title: '{!! z_language('Ghi chú') !!}'
                    });
                    @endforeach
                </script>
            @endif
        @endslot
    @endcomponent
@endsection
@push('links')
    <style>
        .listMain .table tbody tr td.column {
            position: relative;
            padding: 5px 0px 0px 3px;
        }
    </style>
@endpush
@push('scripts')
    <link rel="stylesheet" href="{{asset("module/admin/assets/bootstrap3-editable/css/bootstrap-editable.css")}}">
    <script src="{{asset('module/admin/assets/bootstrap3-editable/js/bootstrap-editable.js')}}"></script>
    @if(!request()->isXmlHttpRequest())
    <script class="script_editable">
        @foreach($models as $model)
        $('#editable_{!! $model->id !!}').editable({
            type:  'textarea',
            pk:    '{!! $model->id !!}',
            name:"save:note",
            template: '<table> <tr> <td>Trạng thái</td><td> <select class="form-control" name="status" id=""> <option value="1">check lại</option> <option value="2">không check nữa</option> </select> </td></tr><tr> <td>Note</td><td> <textarea name="note" class="form-control"></textarea> </td></tr></table>',
            url:   '{!! url()->current() !!}',
            title: '{!! z_language('Ghi chú') !!}'
        });
        @endforeach
    </script>
    @endif
    <script>
        function updateStatus(self){
            let data = $(self).data();

            $(self).hide();
            $.ajax({
                type: "POST",
                data: {
                    act:"updateStatus",
                    id:data.id,
                    status:data.status
                },
                success: function (html) {

                }
            });
        }
        function updateStatusCancel(self){
            let data = $(self).data();
            var person = confirm("Ban có muốn hủy check đơn "+data.id+" "+data.tracking);
            if (person == true) {

                $(self).hide();
                $.ajax({
                    type: "POST",
                    data: {act:"updateStatusCancel",id:data.id,status:data.status},
                    success: function (html) {

                    }
                });
            }
        }
        function updateStatusOke(self){
            let data = $(self).data();
            var person = confirm("Ban có muốn thành công cho đơn "+data.id+" "+data.tracking);
            if (person == true) {

                $(self).hide();
                $.ajax({
                    type: "POST",
                    data: {act:"updateStatusOke",id:data.id,status:data.status},
                    success: function (html) {

                    }
                });
            }
        }
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
            // $("[auto-action=\"1\"]").each(function () {
            //     let dom = $(this);
            //     switch (dom.prop("tagName")) {
            //         case "INPUT":
            //             dom.change(myFunction).keyup(myFunction);
            //             break;
            //         case "SELECT":
            //             dom.change(myFunction);
            //             break;
            //     }
            // });
        });
    </script>
@endpush
