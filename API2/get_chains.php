<?php 
ini_set('display_errors', 1);

include("api_phipsi_v2_library.php");

$obj2 = new api_phipsi_v2_Data();
 //echo "VDB= ".$obj2->VDB ." <br>";
 //$obj->objData =& $obj2;
 $obj = new api_phipsi_v2_Core();
 if(isset($_GET['VDB']))
 { 
 	$obj2->VDB=$_GET['VDB'];
 }
 $obj2->key=$obj->getKey($obj2);

 $subUnits = $obj->getSubUnits($obj2);
 
 print json_encode($subUnits);
 /*
for($i=0; $i< count( $subUnits); $i++)
{
	echo  "printing ".$i." -> ". $subUnits[$i][0]." ".count($subUnits[$i])." <br>";	
}*/

 ?>