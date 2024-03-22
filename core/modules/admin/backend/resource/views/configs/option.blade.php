<table class="table table-bordered">
    <tbody>
        @if(isset($configs['config']["columns"]['lists']))
        <tr class="text-center">
            <td width="150">
                <label for="text">Columns</label>
            </td>
            <td>
                @foreach($configs['config']["columns"]['lists'] as $val=>$columns)
                    <input type="checkbox" name="data.columns[{!! $val !!}]" value="{!! $val !!}"> {!! isset($columns['label'])?$columns['label']:"Empty" !!}
                @endforeach
            </td>
        </tr>
        @endif
        @if(isset($configs["config"]['pagination']['item']))
            <tr class="text-center">
                <td width="150">
                    <label for="text">Pagination</label>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr class="text-center">
                            <td>
                                <label for="text">Item</label>
                            </td>
                            <td>
                                <input type="text" name="data.pagination.item" class="form-control" >
                            </td>
                        </tr>
                     </table>
                </td>
            </tr>
        @endif
        @if(isset($configs['options']))
            @foreach($configs['options'] as $key=>$view)
                @includeIf($view)
            @endforeach
        @endif
    </tbody>
</table>
