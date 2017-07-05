<?php

class api_phipsi_v2
{
	public $objData;
	
	public function __construct()
	{
		$this->objData = new api_phipsi_V2_Data();
	}
	
	public function Init()
	{
		
		if(isset($description)) 
		{
			header("Vary: Accept");
			header("Content-Type: text/plain; charset=utf-8");
			header('Pragma: public');        
			header('Cache-control: private');
			header('Expires: -1');
		  	echo <<<EOF
			Produces the output for the Phi-Psi Explorer
EOF;
			exit;
		}
	}
	
	public function makeHeaderFormat($format)
	{
		
		switch($format) 
		{
			case "csv":
				print("Vary: Accept");
				print("Content-Type: text/plain; charset=utf-8");
				print('Pragma: public');        
				print('Cache-control: private'); 	
				print('Expires: -1');
				print "Entry Key:".$this->objData->key.", Entry ID: ".$this->objData->VDB.", SIC: ".$this->SIC."\n";
  			break;
			default: //xml
			//header('Access-Control-Allow-Origin: *');
			  header("Vary: Accept ");
			  header("Content-Type: application/xml; charset=utf-8 ");
			  header('Pragma: public ');
			  header('Cache-control: private ');
			  header('Expires: -1 ');
			  
			  print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n ");
			  print "<viperdb entry_key='".$this->objData->key."' entry_id='".$this->objData->VDB."' SIC='".$this->objData->SIC."'>\n ";
		 	break;
		}
	}
	
	public function makeBody()
	{
		$attrs=array(
		"x",
		"y",
		"sub",
		"res",
		"rid",
		"uc",
		"sst",
		"r",
		"phi",
		"psi",
		"sasa",
		"num_int",
		"identity",
		"ass_ene",
		"solv_ene",
		"bsa"
		);
		
		 $objCore = new api_phipsi_v2_Core();
		 $subUnits = $objCore->getSubUnits($this->objData);
		 // first get subUnits from objData object 
		 for($i=0; $i< count( $subUnits)-1; $i++)
		 {
			//echo  "printing ".$i." -> ". $subUnits[$i][0]." ".count($subUnits[$i])." <br>";
			$SASA_Bound_Data= $objCore->getSASA_Bound($this->objData,$subUnits[$i][0]);
			$Content_Data = $objCore->getContent($this->objData,$subUnits[$i][0]);
			
			print ("<subunit n='".$subUnits[$i][0]."' " .
					"s='".(count( $Content_Data)-1)."' " .
					"minSASAB='".$SASA_Bound_Data[0][0]."' " .
					"maxSASAB='".$SASA_Bound_Data[0][1]."' >\n");
				
				for($j=0; $j< count( $Content_Data)-1; $j++)
		 		{
		 			$X=$Content_Data[$j][6];
		 			if( $X< 0)
					{
						$X+=360;
					}
					
					 $attr_vals=array(
								    round($objCore->getMx($X,$Content_Data[$j][7])), //x
								    round($objCore->getMy($X,$Content_Data[$j][7])), //y
								    $Content_Data[$j][0],    //sub
								    $Content_Data[$j][2],    //res
								    $Content_Data[$j][1],    //rid
								    $Content_Data[$j][11],   //uc
								    $Content_Data[$j][4],   //sst
								    $Content_Data[$j][5],    //r
								    $X,    //phi
								    $Content_Data[$j][7],    //psi
								    $Content_Data[$j][10],    //sasa
								    $Content_Data[$j][12],    //num_int
								    $Content_Data[$j][13],     //identity
								    $Content_Data[$j][14],    //ass_ene
								    $Content_Data[$j][15],    //solv_ene
								    $Content_Data[$j][16],    //bsa
			   						 );
			   						 
				   	print("        <datamarker ");
					for($k=0;$k<count($attrs);$k++){
					  print(  "$attrs[$k]='$attr_vals[$k]' ");
					}
					print( "  />\n");
					
		 		}
		 		
		 		print ("</subunit>\n");					
		 }
	}
	
	public function makeFoot()
	{
			print ("</viperdb>\n");	
	}

