<?php
class universalDBUpd
{
	var $strBDacc = "host=localhost dbname=transp_admin user=admin password=MonagasMonroy";
	
	public function exQuery($Query)
	{
		$db = pg_connect($this->strBDacc);
		if (!$db) {return "fail to connect";}
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
		{
			$return[] = $row;
		} 
		pg_free_result($Qu);
		pg_close($db);
		return($return);
		
	}

	public function getNextId_Serv($sec, $table)
	{
		// Ejemplo: $sec = "CCSAD"
		// $table: servicio, pax, clientes
		$db = pg_connect($this->strBDacc);
		if (!$db) {
			return "fail to connect";
		}
		$par = $sec."%";
		if ($table == "servicio")
			$id = "id_serv";
		elseif ($table == "pax")
		$id = "id_pax";
		elseif ($table == "clientes")
		$id = "id_client";
		else
			return null;
		$Query  = "SELECT max(".$id.") FROM ".$table." WHERE ".$id." LIKE '".$par."'";
		//echo "query: ".$Query."\n";
		$Qu = pg_query($db,$Query);
		$num_rows = pg_num_rows($Qu);
		if($num_rows > 0)
		{
			$par = pg_fetch_row ($Qu);
			//echo "pg_fetch_row par: ".$par[0]."\n";
			$par = substr($par[0], -6);
			$num = (int)$par;
			$num = $num + 1;
			$par = $sec . str_pad($num, 6, '0', STR_PAD_LEFT);
		}
		else
			$par = $sec . "000001";
		pg_free_result($Qu);
		pg_close($db);
		//echo "return par: ".$par."\n";
		return ($par);
	}
} //fin clase
?>
