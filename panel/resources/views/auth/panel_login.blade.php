<!DOCTYPE html>
<html>	
<head>
	<title>FrozenPanel-Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<meta name="keywords" content="FrozenPanel,Login,FrozenGo" />
	<link href="{{ URL::asset('css/style.css') }}" rel='stylesheet' type='text/css' />
	<!--
    <link href='http://fonts.useso.com/css?family=PT+Sans:400,700,400italic,700italic|Oswald:400,300,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.useso.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
    -->
	<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.js"></script>
	<script src="//cdn.bootcss.com/semantic-ui/2.2.11/semantic.min.js"></script>
</head>
<body>
<script>
    $(document).ready(function(c) {
        $('.close').on('click', function(c){
            $('.login-form').fadeOut('slow', function(c){
                $('.login-form').remove();
            });
        });
    });
</script>

<h1>FrozenPanel</h1>
<div class="login-form">
    <div class="close"> </div>
    <div class="clear"> </div>
    <div class="avtar">
        <img src='{{ URL::asset("images/avtar.png") }}' />
    </div>
    <form class="login-sub">
        <input type="text" placeholder="您的FrozenPanel用户名" name="username">
        <div class="key">
            <input type="password" placeholder="请输入密码" name="password">
        </div>
        <input type="hidden" name="pushroad" value="panel">
    <div class="signin">
        <input type="submit" value="Login" class="signin_btn">
    </div>
    </form>
</div>
<div class="copy-rights">
    <p>Copyright &copy; 2017.FrozenPanel</p>
</div>
<script>
    $(function(){
        $.fn.api.settings.api={
            'signin':'{{ url('/auth/login') }}'
        };
        $(".login-sub").api({
            action: 'signin',
            serializeForm: true,
            method:'POST',
            beforeXHR: function(xhr){
                xhr.setRequestHeader('X-CSRF-TOKEN',$('meta[name="csrf-token"]').attr('content'));
                $(".signin_btn").val("正在登陆...");
                return xhr;
            },
            onSuccess: function(response){
                if(response.success==true){
                    $(".signin_btn").val("登陆成功！");
                    window.location = '{{ url('/panel/index') }}';
                }else{
                    $(".signin_btn").val(response.msg);
                }
            },
            onFailure: function(response){
                $(".signin_btn").val(response.msg);
            },
            onError: function(response){
                $(".signin_btn").val("连接错误");
            }
        })
    });
</script>
</body>
</html>