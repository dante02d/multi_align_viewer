class Multi_align_builder
{
	constructor(div_b)
	{
		this.sequence_lengt =300;
		this.sequences_number=3;
		this.label_number_star = 1;
		this.label_interval_size = 10;
		this.max_residues_per_row = 50;
		this.div_base = div_b;
		this._currentNum = this.label_number_star;
		this._invervalCount = 1;
		this.DataObj = 0;
		this.buildMainElements();

	}

	buildMainElements()
	{
		var align_tool = "";
		
		var numOfIntervals = this.sequence_lengt/this.max_residues_per_row
		for (var i=0; i< numOfIntervals ; i++)
		{
			this.div_base.innerHTML += this.createInterval(this._currentNum-1,this._currentNum + this.max_residues_per_row) ;
		}

		//this.div_base.innerHTML = this.createInterval(0,this.sequence_lengt+1) ;
		//this.div_base.innerHTML += this.createInterval(0,100) ;
		//this.div_base.innerHTML += this.createInterval(101,200) ;
	}

	createInterval(startInterval,endInterval)
	{

		var align_tool = "";
		align_tool += "<div class='rTable'>";
		for (var i=0; i< this.sequences_number+1;i++)
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
		else if (i>0 && j==startInterval)
			return this.addLabeCell(i);
		else if(i==0 && j>0)
			return this.addNumberCell(i,j,startInterval);
		else
			return this.addResidueCell(i,j);
	}

	addEmptyCell()
	{
	    return "<div class='rTableCell'> </div>";
	}

	addLabeCell(row)
	{
		return "<div class='rTableCell' id='seqN"+row+"'> Xxx </div>";
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
	addResidueCell(row, col)
	{
		return "<div class='rTableCell' id='Res"+row+"_"+col+"'> R </div>";
	}
}