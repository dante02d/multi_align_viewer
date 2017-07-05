<?php 
include "../API2/VDB_Structural_Regions.php";
	class MSA_Structure_Annotation
	{
		private $sequenceData;
		
		public function __construct($_sequenceData)
		{
			$this->sequenceData = $_sequenceData;
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
					$strAnnotation= $this->merge_sequence_strInf($data,$dataStr);
					$data->strAnnotation = $strAnnotation;
					//var_dump($data);
				}
				catch(Exception $e)
				{
					//echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
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

		private function merge_sequence_strInf($seq,$strInf)
		{
			$structSeq ="";
			$start_seq = intval($seq->SeqStart);
			$resCount = 0;
			$seqCount = $start_seq;
			if(count($strInf)>0)
			{
				for($i=0 ; $i<strlen($seq->Alignment);$i++)
				{
					if($seq->Alignment[$i]!=".")
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