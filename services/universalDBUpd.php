<?php
class universalDBUpd
{
	var $strBDacc = "host=200.74.207.9 dbname=transp_admin user=admin password=MonagasMonroy";
	var $aux;
	
	function universalDBUpd()
	{
		$this->aux = "1021679";
	}	

	public function testAmf()
	{
		return("AMFPHP: Estoy vivo");
	}
	//
	public function retOneObj($objeto)
	{
		$str1=$objeto->col1;
		$str2=$objeto->val1;
		$numArr=sizeof($objeto->arrayVal);
		$retunr = "col1: ".$str1."... val1: ".$str2." arreglo de ".$numArr." elementos..";
		return ($retunr);
	}
	public function exQuery($Query)
	{
		$db = pg_connect($this->strBDacc);
		//if (!$db) {echo "fail to connect"."\n"; exit;}
		if (!$db) {return "fail to connect";}
		//$queryType = strtok($Query," ");
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
		{
			$return[] = $row;
		} 
		pg_free_result($Qu);
		pg_close($db);
		return($return);
		
	}
} //fin clase taxiUtils.
?>
