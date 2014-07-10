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

	public function getNextId_Serv($sec,)
	{
		$db = pg_connect($this->strBDacc);
		if (!$db) {return "fail to connect";}
		$par = $sec & "%"		// Ejemplo: $sec = "CCSAD"
		$Query  = "SELECT max(id_serv) FROM servicio WHERE id_serv LIKE ".$par.;
		$Qu = pg_query($db,$Query);
		$num_rows = pg_num_rows($Qu);
		if($num_rows > 0) 
		{
			$row = pg_fetch_object ($Qu);
			$par = "'".$row->id_serv."'";
			$par = substr($par, -6);
			$num = (int)$par;
			$num = $num + 1;
			$par = $sec . str_pad($num, 6, '0', STR_PAD_LEFT);
		}
		else
			$par = $sec . "000001";
		pg_free_result($Qu);
		pg_close($db);
		return ($par);
	}
} //fin clase
?>
