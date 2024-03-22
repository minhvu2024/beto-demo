var guidGenerator = function () {
    return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
};

function id() {
    return (guidGenerator() + guidGenerator() + "-" + guidGenerator() + "-" + guidGenerator() + "-" + guidGenerator() + "-" + guidGenerator() + guidGenerator() + guidGenerator());
}


var demo = $(".demo");
$(".demo").sortable({
    connectWith: ".column",
    opacity: .35,
    handle: ".drag",
    cursorAt: {top: 0, left: 0},
    stop: function (ui, dom) {
        console.log("stop .demo");
        // console.log(ui.target);
        // console.log($(ui.target).height());
        var arrColumn = $(ui);
        var attr = $(dom.item).attr('data-id');
        if (!(typeof attr !== typeof undefined && attr !== false)) {
            $(dom.item).attr('data-id', id());
        }
        if ($(ui.target).hasClass('demo')) {
            $(dom.item).children('.tool').find('.configuration').trigger('click');
        }


        // var indexPos = arrColumn.index($(ui.target));
        // var list = [];
        // for(var v=0 ; v<arrColumn.length ; v++){
        //     list.push($(arrColumn[v]).height());
        // }
        // var max = Math.max(...list);
        // var index = list.indexOf(max);
        //
        // for(var v=0 ; v<arrColumn.length ; v++){
        //     if(index!=v){
        //         list.push($(arrColumn[v]).height((max)+"px"));
        //     }
        // }
    },
    start: function (ui, a) {

    }
});
$(".demo .column").sortable({
    connectWith: ".column",
    opacity: .35,
    handle: ".drag",
    cursorAt: {top: 0, left: 0},
    stop: function (ui, dom) {
        var attr = $(dom.item).attr('data-id');
        console.log(".demo .column");
        console.log(attr);
        if (!(typeof attr !== typeof undefined && attr !== false)) {
            $(dom.item).attr('data-id', id());
            console.log("create id");
        }
        console.log(ui);
        // if(!$(dom.item).hasAttribute('data-id') || $(dom.item).attr('data-id').length === 0){
        //     $(dom.item).attr('data-id', id());
        // }

        // console.log(ui.target);
        // console.log($(ui.target).height());
        // var arrColumn = $(ui.target);
        // var lists_column = arrColumn.parent().children('.column');
        // if(lists_column.length>1){
        //     console.log(1111111);
        //     var list = [];
        //     for (var v = 0; v < lists_column.length; v++) {
        //         list.push($(lists_column[v]).height() + 30);
        //     }
        //     console.log(list);
        //     var max = Math.max(...list);
        //     var index = list.indexOf(max);
        //     console.log("max:" + max);
        //     console.log(index);
        //
        //     for (var v = 0; v < lists_column.length; v++) {
        //         if (index === v) {
        //             console.log(max);
        //             $(lists_column[v]).height(max);
        //         }
        //     }
        // }
    },
    start: function (ui, dom) {
        $(dom.item).css('width', 250);
    }
});
$(".sidebar-nav .grid").draggable({
    connectToSortable: ".demo",
    helper: "clone",
    handle: ".drag",
    drag: function (e, ui) {
        ui.helper.width(300)
    },
    cursorAt: {
        top: 0,
        left: 0
    },
    stop: function (e, ui) {
        ui.helper.width("100%");
        ui.helper.height("auto");
        if (ui.helper.parent().hasClass("demo")) {
            console.log($(this).attr('data-id'));
            //layout.formSettingGrid($(this).attr('data-id'));
        } else {
            // var arrColumn = ui.helper;
            // console.log("j=>" + $(this).attr('data-id'));
            // resetColumn(ui.helper.parent().parent());
            // var arrColumn = ui.helper.parent().parent().children(".column");
            //
            // var list = [];
            // for (var v = 0; v < arrColumn.length; v++) {
            //     list.push($(arrColumn[v]).height());
            // }
            // var max = Math.max(...list);
            // var index = list.indexOf(max);
            // for (var v = 0; v < arrColumn.length; v++) {
            //     if (index != v) {
            //         list.push($(arrColumn[v]).height((max) + "px"));
            //     }
            // }
        }
        ui.helper.find(".column").sortable({
            opacity: .35,
            handle: ".drag",
            connectWith: ".column",
            zIndex: 10000,
            cursorAt: {top: 0, left: 0},
            stop: function (ui) {
                console.log("stop sidebar-nav .grid");
                //resetColumn($(ui.target).parent());
                // var arrColumn =  $(ui.target).parent().find(">.column");
                // var indexPos = arrColumn.index($(ui.target));
                // var list = [];
                // for(var v=0 ; v<arrColumn.length ; v++){
                //     list.push($(arrColumn[v]).height());
                // }
                // var max = Math.max(...list);
                // var index = list.indexOf(max);
                // for(var v=0 ; v<arrColumn.length ; v++){
                //    if(index!=v){
                //         list.push($(arrColumn[v]).height((max)+"px"));
                //     }
                // }
            },
            start: function (ui) {
                console.log("start");
                console.log(ui);
            }
        });
    },
    start: function (e, ui) {
        $(this).draggable("option", "cursorAt", {
            left: 0,
            top: 0
        });
        // $(this).attr('data-id', id());
    }
});
$(".sidebar-nav .box-pluign").draggable({
    connectToSortable: ".column",
    helper: "clone",
    handle: ".drag",
    drag: function (e, ui) {
        ui.helper.width(300);
    },
    cursorAt: {
        top: 0,
        left: 0
    },
    stop: function (e, ui) {
        console.log("sidebar-nav .box-pluign");
        var attr = ui.helper.attr('data-id');
        if (!(typeof attr !== typeof undefined && attr !== false)) {
            ui.helper.attr("data-id", id());
            console.log("create id");
        }
        ui.helper.removeAttr("style");
        // if (ui.helper.parent().length === 0)
        {
            ui.helper.children('.tool').find('.configuration').trigger('click');
        }
    },
    start: function (e, ui) {
        $(this).draggable("option", "cursorAt", {
            left: 0,
            top: 0
        });
        // $(this).attr('data-id', id());
    }
});