	public function Help()
	{
		if(isset($help)) 
		{
			header("Vary: Accept");
			header("Content-Type: text/plain; charset=utf-8");
			header('Pragma: public');        
			header('Cache-control: private');
			header('Expires: -1');

	  		echo <<<EOF

Usage:

  api_phipsi.php?option1=value1&option2=value2&...

Options:

  name                   value                      output                   default
-----------------------------------------------------------------------------------------------
  help                                    -This page
  VDB                  PDB ID             -selects a DB entry                2bbv
  SIC                  interface,core,    -The corresponding group of res    interface
                       surfout,surfin
  orderby              sasa,assene,rid    -Sort the output using value       res id (rid)
                       solvene,bsa
  ascdesc              ASC,DESC           -Specify sorting order (up|down)   ASC
  limit                integer            -Specify a limit on the number     All
                                           of returned residues (per chain)
  SIC_core_threshold   FLOAT[0.00-1.00]   -Threshold to select core res      0.05  [= 5%]
  SIC_surf_threshold   FLOAT > 0.00       -Threshold to select surf out res  10.00 [A]
  format               xml,csv            -Formats the output to value       xml

Examples:

  api_phipsi.php

  api_phipsi.php?VDB=1stm&SIC=core

  api_phipsi.php?SIC=surfout

EOF;

			exit;

		}

	}



}

class api_phipsi_v2_Data
{
	public $format;
	public $VDB;
	public $SIC;
	public $SIC_core_threshold;
	public $SIC_surf_threshold;
	public $limit;
	public $ascdesc;
	public $order;
	public $key;
	public $layerName;
	
	public function __construct()
	{
		
		$this->format="xml"; //set a default value for the output format
		$this->VDB="2bbv";  //set a default value for the entry to query
		$this->key=10;
		$this->SIC="interface"; //set a default value for the SIC group to fetch
		$this->SIC_core_threshold = 0.05; // set default value for core threshold
		$this->SIC_surf_threshold = 10; // set ddefault value for surface threshold
		$this->limit="10000";
		$this->ascdesc=" ASC ";
		$this->order=" t5.SI+0 ";
		
		$this->layerName="all";
		
		//print("this is inside of consutructor api_phipsi_v2_Data ".$this->VDB."<br>");
	}
	
	public function changeOrderby($orderby)
	{
		switch($orderby) 
		{
		  	case "assene":
		    	$this->order=" t5.TAE ";
		    break;
		  	case "solvene":
		    	$this->order=" t5.TSE ";
		    break;
		  	case "bsa":
		    	$this->order=" t5.TBSA ";
		    break;
		  	case "sasa":
		    	$this->order=" t5.SASA";
		    break;
		  	default: // defaults to
		    	$this->order=" t5.SI+0 ";
		}
	}

}

class api_phipsi_v2_Core
{
	
	private $dbName;
	private $dbUser;
	private $dbPassword;
	private $dbServer;
	
	public function __construct()
	{
		
		
		$this->dbName="viperdb";
		$this->dbUser="root";
		$this->dbPassword="root";
		//$this->dbUser="viper";
		//$this->dbPassword="3opvXMb3";
		
		$this->dbServer="localhost";
	}
	
	private function connectDB()
	{
		$mysqli = new mysqli($this->dbServer,$this->dbUser,$this->dbPassword,$this->dbName);
		if($mysqli->connect_errno)
		{
			print("Failed to conect database ".$mysqli->connect_errno." ".$mysqli->connect_error )	;
		}
		return $mysqli;
	}
	
	public function getKey(&$Obj_Data)
	{
		//print("VDB".$Obj_Data->VDB);
		$mysqli = $this->connectDB();
		$v_result=$mysqli->query("SELECT entry_key FROM viper where entry_id='$Obj_Data->VDB'");
		$v_row =$v_result->fetch_assoc();
		return $v_row['entry_key'];
	}
	
	public function getVirusFamilyGenus($VDBcode)
	{
		//print("VDB".$Obj_Data->VDB);
		$mysqli = $this->connectDB();
		$v_results=$mysqli->query("SELECT family,genus FROM viper where entry_id='$VDBcode'");
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}
	
	public function getVirusDetails($VDBcode)
	{
		//print("VDB".$Obj_Data->VDB);
		$mysqli = $this->connectDB();
		$v_results=$mysqli->query("SELECT name,family,genus FROM viper where entry_id='$VDBcode'");
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}
	
	public function getAllVDB()
	{
		//print("VDB".$Obj_Data->VDB);
		$mysqli = $this->connectDB();
		$sql="select entry_id from viper";
		$v_results=$mysqli->query($sql);
		$i=0;
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}
	
