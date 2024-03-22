@section('content-header')
    <h1>
        &starf; {!! @z_language(["Manager Component"]) !!}
        <small>it all starts here</small>
    </h1>
@endsection
@section('content')
    <div class="box box box-zoe" id="sectionList">
        <div class="box-header with-border">
            <div class="box-tools">
                <form action="" id="filter_search_form">
                    <div class="input-group input-group-sm hidden-xs" style="width: 250px;"><input type="text"
                                                                                                   name="filter.search"
                                                                                                   class="form-control pull-right"
                                                                                                   value=""
                                                                                                   placeholder="Search">
                        <div class="input-group-btn">
                            <button type="button" id="BtnSearch" class="btn btn-default"><i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div style="padding: 5px"><a href="http://localhost:8000/admin/page">Tất cả</a> | <a href="?status=1">Công
                    cộng</a> |
                <a href="?status=0">Ẩn</a></div>
        </div>
        <div class="box-body listMain">
            <table class="table table-bordered">
                <thead>
                <tr>

                    <th class="column  column-primary  column-id  ">
                        Mã
                    </th>
                    <th class="column  column-primary  column-title  ">
                        Tiêu đề
                    </th>
                    <th class="column  column-status  ">
                        Trạng thái
                    </th>
                    <th class="column  column-date  ">
                        Ngày tạo
                    </th>
                    <th class="column  column-date  ">
                        Ngày sửa
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="list-row">

                    <td class="column  column-primary  column-id">
                        <div class="label-text">2</div>
                    </td>
                    <td scope="col" class="column column-primary column-name "><strong><a class="row-title" href="#">
                                <div class="label-text">Liên hệ</div>
                            </a></strong>
                        <div class="row-actions" style="display: none;"><span class="edit"><a
                                        href="http://localhost:8000/admin/page/edit/2"> Edit </a> |
                                                                                                                    </span><span
                                    class="preview"><a href="http://localhost:8000/admin/page/edit/2"> Preview </a> |
                                                                                                                    </span><span
                                    class="trash"><form id="trash-form"
                                                        action="http://localhost:8000/admin/page/delete/2" method="post"
                                                        style="display: none;"><input type="hidden" name="_token"
                                                                                      value="xDY4IetpfJqixhIku5Kn9GPBRS1GIgBkD7LTwylC">                                                    </form><a
                                        href="#"
                                        onclick="event.preventDefault(); document.getElementById('trash-form').submit();"> Trash </a>
                                                                                                                    </span>
                        </div>
                        <div class="tool">
                            <button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></button>
                        </div>
                    </td>
                    <td data="col" class="column  ">
                        <div class="label-text">
                            <div class="text-center"><span class="label label-danger">Ẩn</span></div>
                        </div>
                    </td>
                    <td data="col" class="column  ">
                        <div class="label-text">2019-07-27 03:35:32</div>
                    </td>
                    <td data="col" class="column  ">
                        <div class="label-text">2019-08-19 10:15:25</div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix"></div>
    </div>
@endsection