class Viewer_Data_Source
{

	load_Data(id_Align)
	{
		$.ajax({
			type:"post",
			url:"./Align_Data_Source/getAlignData.php?idAlign="+id_Align,
			success: function (data)
			{
				
				var dataObj =JSON.parse(data) ;
				
				var mainDiv = document.getElementById('divBase');
				var objControler = new Multi_align_builder(mainDiv,dataObj);

				//console.log(dataObj)
			}
		});
	}
}