	public function getAllVDBs($VDBp)
	{
		//print("VDB".$Obj_Data->VDB);
		$mysqli = $this->connectDB();
		$sql="select entry_id from viper where entry_id like \"$VDBp%\" LIMIT 9";
		$v_results=$mysqli->query($sql);
		$i=0;
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}
	
	
	public function getSubUnits(&$Obj_Data)
	{
		
		$mysqli = $this->connectDB();
		$sql = "SELECT distinct(v.`auth_1_asym_id`) FROM viper_residue_contact v 
				WHERE v.`entry_id`='$Obj_Data->VDB' AND v.`auth_1_asym_id` NOT IN ('R','S','T')";
		
		//outdate method no implemented in database		
		if($Obj_Data->layerName != "all")
		{
			$sql .= " AND v.`layer_name`='$Obj_Data->layerName'";
		}
		// end *********************************
		$sql .= " ORDER BY v.`auth_1_asym_id`";
		
		//print $sql;
		
		$v_results=$mysqli->query($sql);
		$i=0;
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}
	
	public function getSASA_Bound(&$Obj_Data,$SubUnit)
	{
		$mysqli = $this->connectDB();
		$sql = "SELECT round(min(v.`sasa_bound`),2),round(max(v.`sasa_bound`),2) " .
				"FROM viper_residue_asa v WHERE v.`entry_id`='$Obj_Data->VDB' AND v.`label_asym_id`='$SubUnit'";
		
		
		//print $sql;
		
		$v_results=$mysqli->query($sql);
		$i=0;
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	//print ("SASA bound found ". $v_row[$i][0]." ".$v_row[$i][1]."<br>");
		 	//$i++;
		 }
		 
		 //v_[0][0] sasa_bound_min
		 //v_[0][1] sasa_bound_max
		 
		return $v_row;
	}
	
	public function getContent(&$Obj_Data,$SubUnit)
	{
		$mysqli = $this->connectDB();
		$sql = "CALL Viper_PhiPsi_ContentFile ('".$Obj_Data->VDB."',".$Obj_Data->key.",'".$SubUnit."','".$Obj_Data->SIC. "');";
		//print $sql;
		
		$v_results=$mysqli->query($sql);
		$i=0;
		$v_row=array();
		
		 while($v_row[]=$v_results->fetch_array())
		 {
		 	/*print ("sub unit found ". $v_row[$i][0]." <br>");
		 	$i++;*/
		 }
		 
		return $v_row;
	}

	public function getMx($X,$Y)
	{
		
		$degree2radian = acos(-1.0)/180.0;	
		$mx=cos($X*$degree2radian)*sin($Y*$degree2radian);
		//then some empirical correction/transformation (try&error to get it rigth)
	    $mx=((($mx*225.)+256.)/1.);
	    /*$Radius=1000;
	    $mx=cos(((($X)*3.1416)/180))*cos((($Y)*3.1416)/180)*$Radius;*/
	    return $mx;
	}	
	
	public function getMy($X,$Y)
	{
		$degree2radian = acos(-1.0)/180.0;	
		$my=sin($X*$degree2radian)*sin($Y*$degree2radian);
		//then some empirical correction/transformation (try&error to get it rigth)
	    $my=-((($my*440.)-512.)/2.);
	   /* $Radius=1000;
	    $my=cos(((($X)*3.1416)/180))*sin((($Y)*3.1416)/180)*$Radius;*/
	    return $my;
	}
	
	public function calculate_SurfOut_Charge($vdb)
	{
		$tot_Positive = 0;
		$tot_Negative = 0;
		
		$objData = new api_phipsi_v2_Data();
		$objData->VDB=$vdb;
		$objData->SIC="surfout";
		$objData->key = $this->getKey($objData);
		
		$subUnits = $this->getSubUnits($objData);
		for($i=0; $i< count( $subUnits); $i++)
		{
			$subUnits_data = $this->getContent($objData,$subUnits[$i][0]);
			for($j=0; $j< count( $subUnits_data)-1; $j++)
			{
				if($subUnits_data[$j][2]=="ARG" || $subUnits_data[$j][2]=="LYS" )
				{
					$tot_Positive+=1;
				}
				else if($subUnits_data[$j][2]=="ASP" || $subUnits_data[$j][2]=="GLU" )
				{
					$tot_Negative+=1;
				}
			}
		}
		//print $tot_Positive;
		//print $tot_Negative;
		$IAU_Charge = $tot_Positive- $tot_Negative;
		$CP_Charge = $IAU_Charge*60;
		return $CP_Charge;
	}	


}


?>
