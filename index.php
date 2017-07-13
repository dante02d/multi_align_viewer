<?php  ?>

<html>
	<head>
		<title>Sequence Viewer</title>
		<link rel="stylesheet" type="text/css" href="./align_viewer_core.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="./multi_align_builder.js"></script>
		<script src="./viewer_data_source.js"></script>
		<script src="./multi_align_statistics.js"></script>

		<script type="text/javascript"> 
			function onload_event()
			{
				//alert("Do something");

				var url_string = window.location.href;
				var url = new URL(url_string);
				var idAlign = url.searchParams.get("idAlign");

				var objDataSource = new Viewer_Data_Source();
				if(idAlign==null)
				{
					idAlign=1;
					alert ("No aligment selected loading default");
				}

				objDataSource.load_Data(idAlign);
			}
		</script>
	</head>
	<body onload="onload_event()">
		<h1>Sequence Viewer</h1>
		<div id="divBase"></div>
		<div id="statistics"></div>
	</body>
</html>