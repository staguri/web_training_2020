$('#create').submit(function() {
    var username = $("input[name='username']").val();
    console.log(username);
    var passwd = $("input[name='passwd']").val();
    var passwd1 = $("input[name='passwd1']").val();
    if(passwd === '' || passwd1 === '' || username ==='') {
        alert('ユーザ名またはパスワードが未入力です');
        return false;
    }
    if(passwd != passwd1) {
        alert('パスワードが一致しませんでした。');
        return false;
    }
});
