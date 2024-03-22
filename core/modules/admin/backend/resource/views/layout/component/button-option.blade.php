@php $option = app()->getConfig()->options; @endphp
@if(isset($config['name']) && isset($option[$config['name']]))
@php
    $data = $option[$config['name']];
    $rs = \Illuminate\Support\Facades\DB::table('config')->where(['type'=>'option','name'=>$config['name']])->first();
    $_data = config_get('option',$config['name']);
    $data['data'] = isset($data['data'])?array_merge($data['data'],$_data):$_data;
@endphp
&nbsp;<a href="javascript:void(0);"
   class="btn btn-default btn-md btnOption" data-id="layout"><i class="fa fa-fw fa-cogs"></i> {{ $label }}
</a>&nbsp;
@push('extra-content')
    <div class="modal fade" id="myModalOption" role="dialog">
        <form action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{$header}}</h4>
                        <div style="display: none"><textarea class="value">@json($data)</textarea></div>
                    </div>
                    <div class="modal-body">
                        <p>Loadding ...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{z_language('Close')}}</button>
                        <button type="button"  class="btnSaveOption btn btn-primary">{{z_language('Save')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endpush
@push("scripts")
    <script>
        (function () {
            var myModalOption = $("#myModalOption");
            var FormModal  = myModalOption.find("form");
            var dataOption = {!! json_encode($data) !!};
            console.log(dataOption);
            $('.btnSaveOption').click(function () {
                myModalOption.modal('toggle');
                var data = FormModal.zoe_inputs('get');
                $.ajax({
                    url: '{{route('backend:dashboard:option')}}',
                    type: "POST",
                    data: {act:"save",name:'{{$config['name']}}',data:data},
                    success: function (html) {
                       if(data.hasOwnProperty('data')){
                           dataOption.data= data.data;
                       }
                    }
                });
            });    
            $(".btnOption").click(function () {
                myModalOption.modal();
                $.ajax({
                    url: '{{route('backend:dashboard:option')}}',
                    type: "POST",
                    data: {act:"get",name:'{{$config['name']}}',configs:dataOption},
                    success: function (html) {
                        myModalOption.find('.modal-body').html(html);
                        myModalOption.find("form").zoe_inputs('set',dataOption);
                    }
                });
            });
        })();
    </script>
@endpush
@endif
