@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Thông tin tìm kiếm</h3>
            </div>
            <form role="form">
                <div class="box-body">
                    <div class="form-group">
                        <label for="fullname">Tên Khách Hàng</label>
                        <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Tên Khách Hàng" value="{!! isset($fullname)?$fullname:"" !!}">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input name="address" type="text" class="form-control" id="address" placeholder="Địa chỉ" value="{!! isset($address)?$address:"" !!}">
                    </div>
                    <div class="form-group">
                        <label for="zipcode">Địa chỉ</label>
                        <input name="zipcode" type="text" class="form-control" id="zipcode" placeholder="Mã bưu điện" value="{!! isset($zipcode)?$zipcode:"" !!}">
                    </div>
                    <div class="form-group">
                        <label for="address">Công ty</label>
                        {!! Form::CategoriesNestableOne($category_com,[$cate=>""],"cate","",["auto-action"=>1]) !!}
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if(isset($address) && !empty($address) || isset($fullname) && !empty($fullname) || isset($zipcode) && !empty($zipcode))
@if(isset($model))
    {!! Form::model($model, ['method' => 'POST','route' => ['backend:shop_ja:product:store'],'id'=>'form_store']) !!}
    {!! Form::hidden('id') !!}
@else
    {!! Form::open(['method' => 'POST','route' => ['backend:shop_ja:product:store'],'id'=>'form_store']) !!}
@endif

<table class="table">
    <tr>
        <td style="width: 5%"><input type="text" class="form-control" readonly id="col-row-review"></td>
        <td style="width: 95%">
            <input type="text" class="form-control" id="value-review">
            <div id="zoe-dropdown-review" style="display: none"></div>
        </td>
    </tr>
</table>
<div id="spreadsheet"></div>

@endif
{!! Form::close() !!}
@section('extra-script')
    @if(isset($address) && !empty($address) || isset($fullname) && !empty($fullname) || isset($zipcode) && !empty($zipcode))
    @include('shop_ja::componer.excel', array())
    @endif
    <script>
        {{--function Save(status) {--}}
            {{--if(status === true){--}}
                {{--let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');--}}
                {{--let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');--}}
                {{--let data = spreadsheet.jexcel[worksheet].options.data;--}}
                {{--let name = _spreadsheet.textContent;--}}
                {{--let key = datacache.hasOwnProperty(name)?datacache[name].key:false;--}}
                {{--_spreadsheet.classList.add("cacheAction");--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
                    {{--data:{--}}
                        {{--data:JSON.stringify(data),--}}
                        {{--token:token,--}}
                        {{--act:"cache",key:key,name:name,'id':'{{isset($model)?$model->id:0}}','type':'{{isset($model)?'edit':'create'}}'} ,--}}
                    {{--success: function (data) {--}}
                        {{--console.log(data);--}}
                        {{--_spreadsheet.classList.remove("cacheAction");--}}
                    {{--},--}}
                {{--});--}}
            {{--}else{--}}
                {{--let datas = {};--}}
                {{--$("#spreadsheet .jexcel_tab_link").each(function () {--}}
                    {{--let  worksheet = this.getAttribute('data-spreadsheet');--}}
                    {{--let data = spreadsheet.jexcel[worksheet].options.data;--}}
                    {{--let name = this.textContent;--}}
                    {{--let _columns = [];--}}
                    {{--for(let k in  columnsAll[name] ){--}}
                        {{--_columns.push(k);--}}
                    {{--}--}}
                    {{--datas[name] = {--}}
                        {{--data:data,--}}
                        {{--columns:_columns--}}
                    {{--};--}}
                {{--});--}}
                {{--let form_store = $("#form_store");--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
                    {{--data:{--}}
                        {{--datas:JSON.stringify(datas),--}}
                        {{--info: form_store.zoe_inputs('get'),--}}
                        {{--act:"saveShow",--}}
                        {{--'id':'{{isset($model)?$model->id:0}}',--}}
                        {{--'type':'{{isset($model)?'edit':'create'}}'} ,--}}
                    {{--success: function (data) {--}}
                        {{--if(data.hasOwnProperty('url')){--}}
                            {{--window.location.replace(data.url);--}}
                        {{--}--}}
                    {{--},--}}
                {{--});--}}
            {{--}--}}
        {{--}--}}

    </script>
@endsection


@endsection