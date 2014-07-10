package jaime.com
{
	import flash.events.Event;
	
	public class EventoCustom extends Event
	{
		// Event types.
		public static const EVENT_DEFAULT:String = "event1";
		public static const EVENT_CUSTOM:String = "event2";
		public static const EVENT_CLOSE_WIN:String = "event3";
		
		public function EventoCustom(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		override public function clone():Event {
			// Return a new instance of this event with the same parameters.
			return new EventoCustom(type, bubbles, cancelable);
		}
	}
}