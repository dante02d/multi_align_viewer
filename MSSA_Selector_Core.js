
function cleanChainSelector()
{
    $('#cmbChain')
                .find('option')
                .remove()
                .end()
               ;
}

function fillChainsSelector(strVDB)
{
    $.ajax({
        type:"post",
        url:"./API2/get_chains.php?VDB="+strVDB,
        success: function (data)
        {
            var dataObj =JSON.parse(data) ;
            //console.log(dataObj[0][0])

            cleanChainSelector();
             
            $.each(dataObj,function(i,item)
            {
                if(item!=null)
                {
                    $("#cmbChain").append(
                        $("<option>",
                        {
                            value:item[0],
                            text:item[0]
                        })
                    );     
                //console.log(item[0]);    
                }
                                                
            });
        }
    });
}

function generateAligment(vdbsAlign)
{
    console.log(vdbsAlign);
    var dataToAlign = {"vdbs":vdbsAlign};
     $.ajax({
        type:"post",
        url:"./MSSA_Executor.php",
        data:dataToAlign,
        success: function (data)
        {
            /*var dataObj =JSON.parse(data) ;
            console.log(dataObj)*/
            var frg=data.split(":");
            if(frg.length > 1)
            {
                console.log(frg);
                window.location.replace("./index.php?idAlign="+frg[1].trim());
            }
            else
                console.log("Error");

        }
    });
}

jQuery(function($) 
	{
    	$( "#vdbSearch" ).autocomplete(
    	{
    		source: "./API2/test_Autocomplete.php",
    		select: function( event, ui ) 
    		{
                // Event for automplete textbox
    			var strVDB = ui.item.value;
                fillChainsSelector(strVDB);
                //console.log(urlGetChains);                
    		}
    	});
    });


function addItem()
{
    //console.log($("#cmbChain").val());
    objPool.additem($("#vdbSearch").val(),$("#cmbChain").val());
}
function addItem_Test()
{    
    objPool.additem("1dwn","B");
    objPool.additem("2ms2","B");
}

function buildAligment()
{
   var strOutput =objPool.getitems();
   $("#dialog-message").html(strOutput);
   $( "#dialog" ).dialog();
   generateAligment(strOutput);

}

class VDBPoolMagnament
{

    constructor()
    {
        this.controlName="#aligment_pool";
        this.VDBItems=[];
    }

    additem(vdb,chain)
    {
        var item={"vdb":vdb,"chain":chain};
        this.VDBItems.push(item);
        this.additemGrahics(item);
    }

    additemGrahics(item)
    {
        var strDiv = "<div>"+ item["vdb"]+ ":"+ item["chain"]+"</div>";
        $(this.controlName).append(strDiv);
    }

    getitems()
    {
        var strOutput="";
        $.each(this.VDBItems,function(i,item){
            if(strOutput.length > 0)
                strOutput+=",";
            strOutput+=item["vdb"]+":"+item["chain"];
            //console.log(item);
        });
        //console.log(strOutput);
        return strOutput
    }
}

var objPool = new VDBPoolMagnament();