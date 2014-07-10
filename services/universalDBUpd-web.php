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
	/*
	 * 
	 */
	public function getUsersServList($cedula,$fromDate)
	{
	$db = pg_connect($this->strBDacc);
		if (!$db) {return "fail to connect";}
		$Query  = "SELECT * FROM servicio WHERE solicita = ".$cedula." AND fecha_servicio >='".$fromDate."'  ORDER BY fecha_servicio";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$servicio[] = $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_card, driver_name,cel_phone FROM drivers ORDER BY id_card";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$driver[]= $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_client, nombre_corto FROM clientes ORDER BY id_client";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$empresas[]= $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_serv, id_pax FROM crosrefpax ORDER BY id_serv";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$crosrefpax[]= $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_pax,id_client,pax_name,cel_phone FROM pax ORDER BY id_pax";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$pax[]= $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_serv,orden,sitio FROM sitios ORDER BY id_serv, orden";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$sitios[]= $row;
		pg_free_result($Qu);
		$Query  = "SELECT id_car_type,car_type FROM cartype ORDER BY id_car_type";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$cars[]= $row;
		pg_free_result($Qu);
		//
		$j=0;
		foreach ($servicio as $item)
		{
			$listaServicesClient[$j]->fecha = $item->fecha_servicio;
			$listaServicesClient[$j]->hora = $item->hora_servicio;
			$listaServicesClient[$j]->empresa = $this->findElement($empresas,"nombre_corto","id_client",$item->id_cliente);
			$listaServicesClient[$j]->car = $this->findElement($cars,"car_type","id_car_type",$item->id_cartyp);
			$listaServicesClient[$j]->driver = $this->findElement($driver,"driver_name","id_card",$item->id_driver);
			$driverPhone = $this->findElement($driver,"cel_phone","id_card",$item->id_driver);
			if($driverPhone != "")
				$listaServicesClient[$j]->driver .= " telf: ".$driverPhone;
			$cross = $this->findElements($crosrefpax,"id_pax","id_serv",$item->id_serv);
			$numPax = sizeof($cross);
			$listaServicesClient[$j]->pasajeros = "";
			$listaServicesClient[$j]->telfspax = "";
			for($i=0;$i<$numPax;$i++)
			{
				$paxName = $this->findElement($pax,"pax_name","id_pax",$cross[$i]);
				$paxCel = $this->findElement($pax,"cel_phone","id_pax",$cross[$i]);
				
				$listaServicesClient[$j]->pasajeros .= $paxName;
				$listaServicesClient[$j]->telfspax .= ($paxCel == "")? "NO TIENE" : $paxCel;
				if($numPax > 1 && $i < ($numPax-1))
				{
					$listaServicesClient[$j]->pasajeros .= ",";
					$listaServicesClient[$j]->telfspax .= ",";
				}
			}
			$arrSites=$this->findElements($sitios,"sitio","id_serv",$item->id_serv);
			$numSites=sizeof($arrSites);
			$listaServicesClient[$j]->origen = $arrSites[0];
			$listaServicesClient[$j]->destinos = "";
			for($i=1;$i<$numSites;$i++)
			{
				$listaServicesClient[$j]->destinos .= $arrSites[$i];
				if ($numSites > 2 && $i <($numSites-1))
					$listaServicesClient[$j]->destinos .= ",";
			}
			$j++;
			//echo $clientName."\n";
		}
		pg_close($db);
		return ($listaServicesClient);
		//return($cross);
	}
	///
	private function findElement($currentArr,$valRet,$element,$valElement)
	{
		for($i=0;$i<sizeof($currentArr);$i++)
		{
			if($currentArr[$i]->$element == $valElement)
				return $currentArr[$i]->$valRet;
		}
		return "";
	}
	private function findElements($currentArr,$valRet,$element,$valElement)
	{
		$cnt=0;
		for($i=0;$i<sizeof($currentArr);$i++)
		{
			if($currentArr[$i]->$element == $valElement)
			{
				$arrRet[$cnt] = $currentArr[$i]->$valRet;
				$cnt++;
			}
		}
		return ($arrRet);
	}	
} //fin clase taxiUtils.
?>
