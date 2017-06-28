<?php  ?>

<html>
	<head>
		<title>Sequence Viewer</title>
		<link rel="stylesheet" type="text/css" href="./align_viewer_core.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="./multi_align_builder.js"></script>
		<script src="./viewer_data_source.js"></script>
		<script type="text/javascript"> 
			function onload_event()
			{
				//alert("Do something");
				var objDataSource = new Viewer_Data_Source();
				objDataSource.load_Data(1);
				

			}
		</script>
	</head>
	<body onload="onload_event()">
		<h1>Sequence Viewer</h1>
		<div id="divBase"></div>
	</body>
</html>