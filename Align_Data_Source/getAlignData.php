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
				$alignADetails = $this->get_Advace_Aligment_Details($this->Id_Align,count($alignData));
				$objAnalitycs = new MSA_Structure_Annotation($alignData,$alignADetails);
				$objAnalitycs->add_Structural_Information(); 
				//var_dump($alignData);
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

	private function get_Advace_Aligment_Details_by_Structure($id_Align,$structure)
	{
		$sql="SELECT * FROM viperdb.MSA_Structure_Based_Advanced_Details as det  where det.Id_Align= $id_Align and det.A_Column=$structure order by det.A_Row;";
		//print $sql;
		$objCon = new conection();
		$res =$objCon->executeQuerry($sql);
		if(!isset($res))
		{
			print "<h1>".$structure."</h1>";
			print var_dump($res);
		}
			
		return $res;
	}


	private function get_Advace_Aligment_Details($id_Align,$structresNum)
	{
		$arrayDetails=[];
		for($i=1;$i<=$structresNum;$i++)
		{
			$arrayDetails[]=$this->get_Advace_Aligment_Details_by_Structure($id_Align,$i);
		}
		//print count($arrayDetails)."..................>";
		return $arrayDetails;
	}


}

$objAlign = new Align_Data_Source();
?>