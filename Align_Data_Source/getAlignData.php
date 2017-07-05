<?php
ini_set('display_errors', 1);
require_once './DataConexion.php';
include 'MSA_Structure_Annotation.php';

class Align_Data_Source
{
	private $Id_Align;
	public function __construct()
	{
		if(isset($_GET["idAlign"]))
		{
			$this->Id_Align = $_GET["idAlign"];
		}
		else
		{
			$this->Id_Align =1;
		}
				
		if (isset($_GET["Op"]))
		{
			if($_GET["Op"]==1)
			{
					print($this->getAlignment($this->Id_Align));
				
			}
			else if ($_GET["Op"]==2)
			{
				$alignData = $this->getAlignment($this->Id_Align,False);
				$objAnalitycs = new MSA_Structure_Annotation($alignData);
				$objAnalitycs->add_Structural_Information(); 
				print json_encode ($alignData);
			}
		}
		else
		{
				print($this->getAlignment($this->Id_Align));
			
		}

		
	}

	
	public function getAlignment($idAlign,$jsonFormat = True)
	{
		$sql="SELECT * FROM MSA_Structure_Based_Details where Id_Align = $idAlign";
		$objCon = new conection();
		$res =$objCon->executeQuerry($sql);
		if($jsonFormat)
			return json_encode ($res);
		else
			return $res;
	}
}
$objAlign = new Align_Data_Source();
?>