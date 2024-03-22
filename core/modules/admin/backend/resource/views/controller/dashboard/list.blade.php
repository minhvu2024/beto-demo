@section('content')
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{!! @z_language(["Dashboard"]) !!}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            Start creating your amazing application!
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->


    <div class="row">
        <div class="col-sm-6">
            <input type="text" id="file_input" class="form-control">
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-default" data-input="#file_input" onclick="openElfinder(this)">Select
                image
            </button>
        </div>
    </div>

    <div class="modal fade" id="elfinderShow">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="elfinder"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="spreadsheet"></div>
    <div id="spreadsheet1"></div>
@endsection
@push('scripts')

    <script src="https://bossanova.uk/jexcel/v4/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v3/jsuites.js"></script>
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v3/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://bossanova.uk/jexcel/v4/jexcel.css" type="text/css" />
    <script>
        var data = [
            ['Cheese', 10, 6.00, "=B1*C1"],
            ['Apples', 5, 4.00, "=B2*C2"],
            ['Carrots', 5, 1.00, "=B3*C3"],
            ['Oranges', 6, 2.00, "=B4*C4"],
        ];


        var SUMCOL = function(instance, columnId) {
            var total = 0;
            for (var j = 0; j < instance.options.data.length; j++) {
                if (Number(instance.records[j][columnId-1].innerHTML)) {
                    total += Number(instance.records[j][columnId-1].innerHTML);
                }
            }
            return total;
        }

        var table = jexcel(document.getElementById('spreadsheet'), {
            data:data,
            minDimensions: [4,10],
            columnDrag:true,
            footers: [['Total','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
            columns: [{
                width:'200px',
            }]
        });

    </script>


    <script>
        var data = [
            ['Jazz', 'Honda', '2019-02-12', '', true, '$ 2.000,00', '#777700'],
            ['Civic', 'Honda', '2018-07-11', '', true, '$ 4.000,01', '#007777'],
        ];

        jexcel(document.getElementById('spreadsheet1'), {
            data:data,
            columns: [
                {
                    type: 'text',
                    title:'Car',
                    width:90
                },
                {
                    type: 'dropdown',
                    title:'Make',
                    width:120,
                    source:[
                        "Alfa Romeo",
                        "Audi",
                        "Bmw",
                        "Chevrolet",
                        "Chrystler",
                        // (...)
                    ]
                },
                {
                    type: 'calendar',
                    title:'Available',
                    width:120
                },
                {
                    type: 'image',
                    title:'Photo',
                    width:120
                },
                {
                    type: 'checkbox',
                    title:'Stock',
                    width:80
                },
                {
                    type: 'numeric',
                    title:'Price',
                    mask:'$ #.##,00',
                    width:80,
                    decimal:','
                },
                {
                    type: 'color',
                    width:80,
                    render:'square',
                },
            ]
        });
    </script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/theme.css') }}">

    <script src="{{ asset('module/admin/assets/elfinder/js/elfinder.min.js') }}"></script>
    <script type="text/javascript">
        function openElfinder(self) {

            $('#elfinderShow').modal();
            $('#elfinder').elfinder({
                debug: false,
//                lang: 'jp',
                width: '100%',
                height: '80%',
                cssAutoLoad: false,
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                commandsOptions: {
                    getfile: {
                        onlyPath: true,
                        folders: false,
                        multiple: false,
                        oncomplete: 'destroy'
                    },
                    ui: 'uploadbutton'
                },
                mimeDetect: 'internal',
                onlyMimes: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif'
                ],
                ui: ['toolbar', 'path', 'stat'],
                {{--onlyMimes: [{{ $mimeTypes }}],--}}
                rememberLastDir: false,
//                height: 300,
//                defaultView: 'list',
                url: '{{ route("backend:elfinder:showConnector") }}?image=1',
                soundPath: '{{ asset('module/admin/assets/elfinder/sounds') }}',
                getFileCallback: function (file) {
                    console.log(file);
                    $($(self).data().input).val(file.url);
                    $('#elfinderShow').modal('hide');
                },
                resizable: false,
                uiOptions: {
                    // toolbar configuration
                    toolbar: [
                        ['home', 'up'],
                        ['upload'],
                        ['quicklook'],
                    ],
                    tree: {
                        openRootOnLoad: true,
                        syncTree: true
                    },
                    // navbar options
                    navbar: {
                        minWidth: 150,
                        maxWidth: 500
                    },
                    cwd: {
                        oldSchool: false
                    }
                }
            }).elfinder('instance');
        }
    </script>
@endpush
