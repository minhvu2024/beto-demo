@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản lý đơn hàng Excel"]) !!}
        <button type="button" class="btn btn-success" onclick="importBtn();">Cập nhật</button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <div class="container1">
        <div class="row">
            <div class="col-md-6 offset-6">
                <div class="card">

                    <div class="card-body">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="success alert alert-success" style="display: none">
                            Upload Successfully
                        </div>
                        <form enctype="multipart/form-data" id="imageUpload">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <input type="file" name="image" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group"><div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div><input type="text" class="form-control pull-right" id="datepicker"></div></div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-success">Đẩy lên</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border"></div>
                    <div class="box-body" id="results"></div>
                    <div class="box-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('extra-script')
    <style>
        .update{
            color: #3a8104;
        }
        .oke{
            color: green;
        }
        .conflict{
            color: red;
        }
        .empty{
            color: #0000cc;
        }
    </style>

    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <script type="text/javascript">
        $(document).ready(function () {
            $('.success').hide();// or fade, css display however you'd like.
            $datepicker = $('#datepicker').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
            });
            $datepicker.datepicker('setDate', new Date());
        });
        $("input[type=file]").on('change',function(evt){

            var files = evt.target.files;

            var regex = /\d+/g;

            var matches = this.value.match(regex);
            if(matches.length > 1){
                $datepicker.datepicker('setDate',matches[1]+"/"+matches[0]+"/"+ files[0].lastModifiedDate.getFullYear());
            }

        });
        $('#imageUpload').on('submit',(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = new FormData(this);

            formData.append('date',$("#datepicker").val());
            $.ajax({
                type:'POST',
                url: "{{ route('backend:shop_ja:order:excel:imports')}}",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                complete: function(response)
                {
                    if($.isEmptyObject(response.responseJSON.error)){
                        $('.success').show();
                        setTimeout(function(){
                            $('.success').hide();
                        }, 5000);
                        $("#results").html(response.responseJSON.html);
                    }else{
                        printErrorMsg(response.responseJSON.error);
                    }
                }

            });
        }));
        function printErrorMsg(msg){
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
        function importBtn(){
            let lists = [];

            $("#results .update").each(function () {
                lists.push(JSON.parse($(this).find('textarea.value').val()));
            });

            $.ajax({
                type: "POST",
                data: {
                    'type':"import",
                    'com':$("#company").text(),
                    'ship':$("#ship").text().toUpperCase(),
                    'date':$datepicker.val(),
                    lists:lists,
                },
                success: function (data) {
                    $.growl.notice({ message: "{!! z_language('Cập nhật thành công') !!}" });
                }
            });
        }

    </script>
@endsection