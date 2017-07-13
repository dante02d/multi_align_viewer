class Msa_statistics
{
	constructor(div_b,data_Obj)
	{
		this.div_base = div_b;
		this.div_base.innerHTML = "";
		this.dataObj = data_Obj;
		this.statMod = new MSA_Analitycs(data_Obj);
		this.statMod.calculate_Statistics();
		this.print_statistics();
	}

	print_statistics ()
	{
		var strData = "";
		strData+= "<p> Total Sequence Conserved Residues = "+this.statMod.totSeqRes+ " </p>";
		strData+= "<p> Total Structure Conserved Residues = "+this.statMod.totStrRes+" </p>";
		strData+= "<p> Total Interface Structure Conserved Residues = "+this.statMod.totIntRes+"("+(this.statMod.totIntRes/this.statMod.totStrRes)*100+"%) </p>";
		strData+= "<p> Total Core Structure Conserved Residues = "+this.statMod.totCorRes+"("+(this.statMod.totCorRes/this.statMod.totStrRes)*100+"%) </p>";
		strData+= "<p> Total Surface Structure Conserved Residues = "+this.statMod.totSurRes+"("+(this.statMod.totSurRes/this.statMod.totStrRes)*100+"%) </p>";
		strData+= "<p> Total Hotspots (Sequence and Structurally conserved) = "+this.statMod.totHotspots+" </p>";
		strData+= "<p> Detail Hotspots I= "+this.statMod.totKHotspots['I']+" C= "+this.statMod.totKHotspots['C']+" S= "+this.statMod.totKHotspots['S']+"</p>";
		strData+= this.print_statistics_Level2();
		this.div_base.innerHTML=strData;
	}

	print_statistics_Level2()
	{
		var strResult = "";
		for(var i=0;i<this.statMod.totKstrRes.length;i++)
		{
			strResult+="<p> VDB:"+this.statMod.totKstrRes[i]['VDB']+ "<br>";
			for(var key in this.statMod.totKstrRes[i]['Resume'])
			{
				strResult+= key+": "+this.statMod.totKstrRes[i]['Resume'][key]+ " ";
			}
			strResult+="</p>";
		}
		return strResult;
	}
}