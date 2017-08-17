<html>
<head>
	<title>
		MSSA Interface
	</title>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


	<script type="text/javascript" src="./MSSA_Selector_Core.js"></script>
</head>
<body>
	<h1>Multiple Structure Sequence Aligment Selector </h1>
	<form method="post">
	<div>
		Please select a VDB entry: 
		<span id="searchPanel">
  			<div >
	  			<label for="vbdSearch">VDB:</label> 
	  			<input  id="vdbSearch" size="6" > 
	  			<!-- <input type="button" id="searchButton" class="searchButton" value="search" onclick="searchForVDB(this)" /> -->
  			</div>
  		</span> 
		<!--<input type="text" name="txtVDB">-->
		<br>
		select a chain: <select id="cmbChain" name="cmbChain"></select>
		<br>
		<input type="button" name="btadd" value="Add" onclick="addItem()">
		<input type="button" name="bttest" value="Test button" onclick="addItem_Test()">
	</div>
	<div id="aligment_pool">
	</div>
	<input type="button" name="btGenerate" value="Generate Aliment" onclick="buildAligment()">
	</form>
	
	<div id="dialog" title="Generating Aligment">
  		<p id="dialog-message"></p>
	</div>
</body>
</html>