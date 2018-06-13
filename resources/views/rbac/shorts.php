<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>ueditor demo</title>
    @include('vendor.ueditor.assets')
</head>

<body>
      <form  method="post" enctype="multipart/form-data" action="http://api.mithrilgaming.com:8000/Rbac/article_add">
        <table border="">
            <tr>
                <td>标题：</td>
                <td><input type="text" name="title"></td>
            </tr>
            <tr>
                <td>头图：</td>
                <td><input type="file" name="files"></td>
            </tr>
            <tr>
                <td>内容</td>
                <td>
                     <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain">
                        
                    </script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var ue = UE.getEditor('container');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td>出处：</td>
                <td><input type="text" name="source"></td>
            </tr>
            <tr>
                <td>关联的游戏名称：</td>
                <td><input type="text" name="game_name"></td>
            </tr>
            <tr>
                <td>作者：</td>
                <td><input type="text" name="article_author"></td>
            </tr>
            <tr>
                <td>文章类型</td>
                <td>
                   <input type="text" name="type" value="">
<!--                     <select name="slideshow_type" id="level">
                    <option value="1" >&nbsp;&nbsp;游民星空</option>
                    <option value="2" >&nbsp;&nbsp;文章内插图</option>
                    <option value="3" >&nbsp;&nbsp;商城图</option>
                    </select> -->
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="提交"></td>
                <td><input type="reset" value="重置"></td>
            </tr>
        </table>
        <br>
       
         
        
        <!-- 出处：<input type="text" name="author"> -->
        
    </form>

</body>

</html>
