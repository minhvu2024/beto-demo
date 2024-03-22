<table class="table table-bordered">
    <tbody>
    @isset($data["count"])
        @for($i=0;$i<$data['count'];$i++)
            <tr>
                <td>{{$i}}</td>
                <td><label for="text">Name</label></td>
                <td>
                    <input type="text" name="opt.{{$data["key"]}}[{{$i}}].name" class="form-control" placeholder="Name">
                </td>
                <td><label for="text">Image</label></td>
                <td>
                    <input type="text" name="opt.{{$data["key"]}}[{{$i}}].image" class="form-control"
                           placeholder="Image">
                </td>
                <td><label for="text">Link</label></td>
                <td>
                    <input type="text" name="opt.{{$data["key"]}}[{{$i}}].link" class="form-control"
                           placeholder="Link">
                </td>
            </tr>
        @endfor
    @endisset
    </tbody>
</table>