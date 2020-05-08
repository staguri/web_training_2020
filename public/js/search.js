$(function () {
    id = "";
    updateMemo = "";
    $('input[name="update"]').on('click', function () {
        var memo = $(this).data('memo');
        id = $(this).data('id');
        $('textarea[name="update_memo"]').val(memo);
        $('.js-modal').fadeIn();
        updateMemo = memo;
    });

    $('textarea[name="update_memo"]').on('input', function (event) {
        updateMemo = $(this).val();
    });

    $('input[name="confirm"]').on('click', function () {
        if ($('p').hasClass('error')) {
            $('.error').remove();
        }
        var self = $(this);
        var cancelButton = $('input[name="cancel"]');
        self.val('更新中').prop('disabled', true);
        cancelButton.remove();
        param = {
            'memo': updateMemo,
            'id': id,
        }
        $.ajax({
            url: '/ajax/memo', //アクセスするURL
            type: 'get',　　 //post or get
            cache: false,        //cacheを使うか使わないかを設定
            dataType: 'json',     //data type script・xmlDocument・jsonなど
            data: param,           //アクセスするときに必要なデータを記載
        })
         .done(function (response) {
             if (response['message'] == 'ok') {
                 $('.js-modal').fadeOut();
                 location.reload();
             } else if (response['message'] == 'error') {
                 $('<p class="error">更新に失敗しました</p>').prependTo(self.parent());
                 self.val('更新する').prop('disabled', false);
                 $('<input type="button" name="cancel" class="js-modal-close" href="" value="キャンセル">')
                     .appendTo(self.parent());
                 $('.js-modal-close').on('click', function () {
                     $('.js-modal').fadeOut();
                     return false;
                 });
             }
         });
    });

    $('.js-modal-close').on('click', function () {
        $('.js-modal').fadeOut();
        return false;
    });

    $('#invalid').on('click', function () {
        return false;
    });
});
