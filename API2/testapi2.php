<?php
ini_set('display_errors', 1);
/*
 * Created on 20/02/2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 include("api_phipsi_v2_library.php");
// echo "init Test  <br>";
 


 $obj2 = new api_phipsi_v2_Data();
 //echo "VDB= ".$obj2->VDB ." <br>";
 //$obj->objData =& $obj2;
 $obj = new api_phipsi_v2_Core();
 if(isset($_GET['VDB']))
 { 
 	$obj2->VDB=$_GET['VDB'];
 }
 
 if(isset($_GET['SIC']))
 { 
 	$obj2->SIC=$_GET['SIC'];
 }
 
 if(isset($_GET['layer_name']))
 { 
 	$obj2->layerName=$_GET['layer_name'];
 }
 $obj2->key=$obj->getKey($obj2);

 
 /*// expermientos getkey
// echo " data: ".$obj->getKey($obj2)."<br>";
 echo " data: ".$obj2->key."<br>";*/

//experimento getSubUnits()
/* $subUnits = $obj->getSubUnits($obj2);
 
for($i=0; $i< count( $subUnits); $i++)
{
	echo  "printing ".$i." -> ". $subUnits[$i][0]." ".count($subUnits[$i])." <br>";	
}*/


/*$obj3 = new api_phipsi_v2();
$obj3->objData =& $obj2;
$obj3->makeHaderFormat("xml");
*/

// eperiment on contentFile
/*
$subUnits = $obj->getContent($obj2,"A");
 
for($i=0; $i< count( $subUnits); $i++)
{
	echo  "printing ".$i." -> ". $subUnits[$i][0]." - ".$subUnits[$i][10]. " < ".count($subUnits[$i])." <br>";	
}

$subUnits = $obj->getContent($obj2,"B");
 
for($i=0; $i< count( $subUnits); $i++)
{
	echo  "printing ".$i." -> ". $subUnits[$i][0]." - ".$subUnits[$i][1]. " < ".count($subUnits[$i])." <br>";	
}


$subUnits = $obj->getContent($obj2,"C");
 
for($i=0; $i< count( $subUnits); $i++)
{
	echo  "printing ".$i." -> ". $subUnits[$i][0]." - ".$subUnits[$i][1]. " < ".count($subUnits[$i])." <br>";	
}*/


$obj3 = new api_phipsi_v2();
$obj3->objData =& $obj2;
$obj3->makeHeaderFormat("xml");
$obj3->makeBody();
$obj3->makeFoot();


 //echo "End Test *********** ->";
?>