function getSorted(selector, attrName) {
    return $(selector.toArray().sort(function (a, b) {
        var aVal = parseInt(a.getAttribute(attrName)),
            bVal = parseInt(b.getAttribute(attrName));
        console.log(aVal);
        console.log(bVal);
        return aVal - bVal;
    }));
}

function configuration(self) {
    var option = self.children('.option').find('.value textarea');
    var _conf = option.val();
    var config = _parseJSON(_conf);
    var success = function (config) {
        var edit;
        window._editor = null;

        var compiler = {"grid": [], "blade": []};
        if (config.cfg.hasOwnProperty('compiler')) {
            compiler = $.extend(compiler, config.cfg.compiler);
            if (!compiler.grid.push) {
                compiler.grid = Object.values(compiler.grid);
            }
            if (!compiler.blade.push) {
                compiler.blade = Object.values(compiler.blade);
            }
        }
        $.ajax({
            url: $("#layout").attr('uriconfig'),
            type: "POST",
            data: {
                config: config
            },
            success: function (data) {
                var a = bootpopup({
                    title: "Custom HTML",
                    size: "large",
                    content: [
                        data.views.content
                    ],
                    cancel: function (data, array, event) {

                    },
                    ok: function (data, array, event) {

                        // if (data.hasOwnProperty('cfg')) {
                        //    // config.cfg = $.extend(config.cfg, data.cfg);
                        // }
                        // if (data.hasOwnProperty('opt')) {
                        //   //  config.opt = $.extend(config.opt, data.opt);
                        //     //console.log(JSON.stringify(data.opt));
                        // }
                        compiler = {"grid": [], "blade": []};
                        $("#ms-optgroup .ms-selection .ms-optgroup-container").each(function () {

                            $(this).find(".ms-selected").each(function (i, v) {
                                var obj = $(v);
                                var val = obj.attr("data-group").split("-");
                                if (!compiler.hasOwnProperty(val[0])) {
                                    compiler[val[0]] = [];
                                }
                                compiler[val[0]][parseInt(i)] = val[1];
                            });
                        });
                        var _config = $.extend(config, data);
                        _config.cfg.compiler = compiler;

                        _config.cfg.id = self.attr('data-id');

                        option.html(JSON.stringify(_config));
                        if (_config.cfg.hasOwnProperty('public') && _config.cfg.public === "1") {
                            $.ajax({
                                type: 'POST',
                                url: $("#layout").attr('uricom'),
                                data: {
                                    widget: _config,
                                    id: $("#formInfo #id").val(),
                                    type: "create"
                                },
                                success: function (data) {
                                    console.log(data);
                                }
                            });
                        }

                    },
                    before: function (_this) {
                        console.log(config);
                        console.log(_this.form.zoe_inputs("set", config,{

                        }));

                        _this.form.find("#data_config").val(JSON.stringify(config));
                        var _multiSelect = $('#optgroup').multiSelect({
                            selectableOptgroup: true,
                            afterInit: function (container) {
                                this.$selectionContainer.find(".ms-optgroup").each(function () {
                                    console.log("#" + $(this).parent().attr("id") + ">.ms-elem-selection");
                                    $(this).sortable({
                                        items: 'li:not(.ms-optgroup-label)',
                                        stop: function (ev, ui) {
                                            $(this).find(".ms-selected").each(function (i, v) {
                                                var obj = $(v);
                                                obj.attr('data-order', i + 1);

                                                var val = obj.attr("data-group").split("-");
                                                if (compiler.hasOwnProperty(val[0])) {
                                                    compiler[val[0]][i] = val[1];
                                                }
                                            });
                                            console.log(compiler);
                                        }
                                    });
                                    $(this).disableSelection();
                                    getSorted($(this).find(".ms-selected"), "data-order").detach().appendTo($(this));
                                });
                                // getSorted(this.$selectionContainer.find(".ms-selected"),"data-order").detach().appendTo(this.$selectionContainer);
                            },
                            afterSelect: function (values, index) {
                                var val = values[0].split("-");
                                console.log(val[0]);
                                if (!compiler.hasOwnProperty(val[0])) {
                                    compiler[val[0]] = [];
                                }
                                compiler[val[0]].push(val[1]);
                                this.$selectionContainer.find(".ms-optgroup [data-group='" + val[0] + "-" + val[1] + "']").attr("data-order", compiler[val[0]].length);
                                this.$selectionContainer.find(".ms-optgroup").each(function () {
                                    getSorted($(this).find(".ms-selected"), "data-order").detach().appendTo($(this));
                                });
                                console.log(compiler);
                            },
                            afterDeselect: function (values, index) {

                                var val = values[0].split("-");
                                if (compiler.hasOwnProperty(val[0])) {
                                    console.log(compiler[val[0]]);
                                    var _index = compiler[val[0]].indexOf(val[1]);
                                    if (_index !== -1) {
                                        compiler[val[0]].splice(_index, 1);
                                    }
                                }
                                for (var key in compiler) {
                                    if (compiler.hasOwnProperty(key)) {
                                        for (var _key in compiler[key]) {
                                            this.$selectionContainer.find(".ms-optgroup [data-group='" + key + "-" + compiler[key][_key] + "']").attr("data-order", _key + 1);
                                        }
                                    }
                                }
                                console.log(compiler);
                                this.$selectionContainer.find(".ms-optgroup").each(function () {
                                    getSorted($(this).find(".ms-selected"), "data-order").detach().appendTo($(this));
                                });
                            }
                        });
                        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                            var target = $(e.target).attr("href");
                            var editor = $(target + " #editor");
                            var d = $(this).data();

                            if (window.hasOwnProperty("func_" + d.name)) {
                                window["func_" + d.name]();
                            }
                            if (editor.length > 0) {
                                var editorDom = document.getElementById('editor');
                                if (editorDom.style.display !== "none") {
                                    edit = CodeMirror.fromTextArea(editorDom, {
                                        lineNumbers: true,
                                        mode: "mustache"
                                    });
                                    try {
                                        if (config.cfg.template.data.hasOwnProperty(config.cfg.template.view)) {
                                            edit.setValue(config.cfg.template.data[config.cfg.template.view]);
                                        }
                                    } catch (eeee) {

                                    }
                                    window._editor = edit;
                                }
                            }

                        });
                        // document.addEventListener("submit", function () {
                        //     var value = edit.getValue();
                        //     editor.value = value;
                        //     console.log(value);
                        // });
                        // CodeMirror.runMode(editor.value, "mustache", function (text, style) {
                        //     console.log('text: ' + text + "-- style: " + style);
                        // });

                    },
                    complete: function () {

                    },
                });
                a.data = function () {
                    if (edit) {
                        var value = edit.getValue();
                        this.form.find("#editor").val(value);
                    }
                    return this.form.zoe_inputs("get");
                };
            }
        });

        if (self.hasClass('box-pluign')) {
            // layout.formSettingWidget(self.attr('data-id'),function(data,self){
            //     if(!data.opt.title.push){
            //         if(data.opt.title.length)
            //             self.find(".view >.plugin").html(data.opt.title);
            //
            //         $.post($("#UrlSaveWidget").attr("data-url"),{act:'saveWidget',data:data},function(data){
            //
            //         });
            //     }else{
            //         alertify.error("Đã có lỗi xảy ra");
            //         location.reload();
            //     }
            // });
        } else {
            // layout.formSettingGrid(self.attr('data-id'));
        }
    };
    if (config.cfg.hasOwnProperty('public') && config.cfg.public === "1") {
        $.ajax({
            type: 'POST',
            url: $("#layout").attr('uricom'),
            data: {
                widget: config,
                type: "get",
                id: config.cfg.id
            },
            success: function (data) {
                try {
                    self.children('.option').find('.value textarea').html(data);
                    data = _parseJSON(data);

                    success(data);
                } catch (e) {
                    success(config);
                }
            }
        });

    } else {
        success(config);
    }
}

