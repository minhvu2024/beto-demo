<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">

    <!-- elFinder JS (REQUIRED) -->
    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>
    <script type="text/javascript">
        var FileBrowserDialogue = {
            init: function () {
                // Here goes your code for setting your custom things onLoad.
            },
            mySubmit: function (file, fm) {
                parent.tinymce.activeEditor.windowManager.getParams().oninsert(file, fm);
                parent.tinymce.activeEditor.windowManager.close();
            }
        }
        $().ready(function () {
            var elf = $('#elfinder').elfinder({
                // set your elFinder options here
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ route("backend:elfinder:showConnector") }}',  // connector URL
                soundPath: '{{ asset($dir.'/sounds') }}',
                getFileCallback: function (file, fm) { // editor callback
                    console.log(file);
                    console.log(fm);
                    FileBrowserDialogue.mySubmit(file, fm); // pass selected file path to TinyMCE
                }
            }).elfinder('instance');
        });
    </script>
</head>
<body>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

</body>
</html>
