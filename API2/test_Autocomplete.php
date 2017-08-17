<?php
/*
 * Created on 20/02/2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 include("api_phipsi_v2_library.php");
 
 /*$obj = new api_phipsi_v2_Core();
  
 $vdbFound = $obj->getAllVDB();
 $vdbArray= "{\"vdbs:\"[ ";
 
for($i=0; $i< count( $vdbFound)-1; $i++)
{
	 $vdbArray .="{\"VD\":";
	
	 $vdbArray .= "\"". $vdbFound[$i][0]."\" }";
	 if(($i+1)< (count( $vdbFound)-1))
	{
		$vdbArray.=", ";
	}	
}

 $vdbArray .="]}";
echo $vdbArray;*/
$VDB=$_GET["term"]; 

$obj = new api_phipsi_v2_Core();
  
 $vdbFound = $obj->getAllVDBs($VDB);
 $vdbArray= "[";
 
for($i=0; $i< count( $vdbFound)-1; $i++)
{
	
	if($i> 0)
	{
		$vdbArray.=", ";
	}	
	 $vdbArray .= "\"". $vdbFound[$i][0]."\" ";
	
}

 $vdbArray .="]";
echo $vdbArray;

 
?>