demo.delegate(".configuration", "click", function (e) {
    e.preventDefault();
    var self = $(this).closest('.tool').parent();
    configuration(self);
});
demo.delegate(".status", "click", function (e) {
    var classOn = 'fa-check-square';
    var classOff = 'fa-square';
    e.preventDefault();
    var self = $(this).closest('.tool').parent();
    var i = $(this).find('i');
    var status = !i.hasClass(classOn);
    // layout.updateStatus(self.attr('data-id'),status,function(data,self){
    //     if(!data.opt.title.push){
    //         $.post($("#UrlSaveWidget").attr("data-url"),{act:'saveWidget',data:data},function(data){
    //             if(status){
    //                 i.removeClass(classOff).addClass(classOn);
    //
    //             }else{
    //                 i.removeClass(classOn).addClass(classOff);
    //             }
    //             alertify.success("Thành công ");
    //         });
    //     }else{
    //         alertify.error("Đã có lỗi xảy ra");
    //     }
    // });
});

demo.delegate(".remove", "click", function (e) {
    var self = this;
    bootpopup.confirm(
        "Do you confirm this message?",
        "Confirm this message",
        function (ans) {
            if (ans) {
                var _self = $(self).closest('.tool').parent();
                var success = function (data) {
                    var classOn = 'fa-check-square';
                    var classOff = 'fa-square';
                    e.preventDefault();
                    var parent = $(this).parent().parent().parent();
                    $(self).parent().parent().remove();
                };
                var option = _self.children('.option').find('.value textarea');
                var _conf = option.val();
                var config = _parseJSON(_conf);
                if (config.cfg.hasOwnProperty('public') && config.cfg.public === "1") {
                    $.ajax({
                        type: 'POST',
                        url: $("#layout").attr('uricom'),
                        data: {
                            widget: config,
                            id: $("#formInfo #id").val(),
                            type: "remove"
                        },
                        success: success
                    });
                } else {
                    success({});
                }
            }
        }
    );
});
var DataLayout = [];

