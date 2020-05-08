$('#login').on('click',function() {
    var username = $("input[name='username']").val();
    var passwd = $("input[name='passwd']").val();
    var token = $("input[name='token']").val();
    if(username === '' || passwd === '') {
        alert('パスワードまたはユーザー名が未入力です');
        return false;
    }
    var params = {
        'name': username,
        'pass': passwd,
        'token': token,
    };

    $.ajax({
        url: '/ajax/account/login', //アクセスするURL
        type: 'post',　　 //post or get
        cache: false,        //cacheを使うか使わないかを設定
        dataType:'json',     //data type script・xmlDocument・jsonなど
        data:params,           //アクセスするときに必要なデータを記載
    })
     .done(function(response) {
         if(response['message'] =='ok'){
             location.href='/top';
         }else if(response['message'] =='error'){
             alert('ユーザー名、パスワードが一致しませんでした。')
         }else{
             alert(response['message']);
         }
     })
});
