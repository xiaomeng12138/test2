<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/bootstrap.min.css">
</head>
<body>
        <center><h2 class="lead">登录</h2></center>
        <center><img src="{{$qrcode}}" width="200px"></center>
        <center><p style="color:red;">
            @if(!empty($errors->first()))
                {{$errors->first()}}
            @endif

        </p></center>
<form class="form-horizontal" action="{{url('login_do')}}" method="post" role="form" enctype="multipart/form-data">

    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">用户名称:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="user_name" id="firstname" placeholder="请输入名称">
        </div>
    </div>

    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">密码:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" id="firstname" placeholder="请输入密码">
        </div>
    </div>



    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default btn-success">登录</button>
        </div>
    </div>
</form>
</body>
</html>