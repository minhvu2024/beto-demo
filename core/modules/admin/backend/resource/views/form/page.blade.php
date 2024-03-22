<div class="col-md-9">
    <div class="box box box-zoe">
        <div class="box-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
            @if(isset($page))
                {!! Form::model($page, ['method' => 'POST','route' => ['backend:page:store'],'id'=>'form_store']) !!}
                {!! Form::hidden('id') !!}
            @else
                {!! Form::open(['method' => 'POST','route' => ['backend:page:store'],'id'=>'form_store']) !!}
            @endif

            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td>
                        {!! Form::label('id_title', 'Tiều đề trang', ['class' => 'title']) !!}
                        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Tiều đề trang']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('id_description', 'Mô tả', ['class' => 'description']) !!}
                        {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Tiều đề trang','cols'=>5,'rows'=>5]) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('id_status', 'Status', ['class' => 'description']) !!}
                        {!! Form::radio('status', '1' , true) !!} Yes
                        {!! Form::radio('status', '0',false) !!} No
                    </td>
                </tr>
                <tr>
                    <td>
                        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                        {!! Form::textarea('content', null, ['class' => 'form-control my-editor']) !!}
                        <script>
                            var editor_config = {
                                    path_absolute: "/",
                                    selector: "textarea.my-editor",
                                    plugins: [
                                        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                        "insertdatetime media nonbreaking save table contextmenu directionality",
                                        "emoticons template paste textcolor colorpicker textpattern"
                                    ],
                                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                                    relative_urls: false,
                                    file_browser_callback: function (field_name, url, type, win) {

                                        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                                        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                                        var cmsURL = '{{route('backend:elfinder:tinymce4')}}' + '?field_name=' + field_name;
                                        console.log(cmsURL);
                                        if (type == 'image') {
                                            cmsURL = cmsURL + "&type=Images";
                                        } else {
                                            cmsURL = cmsURL + "&type=Files";
                                        }
                                        tinyMCE.activeEditor.windowManager.open({
                                            file: cmsURL,
                                            title: 'Filemanager',
                                            width: x * 0.8,
                                            height: y * 0.8,
                                            resizable: "yes",
                                            close_previous: "no",
                                        }, {
                                            oninsert: function (file, fm) {
                                                var url, reg, info;
                                                console.log(file);
                                                win.document.getElementById(field_name).value = file.url;
                                                // URL normalization
                                                url = fm.convAbsUrl(file.url);
                                                console.log(url);
                                                // Make file info
//                                                info = file.name + ' (' + fm.formatSize(file.size) + ')';
//                                                console.log(info);
                                                // Provide file and text for the link dialog
//                                                if (meta.filetype == 'file') {
//                                                    callback(url, {text: info, title: info});
//                                                }
//
//                                                // Provide image and alt text for the image dialog
//                                                if (meta.filetype == 'image') {
//                                                    callback(url, {alt: info});
//                                                }
//
//                                                // Provide alternative source and posted for the media dialog
//                                                if (meta.filetype == 'media') {
//                                                    callback(url);
//                                                }
                                            }
                                        });

                                    }
                                }
                            ;

                            tinymce.init(editor_config);
                        </script>
                    </td>

                </tr>
                </tbody>
            </table>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="box box box-zoe">
        <div class="box-body">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Task</th>
                    <th>Progress</th>
                    <th style="width: 40px">Label</th>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Update software</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-danger">55%</span></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Clean database</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-warning" style="width: 70%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-warning">70%</span></td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Cron job running</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar bg-primary" style="width: 30%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-primary">30%</span></td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Fix and squish bugs</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-success">90%</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('extra-script')
    <script type="text/javascript">

    </script>
@endsection