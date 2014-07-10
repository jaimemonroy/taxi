// ActionScript file

private function getAllUsrList():void
{
	//first get Clients name-id list..
	var query:String = "SELECT id_client, nombre_corto FROM clientes ORDER BY id_client";
	gateway0.call( "universalDBUpd.exQuery" , new Responder(onResultIdClient, onFaultCliId),query)
	
	
}

private function onResultIdClient(result:Array):void
{
	trace("onResultIdClient...")
	for(var s:String in result) result[s] = new ObjectProxy(result[s]);
	acClientId = new ArrayCollection (result);
	for (var i:int = 0;i< acClientId.length;i++)
		trace("cliente: "+i+": "+acClientId.getItemAt(i).nombre_corto)
	var query:String = "SELECT * FROM roles order by id_role"
	gateway0.call( "universalDBUpd.exQuery" , new Responder(onResultRoles, onFaultRoles),query)		
}


private function onResultRoles(result:Array):void
{
	for(var s:String in result) result[s] = new ObjectProxy(result[s]);
	acRoles = new ArrayCollection (result);
	var query:String = "SELECT * FROM ciudades where sede = 't'"
	gateway0.call( "universalDBUpd.exQuery" , new Responder(onResultListCIties, onFaultCitiesList),query)
}

private function onResultListCIties(result:Array):void
{
	var rex:RegExp = /[\s\r\n]+/gim;
	var item:Object;
	for each (item in result)
	{
		item.sigla = item.sigla.replace(rex,'')		//filter spaces in cities
	}
	for(var s:String in result) result[s] = new ObjectProxy(result[s]);
	acCities = new ArrayCollection (result);
	var query:String = "SELECT * FROM users WHERE status = '1' ORDER BY cedula"
	gateway0.call( "universalDBUpd.exQuery" , new Responder(onResultListUsrs, onFaultUserList),query)
}

private function onResultListUsrs(result:Array):void
{
	trace("onResultListUsrs...")
	var item:Object;
	//var i:int = 0;
	//var aux:Number
	for each (item in result)
	{
		item.empresaName = acClientId.getItemAt(getIdxacEmpresas(item.empresa)).nombre_corto
		item.sedeLongName = getCityName(item.sede)
	}
	
	
	for(var s:String in result) result[s] = new ObjectProxy(result[s]);
	acUsrsData = new ArrayCollection (result);
	
}

private function getCityName(_sede:String):String
{
	var rex:RegExp = /[\s\r\n]+/gim;
	var nameCity:String=""
	for(var i:int=0;i<acCities.length;i++)
	{
		//if(acCities.getItemAt(i).sigla.replace(rex,'') == _sede)
		if(acCities.getItemAt(i).sigla == _sede)
		{
			nameCity = acCities.getItemAt(i).ciudad
				break
		}
	}
	return nameCity
}

private function getIdxacEmpresas(id_emprsa:String):int
{
	var i:int
	for (i=0;i<acClientId.length;i++)
	{
		if (this.acClientId.getItemAt(i).id_client == id_emprsa)
			break	
	}
	return i
}


private function onFaultUserList(fault:String):void
{
	trace("AMFPHP: Fail on getAllUsers: "+fault)
}
private function onFaultCliId(fault:String):void
{
	trace("AMFPHP: Fail on get clients iD: "+fault)
}
private function onFaultCitiesList(fault:String):void
{
	trace("AMFPHP: Fail on get Cities: "+fault)
}
private function onFaultRoles(fault:String):void
{
	trace("AMFPHP: Fail on get Roles: "+fault)
}