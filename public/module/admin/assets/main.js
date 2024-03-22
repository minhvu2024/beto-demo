$('#check-all').on('ifChecked', function (event) {
    $('input[name="post\[\]"]').iCheck('check');
    // $('#bulk-action-container').show();
});

$('#check-all').on('ifUnchecked', function (event) {
    $('input[name="post\[\]"]').iCheck('uncheck');
    //  $('#bulk-action-container').hide();
});

function notice() {

}

function ajax(url, success, data, type = "POST") {
    $.ajax({
        url: url,
        type: type,
        data: data,
        success: success,
        error: function (xhr, error) {
            if (xhr.status === 401) {
                location.reload();
            }
        }
    });
}

$.notify.addStyle('htmlContent', {
    html: "<div><div data-notify-html/></div>",
    classes: {
        base: {
            "white-space": "nowrap",
            "background-color": "lightblue",
            "padding": "5px"
        },
        superblue: {
            "color": "white",
            "background-color": "blue"
        },
        error: {
            'color': "#B94A48",
            'background-color': "#F2DEDE",
            'border-color': '#EED3D7',
        }
    }
});
var _parseJSON = function (json) {
    try {
        var data = $.parseJSON(json);
        return data;
    } catch (e) {
        return {};
    }
}