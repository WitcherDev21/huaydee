var modalConfirm = function (callback) {
    $(".abort_poynumber").on("click", function () {
        $(".modal_delete_number").modal('show');
    });
    $(".btnconfirmdeletenumber").on("click", function () {
        callback(true);
        $(".modal_delete_number").modal('hide');
    });
};

modalConfirm(function (confirm) {
    if (confirm) {
        abort_poy();
        //console.info(s);
    }
});


function abort_poy() {
        //show('loading', true);
        $.ajax({
            url: poyCancelUrl,
            cache: false,
            type: 'post',
            data: {
                poy_id: poy,
                _csrf: yii.getCsrfToken()
            },
            success: function (data) {
                //show('loading', false);
                window.location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //show('loading', false);
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

};