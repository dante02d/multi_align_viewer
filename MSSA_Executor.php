<?php 
ini_set('display_errors', 1);
require_once './Align_Data_Source/DataConexion.php';

class MSSAValidator
{
	public function existAligment($strAlignment)
	{
		$strAlignment = str_replace(":", "_", $strAlignment);
		$arrFrags = explode(",", $strAlignment);
		$aligns = $this->getAlignmentsMatching(count($arrFrags),$arrFrags[0]);
		$idAlg=$this->matchAlignmet($arrFrags,$aligns);
		//var_dump( $aligns);
		return $idAlg;
	}

	private function getAlignmentsMatching($numSeqs,$firstElement)
	{
		
		$sql="SELECT * FROM viperdb.MSA_Structure_Based_Resume
				WHERE Align_Details like '%$firstElement%' and Structures_Num=$numSeqs;";
		$database = new Conection();
		$results = $database->executeQuerry($sql);
		return $results;
	}

	private function matchAlignmet($arrFrags,$aligns)
	{
		$itemnum= count($arrFrags);

		for($i=0;$i<count($aligns);$i++)
		{				
			$elemnts= explode(" ",$aligns[$i]->Align_Details);
			$itemsfound= array_diff($arrFrags, $elemnts);
			
			if(count($itemsfound)==0)
			{
				return $aligns[$i]->Id_Align;
			}		
		}
		return -1;
	}
}

	

	if(isset($_POST["vdbs"]))
	{
		$entriestoalign= (string)$_POST["vdbs"];
		//$entriestoalign="1dwn:B,2ms2:B";
		//echo $entriestoalign;
		//echo "<br>";
		$valid= new MSSAValidator();
		$existingAligment = $valid->existAligment($entriestoalign);
		if($existingAligment == -1)
		{

			$command="pymssahub ".$entriestoalign." | grep 'record'";
			//echo $command;
			$output= exec($command,$var,$retval);
			echo $output;
		}
		else
		{
			print "Record found:".$existingAligment;
		}		
	}	
	else
		echo json_encode("Error in chain ids");
	
	//echo $test;
	/*$command= "kpax 2>&1";
	$output= "<pre>".exec($command,$var,$retval)."</pre>";
	echo $output;*/
	
	//$command="whoami 2>&1";
	
	
	//$output= "<pre>".exec($command,$var,$retval)."</pre>";
	
	
	
	//var_dump($_POST);
	/*echo "<hr>";
	
	var_dump( $var);
	echo "<hr>";
	var_dump($retval);*/

?>