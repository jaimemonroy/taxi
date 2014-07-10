<?php
class taxiUtils
{
//
function testAmf()
{
	return("AMFPHP: estoy Vivo");
}
//
function verifyUser($user,$psw)
{
	$db = connectDataBase();
	$query = "SELECT * FROM users WHERE cedula = '".$user."'";
	$Qu = pg_query($db,$query);
	$row = pg_fetch_object ($Qu);
	if ($row)
	{
		if($psw === $row->clave)
		{
			$return->valid=1;
			$return->name = $row->nombre;
			$return->role = $row->nivel;
		}
		else
		{
			$return->valid = 0;
			$return->name ="";
			$return->role = "";
		}
	}
	else
	{
		$return->valid = -1;
		$return->name ="";
		$return->role = "";
	}
	pg_free_result($Qu);
	pg_close($db);
	return($return);
}
function updateServer($objServ)
{
	$db = connectDataBase();
	$query = "UPDATE ".$objServ->table." SET ".$objServ->srvFlag." = ".$objServ->valSrvFlag." WHERE ".$objServ->idTable." = ".$objServ->valId;

	$Qu = pg_query($db,$Query);
	$row = pg_fetch_object($Qu);
	$return[] = $row;
	pg_free_result($Qu);
	pg_close($db);
	return($return);

}
function resetValServer($objServ)
{
	$db = pg_connect("host=localhost dbname=vessingc_taxi user=vessingc_taxiuser password=MonagasMonroy");
	if (!$db) {
		echo "fail to connect"."\n"; exit;
	}
	$query = "UPDATE ".$objServ->table." SET  WHERE ".$objServ->idTable." = ".$objServ->valId;
}
function getAllUsrData()
{
	$db = connectDataBase();
	$query = "SELECT * FROM users WHERE status = '1' ORDER BY id_user";
	$Qu = pg_query($db,$query);
	while ($row = pg_fetch_object ($Qu))
	{
		$return[] = $row;
	}
	pg_free_result($Qu);
	pg_close($db);
	return( $return );
}
function deleteUser($id,$srvs)
{
	$db = connectDataBase();
	$auxSrtig = selServers($srvs);
	$query = "UPDATE users SET status = '0'".$auxSrtig."WHERE id_user = '".$id."'";
	$Qu = pg_query($db,$query);
	$row = pg_fetch_object($Qu);
	$return[] = $row;
	pg_free_result($Qu);
	pg_close($db);
	return($return);
}
/*
 *
* Miscelaneus private functions (not visible to AMFPHP becasue private )
*
*/
private function connectDataBase()
{
	$db = pg_connect("host=localhost dbname=vessingc_taxi user=vessingc_taxiuser password=MonagasMonroy");
	if (!$db) {
		echo "fail to connect"."\n"; exit;
	}
	return $db;
}

private function selServers($option)
{
	$auxSrtig ="";
	switch ($option)
	{
		case 0:	//From web only
			$auxSrtig=", serv_WEB = 'T', serv_CLM = 'F', serv_BQM = 'F' ";
			break;
		case 1:	//From Catia la Mar Server only
			$auxSrtig=", serv_WEB = 'F', serv_CLM = 'T', serv_BQM = 'F' ";
			break;
		case 2:	//From Barquisimeto server only
			$auxSrtig=", serv_WEB = 'F', serv_CLM = 'F', serv_BQM = 'T' ";
			break;
		case 3:	// WEB +  catia la mar Servers (pending Barquisimeto)
			$auxSrtig=", serv_WEB = 'T', serv_CLM = 'T', serv_BQM = 'F' ";
			break;
		case 4:	// WEB +  Barquisimeto Servers (pending catia la mar)
			$auxSrtig=", serv_WEB = 'T', serv_CLM = 'T', serv_BQM = 'F' ";
			break;
		case 5: //All Srvers
			$auxSrtig=", serv_WEB = 'T', serv_CLM = 'T', serv_BQM = 'T' ";
			break;
	}
	return $auxSrtig;
}

} //fin clase taxiUtils.
?>
