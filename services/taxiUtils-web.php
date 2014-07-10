<?php
class taxiUtils
{
public function testAmf()
{
	return("AMFPHP: Estoy vivo");
}
//
function updGenTable($table,$row,$rowVal,$idTable,$valId)
{
	$db = pg_connect("host=200.74.207.9 dbname=transp_admin user=admin password=MonagasMonroy");
	if (!$db) {echo "fail to connect"."\n"; exit;}
	$Query = "UPDATE ".$table." SET ".$row." = '".$rowVal."' WHERE ".$idTable." = '".$valId."'";

	$Qu = pg_query($db,$Query);
	$row = pg_fetch_result($Qu);
	$return = $row;
	pg_free_result($Qu);
	pg_close($db);
	return($return);

}
//
public function verifyUser($user,$psw)
{
	$db = pg_connect("host=200.74.207.9 dbname=transp_admin user=admin password=MonagasMonroy");
	if (!$db) {echo "fail to connect"."\n"; exit;}
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

public function getAllUsrData()
{
	//$db = connectDataBase();
	$db = pg_connect("host=200.74.207.9 dbname=transp_admin user=admin password=MonagasMonroy");
	if (!$db) {echo "fail to connect....."."\n"; exit;}
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
public function deleteUser($id,$web,$clm,$bqt)
{
	//$db = connectDataBase();
	$db = pg_connect("host=200.74.207.9 dbname=transp_admin user=admin password=MonagasMonroy");
	if (!$db) {echo "fail to connect"."\n"; exit;}
	$auxSrtig = ", serv_web = '".$web."', serv_clm = '".$clm."', serv_bqm = '".$bqt."' ";
	$query = "UPDATE users SET status = '0'".$auxSrtig."WHERE id_user = '".$id."'";
	$Qu = pg_query($db,$query);
	$row = pg_fetch_object($Qu);
	$return[] = $row;
	pg_free_result($Qu);
	pg_close($db);
	return($return);
}

} //fin clase taxiUtils.
?>