function getOption(parent) {
    return _parseJSON(parent.children('.option').find('.value textarea').html());
}

function setOption(parent, Object) {
    parent.children('.option').find('.value textarea').html(JSON.stringify(Object));
}

var saveLayoutJson = function (element) {
    var data,
        widget = {},
        depth = 0;
    var step = function (level, depth, trcms) {
        var array = [],
            items = typeof trcms == 'undefined' ? level.children('.grid') : level;
        $.each(items, function () {
            var trcms = $(this),
                item = {};

            var option = $.extend({}, getOption(trcms));
            option.cfg.id = trcms.attr('data-id');

            item.row = {};
            item.row.option = option;

            var div_columns = trcms.children('.view').children('.row').children('.column');
            var _option_column = [];
            var place = [];

            div_columns.each(function () {
                var _option_plugins = [];
                if ($(this).attr('place')) {
                    place.push($(this).attr('place'));
                } else {
                    place.push(id());
                }
                var div_columns_plugin = $(this).children('div');
                div_columns_plugin.each(function () {

                    if ($(this).hasClass('grid')) {
                        console.log($(this).attr('data-id'));
                        var arr = [];
                        arr.push($(this));
                        var _option_plugin = step(arr, depth + 1, 1);
                        // _option_plugin.cfg.id = $(this).attr('data-id');
                        _option_plugins.push(_option_plugin);
                    } else {
                        var _option_plugin = $.extend({}, _parseJSON($(this).children('.option').find('.value textarea').html()));
                        _option_plugin.cfg.id = $(this).attr('data-id');
                        widget[_option_plugin.cfg.id] = _option_plugin;
                        _option_plugins.push(_option_plugin.cfg.id);//widget.length - 1
                    }
                });

                _option_column.push(_option_plugins);

            });
            if (_option_column.length) {
                item.row.view = _option_column;
            }
            item.row.option.stg.place = place;
            array.push(item);
        });
        return array;
    }

    data = step($(element), depth);
    return {data: data, widget: widget};
}

