<!DOCTYPE html>
<html>
<head>
	<title>查询</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="/js/jquery.js"></script>
	<style type="text/css">
		.item{
			padding: 10px;
			border: 1px #333 solid;

		}
	</style>
	<script type="text/javascript">

		$(function(){
			$("input[name='search_input']").keyup(function(event){
				
				var keywords = $(this).val();

				$.ajax({
					url: "/CheckCode/search",
					type: 'POST',
					dataType: "json",
					data: {"keyword": keywords},
					success: function(res){
						$(".result").html("");

						for(k in res.data){
							$(".result").append("<div class='item'>" + res.data[k] + "</div>")
						}
					}
				})
				
			})
		})
	</script>
</head>
<body>
<input type="text" name="search_input"/>
<div class="result">
	
</div>
</body>
</html>