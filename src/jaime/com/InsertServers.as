package jaime.com
{
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.TimerEvent;
	import flash.net.Responder;
	import flash.utils.Timer;
	
	import mx.collections.ArrayCollection;
	import mx.core.IFlexDisplayObject;
	import mx.events.CloseEvent;
	import mx.managers.PopUpManager;
	import mx.utils.URLUtil;
	
	import org.osmf.events.TimeEvent;
	
	public class InsertServers extends EventDispatcher
	{
		
		public var msgProcess:String=""
		private var gateway0 : RemotingConnection;
		private var gateway1 : RemotingConnection;
		
		private var Table:String="";
		private var valusToInsert:String="";
		
		
		private var ColWhere:String="";
		private var ColWhereVal:String="";
		private var message:String=""
		private var numServ:int = 1;
		private var serv1Name:String=""
		private var serv2Name:String=""
		private var servStatus:Object
		
		
		private var countEvnt:int=0
		private var web_val:String="F"
		private var clm_val:String="F"
		private var bqt_val:String="F"
		
		public function InsertServers(gw0:RemotingConnection,gw1:RemotingConnection,
									  _table:String,
									  _values:String,
										_message:String,_numServ:int,
										_serv1Name:String,_serv2Name:String)
		{
			gateway0=gw0;
			gateway1=gw1;
			
			Table=_table;
			
			valusToInsert = _values 	
			message=_message;
			numServ=_numServ
			serv1Name=_serv1Name;
			serv2Name=_serv2Name;
			
			doQuery1()
			
		}
		
		private function doQuery1():void
		{
			web_val = (serv1Name == "web")?   "T" : "F" 
			clm_val = (serv1Name == "clm")?   "T" : "F"
			bqt_val = (serv1Name == "bqt")?   "T" : "F"
			trace("Query1: "+buildQuery())	
			gateway0.call( "universalDBUpd.exQuery", new Responder(onResultQuery1, onFaultQuery),buildQuery())
		}
		
		private function onResultQuery1(res:Object):void
		{
			if(numServ == 1)
			{
				msgProcess="Servidor "+getSrvLongName(serv1Name)+" Actualizado"
				this.dispatchEvent(new EventoCustom(EventoCustom.EVENT_CUSTOM,false,false));
				//this.parent.setMsgSrvTxt("Servidor "+getSrvLongName(serv1Name)+" Actualizado")
				var tmWaitOneSec:Timer = new Timer(750)
				tmWaitOneSec.addEventListener(TimerEvent.TIMER,oneSecTimerHandler)	
				tmWaitOneSec.start()	
			}
			else
			{
				msgProcess="Acualizando servidor: "+getSrvLongName(serv2Name)+".."
				this.dispatchEvent(new EventoCustom(EventoCustom.EVENT_CUSTOM,false,false));
				web_val = (serv2Name == "web")?   "T" : (serv1Name == "web")?   "T" : "F" 
				clm_val = (serv2Name == "clm")?   "T" : (serv1Name == "clm")?   "T" : "F"
				bqt_val = (serv2Name == "bqt")?   "T" : (serv1Name == "bqt")?   "T" : "F"
				trace("Query2: "+buildQuery())		
				gateway1.call( "universalDBUpd.exQuery", new Responder(onResultQuery2, onFaultQuery),buildQuery());	
			}
		}
		
		private function onResultQuery2(res:Object):void
		{
			msgProcess=(this.message == "")? "Servidores "+getSrvLongName(serv1Name)+" y "+getSrvLongName(serv2Name)+" Actualizados" : this.message;
			var tmWaitOneSec:Timer = new Timer(1000)
			this.dispatchEvent(new EventoCustom(EventoCustom.EVENT_CUSTOM,false,false));
			tmWaitOneSec.addEventListener(TimerEvent.TIMER,oneSecTimerHandler)	
			tmWaitOneSec.start()
		}
												
		private function oneSecTimerHandler(ev:TimerEvent):void
		{
			
			ev.currentTarget.stop()
			ev.currentTarget.removeEventListener(TimerEvent.TIMER,oneSecTimerHandler)
			msgProcess="END"+Table
			this.dispatchEvent(new EventoCustom(EventoCustom.EVENT_CUSTOM,false,false));	
		}

		private function onFaultQuery(fault : String ) : void
		{
			trace ("AMFPHP falla llamada a universalDBUpd.exQuery (UpdateServers.as): "+fault)
			
		}
		
		private function buildQuery():String
		{
			var Query:String = "INSERT INTO "+Table+" VALUES("+valusToInsert+",";
			Query += "'"+clm_val+"',";
			Query += "'"+bqt_val+"',"	
			Query += "'"+web_val+"')"		
			return Query;
		}
		private function getSrvLongName(shortName:String):String
		{
			var auxStr:String=""
			switch(shortName)
			{
				case "web":
					auxStr="www.transportequiroga.com.ve"
					break;
				case "clm":
					auxStr="Catia la Mar"
					break;
				case "bqm":
					auxStr="Barquisimeto"
					break;		
			}
			return auxStr;
		}
	}
}