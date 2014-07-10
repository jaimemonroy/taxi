package jaime.com
{
	import air.net.URLMonitor;
	import flash.events.StatusEvent;
	import flash.net.URLRequest;
	import mx.controls.Alert;
	
	public class ConnectionManager
	{
		private var eventObj:StatusEvent;
		private var urlMonitor:URLMonitor;
		// if true, show the Alert window
		private var showMessage:Boolean;
		// message to display when connection fails and showMessage is true
		private var message:String;
		
		// URL to test for a connection
		[Bindable]
		public var connectionURL:String;
		[Bindable]
		public var isConnected:Boolean = false;
		
		public function ConnectionManager(showMessage:Boolean=true,
										  connectionURL:String="http://www.google.com",
										  message:String="This application requires\nan Internet connection"):void{
			
			this.showMessage = showMessage;
			this.connectionURL = connectionURL;
			this.message = message;
			startMonitor();
		}
		
		// start the URLMonitor and test against the connectionURL
		public function startMonitor():void{
			trace ("con mgr testing: "+connectionURL)
			var urlRequest:URLRequest = new URLRequest(connectionURL)
			urlRequest.method = "HEAD";
			urlMonitor = new URLMonitor(urlRequest);
			urlMonitor.addEventListener(StatusEvent.STATUS, statusChanged);
			urlMonitor.start();
		}
		
		// handle changes in the connection status and dispatches StatusEvent
		public function statusChanged(event:StatusEvent):void{
			
			this.isConnected =  urlMonitor.available;
			if(!this.isConnected && this.showMessage){
				Alert.show(this.message, "Connection Failure");
			}
			trace ("STATUS "+this.isConnected)
			eventObj = new StatusEvent(StatusEvent.STATUS);
			dispatchEvent(eventObj);
		}
	}
}