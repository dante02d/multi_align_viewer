class Multi_align_builder
{
	constructor(div_b,data_Obj)
	{
		this.sequence_lengt =300;
		this.sequences_number=3;
		this.label_number_star = 1;
		this.label_interval_size = 10;
		this.max_residues_per_row = 50;
		this.div_base = div_b;
		this.div_base.innerHTML = "";
		this._currentNum = this.label_number_star;
		this._invervalCount = 1;
		this.dataObj = data_Obj;
		this.dummyRows = 3; /*Number of rows with no sequence information*/
		this.objAnalitycs = new MSA_Analitycs(data_Obj);
		this.seq_conservedResidues= [];
		this.setupViewer();
		this.buildMainElements();

	}

	setupViewer()
	{
		this.sequences_number = this.dataObj.length;
		this.sequence_lengt = this.dataObj[0].Alignment.length;
	}

	buildMainElements()
	{
		var align_tool = "";
		
		var numOfIntervals = this.sequence_lengt/this.max_residues_per_row
		
		for (var i=0; i< numOfIntervals ; i++)
		{
			this.div_base.innerHTML += this.createInterval(this._currentNum-1,this._currentNum + this.max_residues_per_row) ;
		}

	}

	createInterval(startInterval,endInterval)
	{

		var align_tool = "";
		align_tool += "<div class='rTable'>";
		for (var i=0; i< this.sequences_number+this.dummyRows;i++)
		{
			align_tool += this.addRow();
			for (var j=startInterval; j<endInterval;j++)
			{
				if(j<= this.sequence_lengt)
					align_tool += this.addCell(i,j,startInterval);
			}

			align_tool += this.closeRow();
		} 
		align_tool += "</div>";
		return align_tool;
	}

	addRow()
	{
		return "<div class='rTableRow'>";
	}

	closeRow()
	{
		return "</div>"; 
	}

	addCell(i,j,startInterval)
	{
		if(i==0 && j==startInterval)
			return this.addEmptyCell();
		else if (i>2 && j==startInterval)
			return this.addLabeCell(i,startInterval);
		else if(i==0 && j>0)
			return this.addNumberCell(i,j,startInterval);
		else if(i==1 && j>startInterval)
			return this.addSeqCell(i,j,startInterval);
		else if(i==2 && j>startInterval)
			return this.addStrCell(i,j,startInterval);
		else if(i==1 && j==startInterval)
			return this.addEmptyCell();
		else if(i==2 && j==startInterval)
			return this.addEmptyCell();
		else if(i==3 && j==startInterval)
			return this.addEmptyCell();
		else
			return this.addResidueCell(i,j,startInterval);
	}

	addEmptyCell()
	{
	    return "<div class='rTableCell'> </div>";
	}

	addLabeCell(row,startInterval)
	{
		var seqNum =parseInt(this.dataObj[row-3].SeqStart)+startInterval;
		return "<div class='rTableCell sequenceName' id='seqN"+row+"'> "+this.dataObj[row-3].VDB+"_"+this.dataObj[row-3].Chain+":"+seqNum+" </div>";
	}

	addNumberCell(row,col,startInterval)
	{
		var numContent = "";
		if(this.label_interval_size>0)
		{
			if(col == startInterval+1)
			{
				 numContent = this._currentNum;
				 			 
			}
			if(this._invervalCount == this.label_interval_size)
			{
				numContent = this._currentNum;
				this._invervalCount = 0;
			}

			this._invervalCount++;
			this._currentNum ++;
		}
		else
			numContent = col;
		
		return "<div class='rTableCell'>"+numContent+"</div>";
	}

	addStrCell(row,col,startInterval)
	{
		var numContent = "r";
		numContent = this.objAnalitycs.checkforStrConservation(col-1);
		return "<div class='rTableCell'>"+numContent+"</div>";
	}
	addSeqCell(row,col,startInterval)
	{
		var numContent = "s";
		numContent = this.objAnalitycs.checkforSeqConservation(col-1);
		var aditional_class="";
		if(numContent!='.' && numContent!=' ')
		{
			this.seq_conservedResidues.push([startInterval,col]);
			aditional_class="seqConservRes "
		}
		return "<div class='rTableCell "+aditional_class+"'>"+numContent+"</div>";
	}

	addResidueCell(row, col,startInterval)
	{
		var aditional_class="";
		if(this.isSeqConcerved(startInterval,col))
			aditional_class="seqConservRes "

		if(this.dataObj[row-this.dummyRows].strAnnotation!= undefined && 
				this.dataObj[row-this.dummyRows].strAnnotation.length>0){
			aditional_class+= this.setupStrColorCoding(this.dataObj[row-this.dummyRows].strAnnotation[col-1]);
			//console.log(row+"-"+col+"->"+this.dataObj[row-this.dummyRows].strAnnotation[col-1]);
		}
		else
			console.log(row+"<->"+col+"->"+this.dataObj[row-this.dummyRows].strAnnotation[col-1]);
		return "<div class='rTableCell "+aditional_class+"' id='Res"+row+"_"+col+"'> "+this.dataObj[row-this.dummyRows].Alignment[col-1]+" </div>";
	}

	setupStrColorCoding(strZone)
	{
		if(strZone== 'I')
			return "Interface ";
		else if(strZone== 'C')
			return "Core ";
		else if(strZone== 'S')
			return "SurfaceOut ";
		else if(strZone== 's')
			return "SurfaceIn ";
		return "";
	}

	isSeqConcerved(startInterval,col)
	{
		for (var i=0 ; i< this.seq_conservedResidues.length;i++)
		{
			if(this.seq_conservedResidues[i][1]==col)
			{
				//this.seq_conservedResidues.splice(i,1);
				return true;
			}
			
		}
		return false;
	}
}


