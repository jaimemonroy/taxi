
package jaime.com
{
	import mx.charts.DateTimeAxis;
	import mx.formatters.DateFormatter;
	
	public class FormattedDateTimeAxis extends DateTimeAxis
	{
		public var formatStringYears:String = "YYYY";
		public var formatStringMonths:String = "MM.YYYY";
		public var formatStringDays:String = "DD.MM.YYYY";
		
		protected var formatter:DateFormatter = new DateFormatter();
		
		public function FormattedDateTimeAxis()
		{
			super();
		}
		
		protected override function formatDays(d:Date, previousValue:Date, axis:DateTimeAxis):String
		{
			return formatDate(d, formatStringDays);
		}
		protected override function formatMonths(d:Date, previousValue:Date, axis:DateTimeAxis):String
		{
			return formatDate(d, formatStringMonths);
		}
		protected override function formatYears(d:Date, previousValue:Date, axis:DateTimeAxis):String
		{
			return formatDate(d, formatStringYears);
		}
		
		/**
		 * General purpose formatter function
		 *
		 *  @param d The Date object that contains the unit to format.
		 *  @param formatString The format string for the label to be rendered.
		 *  @return The formatted label.
		 */
		protected function formatDate(d:Date, formatString:String):String
		{
			formatter.formatString = formatString;
			return formatter.format(d);
		}
	}
}