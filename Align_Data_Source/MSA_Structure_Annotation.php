<?php 
include "../API2/VDB_Structural_Regions.php";
//include "./DataConexion.php";
	class MSA_Structure_Annotation
	{
		private $sequenceData;
		private $alignADetails;
		
		public function __construct($_sequenceData,$_alignADetails)
		{
			$this->sequenceData = $_sequenceData;
			$this->alignADetails = $_alignADetails;
		}

		public function add_Structural_Information()
		{
			$strRegions = new VDB_StructuralRegions(False);
			
			foreach ($this->sequenceData as $data )
			{	
				try
				{
					//print "<p>".$data->VDB. "</p><hr>";
					
					$strRegions->objData->VDB = $data->VDB;
					$strRegions->subUnit= $data->Chain;
					$strRegions->setup_Object_Params();
					$dataStr=$strRegions->get_Structural_Regions(False);
					
					$strAnnotation= $this->merge_sequence_strInf($data,$dataStr,$this->find_Advanced_Details_Data($data->VDB,$data->Chain));
					$data->strAnnotation = $strAnnotation;
					//var_dump($dataStr);
				}
				catch(Exception $e)
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				
			}
		}

		function find_Advanced_Details_Data($vdb,$chain)
		{			
			for($i=0;$i<count($this->alignADetails);$i++)
			{
				$fr=$this->find_first_residue($i);
				if($this->alignADetails[$i][0]->Label==$vdb && $this->alignADetails[$i][$fr]->Chain==$chain)
					return $this->alignADetails[$i];
				/*else
				{
					//var_dump($this->alignADetails[$i]);
					print "<p>".$this->alignADetails[$i][0]->Label."<>".$vdb." ".$this->alignADetails[$i][$fr]->Chain."<>".$chain."</p>";
				}	*/		
			}
		}

		function find_first_residue($i)
		{
			for($j=0;$j<count($this->alignADetails[$i]);$j++)
			{
				if($this->alignADetails[$i][$j]->Chain!='-')
				{
					//print $this->alignADetails[$i][$j]->Chain." ".$j;
					return $j;
				}
			}
			return- 1;
		}

		private function util_SL_StructureCode($structZone)
		{
			if($structZone=="Interface")
				return 'I';
			else if ($structZone=="Core")
				return 'C';
			else if ($structZone=="surfin")
				return 's';
			else if ($structZone=="surfout")
				return 'S';
			else
				return 'E';
		}

		private function merge_sequence_strInf($seq,$strInf,$objAdet)
		{
			$structSeq ="";
			$start_seq = intval($seq->SeqStart);
			$resCount = 0;
			$seqCount = $start_seq;
			//var_dump($objAdet);
			//print "<h1>$seq->VDB</h1>";
			for($i=0;$i<count($objAdet);$i++)
			{
				//print "<p>".$objAdet[$i]->Res."=".$seq->Alignment[$i]."</p>"; 
				/*if($objAdet[$i]->Res != $seq->Alignment[$i])
					var_dump($objAdet[$i]);*/
				if($objAdet[$i]->Res=="-")
					$structSeq .=".";	
				else if(!isset($strInf[$resCount]['Rid']))
					$structSeq .="%";	
				else if(intval($strInf[$resCount]['Rid'])== intval($objAdet[$i]->Res_Num))
				{
					$structSeq .=$this->util_SL_StructureCode($strInf[$resCount]['SIC']);
					$resCount++;
				}
				//var_dump($seq);
			}
			//print "<p> $structSeq </p>";
			return $structSeq;
		}
		private function merge_sequence_strInf_V0($seq,$strInf)
		{
			// Original Algorithm without kpax advanced details
			$structSeq ="";
			$start_seq = intval($seq->SeqStart);
			$resCount = 0;
			$seqCount = $start_seq;
			if(count($strInf)>0)
			{
				for($i=0 ; $i<strlen($seq->Alignment);$i++)
				{
					if($seq->Alignment[$i]!="-")
					{
						//setup to the same sequence number
						if(intval($strInf[$resCount]['Rid'])< intval($seqCount))
						{
							
							while(intval($strInf[$resCount]['Rid'])< intval($seqCount) && $resCount < count($strInf)-1)
							{
							//	print "(".$strInf[$resCount]['Rid']."-".$seqCount.")";
								$resCount++;
							}
							//print ">>(".$strInf[$resCount]['Rid']."-".$seqCount.")";
						}
						//if($seq->Alignment[$i]==$strInf[$resCount]['Res2'] && $strInf[$resCount]['Rid']==$seqCount )
						if($seq->Alignment[$i]==$strInf[$resCount]['Res2'] )
						{
							$structSeq .=$this->util_SL_StructureCode($strInf[$resCount]['SIC']);
							if($resCount < count($strInf)-1)
								$resCount++;						
						}
						else
						{
							if($resCount >= count($strInf)-1)
							{
								$structSeq .="+";
							}
							//print "<p>Residue not match ". $seq->Alignment[$i]." <>".$strInf[$resCount]['Res2']. " ".$seqCount." <> ".$strInf[$resCount]['Rid'];
						}
						$seqCount++;
					}
					else
					{
						$structSeq .=".";
					}
				}
			}
			//print "<p>Len Al:".strlen($seq->Alignment)." Len St:".strlen($structSeq)."</p>";
			return $structSeq;
		}

	}

?>