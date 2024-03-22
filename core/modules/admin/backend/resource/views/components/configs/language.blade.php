<div class="table-responsive" id="langConfig">

    <table class="table table-bordered">
        @if(isset($all["temp"]['count']))
            <tr>
                <td>View</td>
                <td>
                    <select class="form-control cfg-lang-template-view">
                        <optgroup label="File">
                            @foreach($list_views as $k=>$_view)
                                @if(!empty($_view['view']) && $_view['view'] != "dynamic")
                                    <option value="{!! $_view["view"] !!}">{{$_view["label"]}}</option>
                                @endif;
                            @endforeach
                        </optgroup>
                        <optgroup label="Template">
                            @for($i=0;$i<$all["temp"]['count'];$i++)
                                <option value="{{$i}}">Template ({{$i}})</option>
                            @endfor
                        </optgroup>
                    </select>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="2">
                <div class="clearfix">
                    <div class="row1">
                        <div class="col-xs-9">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                @php $langcurent = "vi";
                                $languages = config('zoe.language');

                                @endphp
                                @foreach($languages as $language)
                                    @php
                                        $htmlLang='<table class="table table-bordered"><tbody>';
                                        $lang =$language['lang'];
                                    @endphp
                                    @isset($config['lang'][$lang])

                                        @foreach($config['lang'][$lang] as $key=>$_langs)
                                            @php
                                                $htmlLang.='<tr id="'.$lang.'_'.$key.'">';
                                                $htmlLang.='<td width="50%">'.$_langs['key'].'</td>';
                                                $htmlLang.='<td class="input">';
                                                    $htmlLang.="<input class='form-control' type='hidden' name='lang[" . $lang . "][" . $key . "].key' value='" . $_langs['key'] . "'>";
                                                    $htmlLang.="<input class='form-control' type='text' value='".$_langs['val']."' name='lang[" .$lang. "][".$key."].val'>";
                                                $htmlLang.='</td>';
                                                $htmlLang.='</tr>';
                                            @endphp
                                        @endforeach
                                    @endisset
                                    @php $htmlLang.='</tbody></table>'; @endphp
                                    @if($langcurent == $lang)
                                        <div class="tab-pane active" data-lang="{!! $lang !!}" id="home-{!! $lang !!}">
                                            {!! $htmlLang !!}
                                        </div>
                                    @else
                                        <div class="tab-pane" data-lang="{!! $lang !!}"
                                             id="home-{!! $lang !!}">
                                            {!! $htmlLang !!}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="col-xs-3"> <!-- required for floating -->
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tabs-right sideways">
                                @foreach($languages as $language)
                                    @php  $lang =$language['lang']; @endphp
                                    @if($langcurent == $lang)
                                        <li class="active"><a href="#home-{!! $lang !!}"
                                                              data-toggle="tab">{{$lang}}</a></li>
                                    @else
                                        <li><a href="#home-{!! $lang !!}" data-toggle="tab">{{$lang}}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <style>
                        .tabs-left, .tabs-right {
                            border-bottom: none;
                            padding-top: 2px;
                        }

                        .tabs-left {
                            border-right: 1px solid #ddd;
                        }

                        .tabs-right {
                            border-left: 1px solid #ddd;
                        }

                        .tabs-left > li, .tabs-right > li {
                            float: none;
                            margin-bottom: 2px;
                        }

                        .tabs-left > li {
                            margin-right: -1px;
                        }

                        .tabs-right > li {
                            margin-left: -1px;
                        }

                        .tabs-left > li.active > a,
                        .tabs-left > li.active > a:hover,
                        .tabs-left > li.active > a:focus {
                            border-bottom-color: #ddd;
                            border-right-color: transparent;
                        }

                        .tabs-right > li.active > a,
                        .tabs-right > li.active > a:hover,
                        .tabs-right > li.active > a:focus {
                            border-bottom: 1px solid #ddd;
                            border-left-color: transparent;
                        }

                        .tabs-left > li > a {
                            border-radius: 4px 0 0 4px;
                            margin-right: 0;
                            display: block;
                        }

                        .tabs-right > li > a {
                            border-radius: 0 4px 4px 0;
                            margin-right: 0;
                        }
                    </style>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" onclick="GetLang(this);" id="btn-get-lang">Get Lang</button>
            </td>
        </tr>
    </table>
    <script>
        window.func_lang = function () {
            console.log(1111);
            $("#btn-get-lang").trigger('click');
        };

        function GetLang(self) {
            var form = $(self).closest('form');

            var data = form.zoe_inputs("get");

            var config = JSON.parse(form.find("#data_config").val());

//            if (data.hasOwnProperty('opt')) {
//                config.opt = $.extend(config.opt, data.opt);
//            }
//            if (data.hasOwnProperty('cfg')) {
//                config.cfg = $.extend(config.cfg, data.cfg);
//            }
            config = $.extend(config, data);
            $.ajax({
                type: 'POST',
                url: '{{route('backend:layout:ajax:get_lang')}}',
                data: config,
                success: function (data) {
                    try {
                        var data = JSON.parse(data);
                        console.log(data.langs);
                        if (data.status === 0) {
                            $("#langConfig .tab-content .tab-pane").each(function () {
                                $html = "";
                                var lang = $(this).attr('data-lang');
                                var n = Date.now() + "";
                                console.log("n:" + n);
                                for (var key in data.data) {
                                    var tr = $("table tr#" + lang + "_" + key);
                                    console.log(tr);
                                    if (tr.length === 0) {

                                        $html += '<tr id="' + lang + "_" + key + '" update="' + n + '">';
                                        $html += "<td width='50%'>" + data.data[key].name + " </td>";

                                        if (data.langs.hasOwnProperty(lang) && data.langs[lang].hasOwnProperty(data.data[key].name)) {
                                            $html += '<td class="input"><strong>' + (data.langs[lang][data.data[key].name]) + '</strong></td>';
                                        } else {

                                            $html += "<td class='input'><input class='form-control' type='hidden' name='lang[" + lang + "][" + key + "].key' value='" + data.data[key].name + "'><input class='form-control' type='text' value='' name='lang[" + lang + "][" + key + "].val'></td>";
                                        }
                                        $html += '</tr>';
                                    } else {
                                        tr.attr("update", n);
                                        if (data.langs.hasOwnProperty(lang) && data.langs[lang].hasOwnProperty(data.data[key].name)) {
                                            tr.find('input').html('<strong>' + (data.langs[lang][data.data[key].name]) + '</strong>');
                                        }
                                    }
                                }
                                $(this).find('table tr').each(function () {
//                                    console.log($(this).text() + " " + $(this).attr("update") + " " + n);
                                    if ($(this).attr("update") !== n) {
                                        $(this).remove();
                                    }
                                });
                                $(this).find('table').append($html);


                            });
                        } else {

                        }

                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        }
    </script>
</div>
