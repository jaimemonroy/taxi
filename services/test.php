<?php
//var_dump(exQuery("SELECT * FROM servicio WHERE solicita = 10 AND fecha_servicio >='2013-01-01 00:00:00'  ORDER BY fecha_servicio"));

var_dump(getUsersServList(114,'2014-01-01','2014-01-01'));
function getUsersServList($cedula,$fromDate,$toDate)
	{
		$db = pg_connect("host=localhost dbname=transp_admin user=admin password=MonagasMonroy");
		if (!$db) {return "fail to connect";}
		$Query  = "SELECT id_serv FROM servicio WHERE solicita = ".$cedula." AND fecha_servicio >= '".$fromDate."' AND estado != '0'";
		$Qu = pg_query($db,$Query);
		$num_rows = pg_num_rows($Qu);
		if($num_rows <= 0)
		{
			pg_free_result($Qu);
			pg_close($db);
			return null;
		}
		$rowCnt=0;
		while ($row = pg_fetch_object ($Qu))
		{
			$arrServlist[$rowCnt] = "'".$row->id_serv."'";
			$rowCnt++;
		}
		$servlist=implode(",",$arrServlist);
		pg_free_result($Qu);

		$Query  = "SELECT * FROM servicio WHERE id_serv IN (".$servlist.") ORDER BY fecha_servicio";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$servicio[] = $row;
		pg_free_result($Qu);

		$Query  = "SELECT id_card,driver_name,cel_phone FROM drivers ORDER BY id_card";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$driver[]= $row;
		pg_free_result($Qu);

		$Query  = "SELECT id_client,nombre_corto FROM clientes ORDER BY id_client";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$empresas[]= $row;
		pg_free_result($Qu);
	/*
	 * 		pasajeros: cross reference 
	 */
		$Query  = "SELECT id_serv,id_pax FROM crosrefpax WHERE id_serv IN (".$servlist.") ORDER BY id_serv";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$crosrefpax[]= $row;
		pg_free_result($Qu);

		$Query  = "SELECT id_pax FROM crosrefpax WHERE id_serv IN (".$servlist.")";
		$Qu = pg_query($db,$Query);
		$rowCnt=0;
		while ($row = pg_fetch_object ($Qu))
		{
			$arrPaxlist[$rowCnt]= "'".$row->id_pax."'";
			$rowCnt++;
		}
		$paxlist = implode(",",$arrPaxlist);
		pg_free_result($Qu);

		$Query  = "SELECT id_pax,id_client,pax_name,cel_phone FROM pax WHERE id_pax IN (".$paxlist.") ORDER BY id_pax";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
			$pax[]= $row;
		pg_free_result($Qu);
		/*
		 *		new sites struture with cross reference
		 */
		$Query  = "SELECT a.id_serv,a.orden, a.id_site, b.sitio FROM crosrefsite a, sitios b WHERE a.id_serv IN (".$servlist.") AND a.id_site = b.id_site ORDER BY a.id_serv, a.orden";
		$Qu = pg_query($db,$Query);
		while ($row = pg_fetch_object ($Qu))
		{
			$sitios[]= $row;
		}
		pg_free_result($Qu);
		

		//
		$j=0;
		foreach ($servicio as $item)
		{
			$listaServicesClient[$j]->id_serv = $item->id_serv;
			$listaServicesClient[$j]->fecha = $item->fecha_servicio;
			$listaServicesClient[$j]->hora = $item->hora_servicio;
			$listaServicesClient[$j]->empresa = findElement($empresas,"nombre_corto","id_client",$item->id_cliente);
			$listaServicesClient[$j]->car = $item->cartyp;
			$listaServicesClient[$j]->driver = findElement($driver,"driver_name","id_card",$item->id_driver);
			$driverPhone = findElement($driver,"cel_phone","id_card",$item->id_driver);
			if($driverPhone != "")
				$listaServicesClient[$j]->driver .= " telf: ".$driverPhone;
			$cross = findElements($crosrefpax,"id_pax","id_serv",$item->id_serv);
			$numPax = sizeof($cross);
			$listaServicesClient[$j]->pasajeros = "";
			$listaServicesClient[$j]->telfspax = "";
			for($i=0;$i<$numPax;$i++)
			{
				$paxName = findElement($pax,"pax_name","id_pax",$cross[$i]);
				$paxCel = findElement($pax,"cel_phone","id_pax",$cross[$i]);
				
				$listaServicesClient[$j]->pasajeros .= $paxName;
				$listaServicesClient[$j]->telfspax .= ($paxCel == "")? "NO TIENE" : $paxCel;
				if($numPax > 1 && $i < ($numPax-1))
				{
					$listaServicesClient[$j]->pasajeros .= ",";
					$listaServicesClient[$j]->telfspax .= ",";
				}
			}
			$arrSites=findElements($sitios,"sitio","id_serv",$item->id_serv);
			$numSites=sizeof($arrSites);
			$listaServicesClient[$j]->origen = $arrSites[0];
			$listaServicesClient[$j]->destinos = "";
			for($i=1;$i<$numSites;$i++)
			{
				$listaServicesClient[$j]->destinos .= $arrSites[$i];
				if ($numSites > 2 && $i <($numSites-1))
					$listaServicesClient[$j]->destinos .= "*";
			}
			$j++;
			//echo $clientName."\n";
		}
		pg_close($db);
		return ($listaServicesClient);
	}
	///
	function findElement($currentArr,$valRet,$element,$valElement)
	{
		for($i=0;$i<sizeof($currentArr);$i++)
		{
			if($currentArr[$i]->$element == $valElement)
				return $currentArr[$i]->$valRet;
		}
		return "";
	}
	function findElements($currentArr,$valRet,$element,$valElement)
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
					

?>
