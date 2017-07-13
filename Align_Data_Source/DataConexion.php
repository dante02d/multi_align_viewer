<?php
    class Conection
    {
    	private $conn;
    	private $user="root";
		private $pwd="root";
		private $host="localhost";
		private $dataBase="viperdb";
		
		public function conect()
		{
			$this->conn = new MySQLi($this->host,$this->user,$this->pwd,$this->dataBase);
			if ($this->conn->connect_error) 
			{
    			die("Connection failed: " . $this->conn->connect_error);
				error_log("Connection failed: " . $this->conn->connect_error);
			}	 
		}
		
		public function executeQuerry($sql)
		{
			$this->conect();
			$data =  array();
			if(!$result = $this->conn->query($sql))
			{
				printf("Mysql Error: %s\n", $this->conn->error);
				$this->conn->close();
				return $data;
			}
			
			if ($result->num_rows > 0) 
			{
				$i=0;
    			while($row = $result->fetch_object()) 
    			{
        			$data[$i]=$row;
					$i++;
				}	
    		}
			$result->close();
			$this->conn->close();
			
			return $data;
		}
		public function sqlOperations($sql)
		{
			$this->conect();
			if ($this->conn->multi_query($sql) === TRUE) 
			{
    			
			} 
			else 
			{
    			echo "Error: " . $sql . "<br>" . $this->conn->error;
			}
		}
    }
?>