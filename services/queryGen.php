<?php
class universalDBUpd
{
	var $strBDacc = "host=localhost dbname=vessingc_taxi user=vessingc_taxiuser password=MonagasMonroy";
	var $db;
	
	function universalDBUpd()
	{
		$db = pg_connect($strBDacc);
		
		if (!$db) {
			echo "fail to connect"."\n"; exit;
		}
	}	

	public function testAmf()
	{
		return("AMFPHP: Estoy vivo");
	}
	//
	
	public function deleteUser($id,$web,$clm,$bqt)
	{
		//$db = connectDataBase();
		//$db = pg_connect("host=localhost dbname=vessingc_taxi user=vessingc_taxiuser password=MonagasMonroy");
		//if (!$db) {echo "fail to connect"."\n"; exit;}
		$auxSrtig = ", serv_web = '".$web."', serv_clm = '".$clm."', serv_bqm = '".$bqt."' ";
		$query = "UPDATE users SET status = '0'".$auxSrtig."WHERE id_user = '".$id."'";
		$Qu = pg_query($this->db,$query);
		$row = pg_fetch_object($Qu);
		$return[] = $row;
		pg_free_result($Qu);
		pg_close($this->db);
		return($return);
	}

} //fin clase taxiUtils.
?>
