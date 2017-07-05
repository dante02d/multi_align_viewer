<?php 
ini_set('display_errors', 1);

include("api_phipsi_v2_library.php");

class VDB_StructuralRegions
{
	private $objData;
	private $objCoreLib;
	private $subUnit;

	function __construct ()
	{
		$this->objData = new api_phipsi_v2_Data();
		$this->objCoreLib = new api_phipsi_v2_Core();
		$this->subUnit = "A";
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

	private function get_Str_Region($SIC)
	{
		 $this->objData->SIC =$SIC;
		 $varData = $this->objCoreLib->getContent($this->objData,$this->subUnit);
		 return $varData;
	}

	public function get_Structural_Regions()
	{
		 $varData = $this->get_Str_Region("Interface");
		 print json_encode ($varData);

		 print "<br>CORE <br>";
		 $varData = $this->get_Str_Region("Core");
		 print json_encode ($varData); 
	}
}

$strRegions = new VDB_StructuralRegions();
$strRegions->get_Structural_Regions();

?>