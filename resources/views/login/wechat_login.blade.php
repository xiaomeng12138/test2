<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<center><h2 class="lead">请扫描二维码进行微信登录</h2></center>
        <center><img src="{{$qrcode}}" width="200px"></center>
</body>
</html>
<script src="/jquery.min.js"></script>
<script>
	var code="{{$code}}";
	var t=setInterval("check()",2000);

	function check(){
		$.ajax({
			url:"{{url('/check_login')}}",
			data:{code:code},
			dataType:'json',
			success:function(res){
				if(res.code==1){
					clearInterval(t);
					alert(res.msg);
				}
			}
		});
	}
</script>