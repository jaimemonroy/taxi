package jaime.com
{
	import spark.components.Panel
	import mx.controls.Image;
	import flash.filters.*;
	
	[Event(name="buttonClick", type="flash.events.Event")]

	public class ImagePanel extends Panel
	{
		[Bindable] public var ImageSourceL:String="";
		[Bindable] public var ImageSourceR:String="";
		[Bindable] public var ImagePadding:Number = 10;
		
		private var shadowLeter:BitmapFilter =new DropShadowFilter(1,90,0,1,0,0,1,1,false,false,false)
		private var myimageL:Image = new Image();
		private var myimageR:Image = new Image();

		public function ImagePanel()
		{
			super();
		}
				
		protected override function createChildren():void
		{
			super.createChildren();
			
			if( ! ImageSourceL ) return;
			
			myimageL.source = ImageSourceL;
			myimageR.source = ImageSourceR;
			myimageL.filters = [shadowLeter];
			myimageR.filters = [shadowLeter];
			myimageL.visible = true;
			myimageR.visible = true;
			myimageL.includeInLayout = true;
			myimageR.includeInLayout = true;
			this.addChild( myimageL );
			this.addChild( myimageR );
			
			
		}
		
		protected override function updateDisplayList(unscaledWidth:Number, unscaledHeight:Number):void
		{
			super.updateDisplayList(unscaledWidth,unscaledHeight);
			var x:int = width - ( myimageR.width + ImagePadding );
			var y:int = (myimageL.height - myimageR.height)/2 + ImagePadding
			myimageL.width = myimageL.measuredWidth;
			myimageL.height = myimageL.measuredHeight;
			myimageR.width = myimageR.measuredWidth;
			myimageR.height = myimageR.measuredHeight;
			this.setStyle('headerHeight', myimageL.height + ImagePadding*2 );			
			myimageL.move( ImagePadding, ImagePadding );
			myimageR.move( x, y );
		}

	}
}