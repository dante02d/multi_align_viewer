<?php  ?>

<html>
	<head>
		<title>Sequence Viewer</title>
		<link rel="stylesheet" type="text/css" href="./align_viewer_core.css">
		<script src="./multi_align_builder.js"></script>
		<script type="text/javascript"> 
			function onload_event()
			{
				//alert("Do something");
				var mainDiv = document.getElementById('divBase')
				var objControler = new Multi_align_builder(mainDiv);
			}
		</script>
	</head>
	<body onload="onload_event()">
		<h1>Sequence Viewer</h1>
		<div id="divBase"></div>
	</body>
</html>