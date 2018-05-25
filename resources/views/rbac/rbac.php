<html xmlns="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>秘银管理界面 </title>

    <link href="/css/style.css" rel="stylesheet" type="text/css" media="all" />

    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(c) {
            $('.alert-close').on('click', function(c){
                $('.message').fadeOut('slow', function(c){
                    $('.message').remove();
                });
            });
        });
    </script>

</head>
<body style="background: cornflowerblue">

<div class="message warning">

    <div class="inset">
        <div class="login-head"  style="background: cornflowerblue">
            <h1>用户登录</h1>
            <div class="alert-close"></div>
        </div>

        <form method="post" action="http://dev.api.miyin.com/Rbac/login">
            <ul>
                <li><input type="text" class="text" name="name"  >
                    <a href="#" class=" icon user"></a></li>
                   
                <li><input type="password" value="" name="pwd" />
                    <a href="#" class="icon lock"></a></li>
            </ul>

            <div class="submit">
                <input type="submit" value="登录"  style="background: cornflowerblue">
                <h4><a href="http://dev.api.miyin.com/Rbac/regist" >去注册~~</a></h4>
                <div class="clear">  </div>
            </div>
        </form>
    </div>
</div>

<!--- footer --->
<div class="footer">
    <p>Copyright &copy; 2018.</p>
</div>

</body>
</html>