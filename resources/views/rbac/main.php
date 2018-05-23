<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
     <?php
                      if(empty(session('name'))){
                         echo "<script>alert('请先登录');window.location.href = 'index';</script>";
                      }
     ?>
     
hello world


</body>
</html>