function initSnippet(html) {
    let snippet = document.querySelector('#snippet pre code');
    if (html) {
        snippet.innerHTML = html;
    }
    hljs.highlightBlock(snippet);
    $('.source').loading({destroy: true});
}

function SaveLayout(self) {
    console.log('0');
    DataLayout = [];
    var grids = $("#layout_demo>.grid");
    console.log('1');
    var layout = saveLayoutJson("#layout_demo");
    console.log('2');
    console.log((layout));
    $('#layout_demo').loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
    $.ajax({
        type: 'POST',
        url: $(self).attr('url'),
        data: {
            layout: JSON.stringify(layout),
            info: $("#formInfo").zoe_inputs("get")
        },
        success: function (data) {
            var json = JSON.parse(data);
            if (json.hasOwnProperty('id')) {
                $("#id").val(json.id);
            }
            $('#layout_demo').loading({destroy: true});
            $('.source').loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
            initSnippet(json.content);
        },
        error: function (data) {
            console.log(data.responseJSON);
            var content = [];
            for (var v in data.responseJSON) {
                content.push("<div style='word-break: break-all;'><strong>" + v + "</strong> : " + data.responseJSON[v] + "</div>");
            }
            bootpopup({
                title: "Error",
                content: content,
                ok: function (data, array, event) {

                },
            });
            $('#layout_demo').loading({destroy: true});
        }
    });
}

$(document).ready(function () {


    $('.source').loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});

    setTimeout(function () {
        initSnippet();
    }, 1000);

    // $("#saveLayout").click(function () {
    //     console.log('0');
    //     DataLayout = [];
    //     var grids = $("#layout_demo>.grid");
    //     console.log('1');
    //     var layout = saveLayout("#layout_demo");
    //     console.log('2');
    //     console.log((layout));
    //     $('#layout').loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
    //     $.ajax({
    //         type: 'POST',
    //         url: $(this).attr('url'),
    //         data: {
    //             layout: JSON.stringify(layout),
    //             info: $("#formInfo").zoe_inputs("get")
    //         },
    //         success: function (data) {
    //             var json = JSON.parse(data);
    //             if (json.hasOwnProperty('id')) {
    //                 $("#id").val(json.id);
    //             }
    //             $('#layout').loading({destroy: true});
    //         },
    //         error:function (err) {
    //             console.log(err);
    //             $('#layout').loading({destroy: true});
    //         }
    //     });
    // });
});

