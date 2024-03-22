<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>
                <textarea id="editor" name="cfg.template" class="form-control"></textarea>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="row text-center">
        <div class="col-md-12">
            <button type="button" class="btn btn-default" onclick="BtnReview(this)">Review</button>
        </div>

    </div>
    <div id="show_code">
        <textarea id="code_editer" style="display: none"></textarea>
        <div class="error"></div>
    </div>
    <iframe id="iframe-review" src="" frameborder="0" style="width: 100%"></iframe>
</div>
<script>
    function BtnReview(self) {
        var form = $(self).closest('form');
        var data = form.zoe_inputs("get");
        var config = JSON.parse(form.find("#data_config").val());
        console.log(config);
        if (data.hasOwnProperty('cfg')) {
            data.cfg.template = window._editor.getValue();
            config.cfg = $.extend(config.cfg, data.cfg);
        }
        if (data.hasOwnProperty('opt')) {
            config.opt = $.extend(config.opt, data.opt);
        }
        var cmeditor = null;
        $.ajax({
            type: 'POST',
            url: '{{route('backend:layout:ajax:review_blade')}}',
            data: config,
            success: function (data) {
                try {
                    var json = JSON.parse(data);
                    console.log(json);

                    if (json.hasOwnProperty("content")) {

                        if (json.status == 0) {
                            console.log(json.content);
                            if (cmeditor != null) {
                                cmeditor.toTextArea();
                            }
                            $("#show_code").hide();
                            $("#iframe-review").show();
                            $(".error").html("");
                            document.getElementById('iframe-review').contentWindow.document.documentElement.innerHTML = json.content;
                        } else {
                            $("#iframe-review").hide();
                            $("#show_code").show();
                            $(".error").html(json.content);
                            cmeditor = CodeMirror.fromTextArea(
                                document.getElementById('code_editer'), {
                                    lineNumbers: true,
                                    mode: "mustache"
                                }).setValue(json.php);
                        }
                    }
                } catch
                    (e) {

                }
            }
        })
        ;
    }
</script>
