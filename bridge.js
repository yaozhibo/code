$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }
    });
   //Login
    $('body').on('click', 'button.login-submit',function() {
        ajaxData = new FormData();

        ajaxData.append('email',$('input[name = email]').val());
        ajaxData.append('password',$('input[name = password]').val());

        ajaxType = "POST";
        ajaxUrl = scopeUrl.loginPost;
        ajaxDataType = "";
        execAjax(ajaxData, ajaxType, ajaxUrl, ajaxDataType);
    });

    $('body').on('click', 'button.register-submit', function() {

        ajaxData = new FormData();


        ajaxData.append('name',$('input[name = username]').val());
        ajaxData.append('email',$('input[name = email]').val());
        ajaxData.append('password',$('input[name = password]').val());
        ajaxData.append('password_confirmation',$('input[name = password_confirmation]').val());

        ajaxType = 'POST';
        ajaxUrl = scopeUrl.registerPost;
            ajaxDataType = "";
        execAjax(ajaxData, ajaxType, ajaxUrl, ajaxDataType);
    });

    function execAjax(ajaxData, ajaxType, ajaxUrl, ajaxDataType, successResponseStr)
    {
        $.ajax({
            data: ajaxData,
            type: ajaxType,
            url: ajaxUrl,
            dataType: ajaxDataType,
            processData: false,     //告诉jQuery不要去处理发送的数据
            contentType: false,     //告诉jQuery不要去设置Content-Type请求头
            cache:false,        //告诉jQuery不要调用缓存的ajax结果
            success: function(data) {
                toastr.success(data.status + ":" + successResponseStr);
                /*if(data.status != 10000) {
                    console.log(data.status);
                    toastr.error( "错误 " + ': ' + data.info);
                } else {
                    toastr.success(data.status + ":" + successResponseStr);
                }*/
            },
             error: function (data) {
                 var errors = data.responseJSON;
                 var errorsHtml= '';
                 $.each( errors.errors, function( key, value ) {
                     errorsHtml += '<li>' + value + '</li>';
                 });
                 toastr.error( errorsHtml , "错误 " + data.status +': ');
             }
        });
    }
});

/*
        //传输对象
        ajaxData = {
            name: $('input[name = username]').val(),
            email: $('input[name = email]').val(),
            password: $('input[name = password]').val(),
            password_confirmation: $('input[name = password_confirmation]').val(),
            _token: $('input[name = _token]').val()
        };
        //传输数组
        ajaxData.append('name',$('input[name = username]').val());
        ajaxData.append('email',$('input[name = email]').val());
        ajaxData.append('password',$('input[name = password]').val());
        ajaxData.append('password_confirmation',$('input[name = password_confirmation]').val());

        laravel自带的注册验证接收的是数组，不要传对象
        */

/*
可能遇到的问题：
1.new FormData()创建的对象在console.log是不会显示的
 */