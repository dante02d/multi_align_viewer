<?php
ini_set('display_errors', 1);
require_once './DataConexion.php';
class Align_Data_Source
{

	public function __construct()
	{
		
		if(isset($_GET["idAlign"]))
			print($this->getAlignment($_GET["idAlign"]));
		else
			print($this->getAlignment(1));
	}

	
	public function getAlignment($idAlign)
	{
		
		$sql="SELECT * FROM MSA_Structure_Based_Details where Id_Align = $idAlign";
		$objCon = new conection();
		$res =$objCon->executeQuerry($sql);
		return json_encode ($res);
	}
}
$objAlign = new Align_Data_Source();
?>