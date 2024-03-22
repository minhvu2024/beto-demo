<div class="table-responsive" id="Data-Image-Config">
    <table class="table table-bordered">
        <tr class="wrap-image_base64"
            style1="{{isset($items["cfg"]['render']) && $items["cfg"]['render']=="php" ?"display:table-row;":"display:none;"}}">
            <td class="text-center"><label for="text">Image base64</label></td>
            <td>
                <input type="checkbox" name="cfg.config.image.base64" value="1">
            </td>
        </tr>
        <tr>
            <td class="text-center"><label for="text">Resize Image</label></td>
            <td>
                <input type="checkbox" name="cfg.config.image.resize" value="1">
            </td>
        </tr>
        <tr>
            <td class="text-center"><label for="text">Type</label></td>
            <td>
                <input type="checkbox" name="cfg.config.image.lazy" value="lazy"> Load
            </td>
        </tr>
        <tr>
            <td class="text-center"><label for="text">Lazy Load</label></td>
            <td>
                <input data-target=".wrap-dynamic" type="radio" name="cfg.config.image.action" value="lazy"> lazy
                <input data-target=".wrap-dynamic" type="radio" name="cfg.config.image.action" value="php"> php
                <input data-target=".wrap-dynamic" type="radio" name="cfg.config.image.action" value="src"> src
            </td>
        </tr>
        <tr>
            <td class="text-center"><strong>Resize</strong></td>
            <td>
                <table class="table">
                    <tr>
                        <td style="width: 20%;vertical-align: center;"><strong>Screen</strong></td>
                        <td>

                            <input type="checkbox" name="cfg.config.image.platforms" value="mobile"> Mobile
                            <input type="checkbox" name="cfg.config.image.platforms" value="tablet"> Tablet

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%;vertical-align: center;"><strong>Resize Pc</strong></td>
                        <td><input class="form-control" type="text" name="cfg.config.image.pc" value=""></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;vertical-align: center;"><strong>Resize Mobile</strong></td>
                        <td><input class="form-control" type="text" name="cfg.config.image.mobile" value=""></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;vertical-align: center;"><strong>Resize Tablet</strong></td>
                        <td><input class="form-control" type="text" name="cfg.config.image.tablet" value=""></td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</div>