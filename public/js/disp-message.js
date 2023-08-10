$(function () {
    //表示件数の変更
    $('select[name=disp_num]').change(function () {
        $('#form').submit();
    });

    //メッセージ表示
    if (success) {
        $(function () {
            Swal.fire(success, message, 'success');
        });
    }
    if (errorMes) {
        $(function () {
            Swal.fire(errorMes, message, 'error');
        });
    }
})