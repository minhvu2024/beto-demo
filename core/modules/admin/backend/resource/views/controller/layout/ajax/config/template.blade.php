<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
        @if(isset($data['count']) && $data['count']>1)
            <tr>
                <td>View</td>
                <td>
                    <select name="cfg.template.view" class="form-control cfg-template-view">
                        @for($i=0;$i<$data['count'];$i++)
                            <option value="{{$i}}">Template ({{$i}})</option>
                        @endfor
                    </select>
                    <div style="display: none">
                        @for($i=0;$i<$data['count'];$i++)
                            <textarea id="cfg-template-data-{{$i}}" name="cfg.template.data[{{$i}}]"></textarea>
                        @endfor
                    </div>
                </td>
            </tr>
        @else
            <div style="display: none">
                <textarea name="cfg.template.data[0]"></textarea>
            </div>
            <input type="hidden" name="cfg.template.view" value="0">
        @endif
        <tr>
            <td colspan="3">
                <textarea id="editor" class="form-control"></textarea>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="row text-center">
        <div class="col-md-12">
            {{--<button type="button" class="btn btn-default" onclick="SaveTemplate(this)">Save</button>--}}
            <button type="button" class="btn btn-default" onclick="BtnReview(this)">Save Template</button>
        </div>
    </div>
    <div id="show_code">
        <textarea id="code_editer" style="display: none"></textarea>
        <div class="error"></div>
    </div>
    <iframe id="iframe-review" src="" frameborder="0" style="width: 100%"></iframe>
</div>
<script>
    $('.cfg-template-view').on('change', function () {
        window._editor.setValue($("#cfg-template-data-" + this.value).val());
    });

    function SaveTemplate(self) {
        var form = $(self).closest('form');
        var cfg_template_view = $("#cfg-template-data-" + $(".cfg-template-view").val());
        if (cfg_template_view.length > 0) {
            cfg_template_view.val(window._editor.getValue());
        }
    }

    function BtnReview(self) {
        var form = $(self).closest('form');
        var data = form.zoe_inputs("get");
        var config = JSON.parse(form.find("#data_config").val());
        console.log(config);
        if (data.hasOwnProperty('cfg')) {
            data.cfg.template.data[$(".cfg-template-view").val()] = window._editor.getValue();
            data.cfg.view = "dynamic";
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
                            SaveTemplate(self);
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