class MSA_Analitycs
{

	constructor(_dataObj)
	{
		this.dataObj = _dataObj;
		this.totSeqRes=0;
		this.totStrRes=0;
		this.totIntRes=0;
		this.totCorRes=0;
		this.totSurRes=0;
		this.totHotspots=0;
		this.totKstrRes=[];
		this.totKHotspots=[];

		this.interfaceSimbol="&#8224";
		this.coreSimbol = "&#8225";
		this.surfaceOutSimbol="^";
		this.surfaceInSimbol="v";
		
	}



	checkforSeqConservation(col)
	{
		var dicCounter = {}
		for(var i=0;i<this.dataObj.length; i++)
		{
			dicCounter[this.dataObj[i].Alignment[col]]=dicCounter[this.dataObj[i].Alignment[col]]?dicCounter[this.dataObj[i].Alignment[col]]+1:1;
		}
		if(dicCounter[this.dataObj[0].Alignment[col]] == this.dataObj.length)
			return this.dataObj[0].Alignment[col];
		else
			return ".";
	}

	checkforStrConservation(col)
	{
		var dicCounter = {}
		for(var i=0;i<this.dataObj.length; i++)
		{
			if(this.dataObj[i].strAnnotation!= undefined && 
				this.dataObj[i].strAnnotation.length>0)
			{
				dicCounter[this.dataObj[i].strAnnotation[col]]=dicCounter[this.dataObj[i].strAnnotation[col]]?dicCounter[this.dataObj[i].strAnnotation[col]]+1:1;	
			}
			
		}
		//console.log(col+"->"+JSON.stringify(dicCounter));
		if(dicCounter[this.dataObj[0].strAnnotation[col]] == this.dataObj.length)
			return this.translateSrToSimbols(this.dataObj[0].strAnnotation[col]);
		else
			return ".";
	}

	translateSrToSimbols(strLetter)
	{
		if(strLetter=='I')
			return this.interfaceSimbol;
		else if (strLetter=='C')
			return this.coreSimbol;
		else if (strLetter=='S')
			return this.surfaceOutSimbol;
		else if (strLetter=='s')
			return this.surfaceInSimbol;
		else if (strLetter=='.') 	
			return '.';
		else
		{
			console.log(strLetter);
			return "E";
		}
		
	}

	calculate_Statistics()
	{
		this.calculate_Statistics_level1();
		this.calculate_Statistics_level2();
	}

	calculate_Statistics_level1()
	{
		for(var i=0; i< this.dataObj[0].Alignment.length;i++)
		{
			var seqCons = this.checkforSeqConservation(i);
			if(seqCons!= '.')
			{
				this.totSeqRes++;
			}

			var strCons = this.checkforStrConservation(i);
			if(strCons!= '.')
			{
				this.totStrRes++;
				if(strCons==this.interfaceSimbol)
				{
					this.totIntRes++;
				}
				else if(strCons==this.coreSimbol)
				{
					this.totCorRes++;
				}
				else if(strCons==this.surfaceOutSimbol)
				{
					this.totSurRes++;
				}
				else if(strCons==this.surfaceInSimbol)
				{
					this.totSurRes++;
				}
			}

			if(seqCons!= '.' && strCons!= '.')
			{
				this.totHotspots++;
				if(strCons==this.interfaceSimbol)
				{
					this.totKHotspots['I']=this.totKHotspots['I']?this.totKHotspots['I']+1:1;
				}
				else if(strCons==this.coreSimbol)
				{
					this.totKHotspots['C']=this.totKHotspots['C']?this.totKHotspots['C']+1:1;
				}
				else if(strCons==this.surfaceOutSimbol)
				{
					this.totKHotspots['S']=this.totKHotspots['S']?this.totKHotspots['S']+1:1;
				}
				else if(strCons==this.surfaceInSimbol)
				{
					this.totKHotspots['S']=this.totKHotspots['S']?this.totKHotspots['S']+1:1;
				}
			}
		}
	}

	calculate_Statistics_level2()
	{
		for(var i=0;i<this.dataObj.length; i++)
		{
			var arrStrDet= {'I':0,'C':0,'S':0};
			for(var j=0; j< this.dataObj[i].strAnnotation.length;j++)
			{
				if(this.dataObj[i].strAnnotation[j]=="I")
				{
					arrStrDet['I']++;
				}
				else if (this.dataObj[i].strAnnotation[j]=="C")
				{
					arrStrDet['C']++;
				}
				else if (this.dataObj[i].strAnnotation[j]=="S")
				{
					arrStrDet['S']++;
				}
				else if (this.dataObj[i].strAnnotation[j]=="s")
				{
					arrStrDet['S']++;
				}
			}
			this.totKstrRes= this.totKstrRes.concat({'VDB':this.dataObj[i].VDB,'Resume':arrStrDet});
		}
	}

	
}