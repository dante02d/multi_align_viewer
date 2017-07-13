<?php 
ini_set('display_errors', 1);

include("api_phipsi_v2_library.php");

class VDB_StructuralRegions
{
	public $objData;
	private $objCoreLib;
	public $subUnit;

	function __construct ($GetSetup_Params = True)
	{
		$this->objData = new api_phipsi_v2_Data();
		$this->objCoreLib = new api_phipsi_v2_Core();
		$this->subUnit = "A";
		if($GetSetup_Params)
			$this->setup_Get_Params();
	} 

	private function setup_Get_Params ()
	{
		if(isset($_GET['VDB']))
		 { 
		 	$this->objData->VDB=$_GET['VDB'];
		 }
		 
		 if(isset($_GET['SIC']))
		 { 
		 	$this->objData->SIC=$_GET['SIC'];
		 }
		 
		 if(isset($_GET['layer_name']))
		 { 
		 	$this->objData->layerName=$_GET['layer_name'];
		 }

		  if(isset($_GET['subUnit']))
		 { 
		 	$this->subUnit = $_GET['subUnit'];
		 }
		 $this->objData->key=$this->objCoreLib->getKey($this->objData);
	}

	public function setup_Object_Params()
	{
		$this->objData->key=$this->objCoreLib->getKey($this->objData);
		if(count($this->objData->key)==0)
			throw new Exception("No key found for: ".$this->objData->VDB, 1);
			

	}

	private function get_Str_Region($SIC)
	{
		 $this->objData->SIC =$SIC;
		 $varData = $this->objCoreLib->getContent($this->objData,$this->subUnit);
		 return $varData;
	}

	private function extract_important_parameters($data)
	{
		$newArrayData = [];
		
		for($i=0; $i< count($data)-1;$i++)
		{
			$newArrayData[$i]  = array('Res' =>   $data[$i][2],'Rid' =>   intval($data[$i][1]),'SIC'=> $this->objData->SIC,'Res2'=>$this->converToSingleAminoacidCode($data[$i][2]));
		}
		return $newArrayData;
	}

	function util_array_orderby()
	{
	    $args = func_get_args();
	    $data = array_shift($args);
	    foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}

	function converToSingleAminoacidCode($strCode)
	{
		if($strCode=="GLY")
        return "G";
	    else if ($strCode=="PRO")
	        return "P";
	    else if ($strCode=="ALA")
	        return "A";
	    else if ($strCode=="VAL")
	        return "V";
	    else if ($strCode=="LEU")
	        return "L";
	    else if ($strCode=="ILE")
	        return "I";
	    else if ($strCode=="MET")
	        return "M";
	    else if ($strCode=="CYS")
	        return "C";
	    else if ($strCode=="PHE")
	        return "F";
	    else if ($strCode=="TYR")
	        return "Y";
	    else if ($strCode=="TRP")
	        return "W";
	    else if ($strCode=="HIS")
	        return "H";
	    else if ($strCode=="LYS")
	        return "K";
	    else if ($strCode=="ARG")
	        return "R";
	    else if ($strCode=="GLN")
	        return "Q";
	    else if ($strCode=="ASN")
	        return "N";
	    else if ($strCode=="GLU")
	        return "E";
	    else if ($strCode=="ASP")
	        return "D";
	    else if ($strCode=="SER")
	        return "S";
	    else if ($strCode=="THR")
	        return "T";
	    else
	        return "$";
	}

	public function get_Structural_Regions($JsonFormat = True)
	{
		/* if()
		 {*/

		 $varData_i = $this->extract_important_parameters($this->get_Str_Region("Interface"));

		 $varData_c = $this->extract_important_parameters($this->get_Str_Region("Core"));
		
		 $varData_si = $this->extract_important_parameters($this->get_Str_Region("surfin"));
		 
		 $varData_so = $this->extract_important_parameters($this->get_Str_Region("surfout"));

		 $strRegionsArray = array_merge($varData_i,$varData_c,$varData_so,$varData_si);
		 if($JsonFormat)
		 {
		 	print json_encode ($this->util_array_orderby( $strRegionsArray,"Rid"));
		 	return ""; 
		 }
		else
			return $this->util_array_orderby( $strRegionsArray,"Rid");
		//}
	}
}

/*$strRegions = new VDB_StructuralRegions();
$strRegions->get_Structural_Regions(True);*/

?>