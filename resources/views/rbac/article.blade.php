<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>ueditor demo</title>
    @include('vendor.ueditor.assets')
</head>

<body>
    <!-- 加载编辑器的容器 -->
    <script id="container" name="content" type="text/plain">
        这里写你的初始化内容
    </script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
			ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
		});
    </script>
</body>

</html>
