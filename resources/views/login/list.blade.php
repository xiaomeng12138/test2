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
        <a href="{{url('/quit')}}" class="btn btn-danger">退出</a>
        用户名:<font color="#1e90ff">{{$info['user_name']}}</font>

</body>
</html>