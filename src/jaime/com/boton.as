package jaime.com{
	
	import fl.controls.*;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.*;
	import flash.filters.*;
	import flash.text.*;
	
	public class boton extends Sprite{
		public var posX:uint = 0;
		public var posY:uint = 0;
		public var ancho:uint = 120;
		public var alto:uint = 30;
		public var fondo:int = 0xcccccc;
		public var borde:int = 0xcccccc;
		public var fondoOv:int = 0x111111;
		public var bordeOv:int = 0xcccccc;
		public var colorGlow:int = 0x00FF00;
		public var btnName:String = "";

		public var sombra:Boolean = false;
		private var resetGlow:BitmapFilter = new GlowFilter(0x000000,0,0,0,2,2,false,false)
		private var greenGlow:BitmapFilter = new GlowFilter(0x00FF00,1,5,5,2,2,true,false)
		private var shadowLeter:BitmapFilter =new DropShadowFilter(1,90,0xffffff,1,0,0,1,1,false,false,false)
		private var myfilterBV:BitmapFilter =new BevelFilter()
		private var overShape:Shape = new Shape()
		private var titulo:TextField = new TextField();
		public function boton(posX:uint,posY:uint,
							  ancho:uint,alto:uint,
							  fondo:int,borde:int,
							  fondoOv:int,bordeOv:int,
							  colorGlow:int,
							  btnName:String){
			this.posX = posX;
			this.posY = posY;
			this.ancho = ancho;
			this.alto = alto;
			this.fondo = fondo;
			this.borde = borde
			this.fondoOv = fondoOv;
			this.bordeOv = bordeOv;
			this.colorGlow = colorGlow;
			this.btnName = btnName
			buildbtn()
			this.addEventListener(MouseEvent.MOUSE_OVER,overHandler)
			this.addEventListener(MouseEvent.MOUSE_OUT,outHandler)
			this.addEventListener(MouseEvent.MOUSE_DOWN,downHandler)
		}
		public function deacBtn(){
			this.removeEventListener(MouseEvent.MOUSE_OVER,overHandler)
			this.removeEventListener(MouseEvent.MOUSE_OUT,outHandler)
			this.titulo.alpha = .5
		}
		public function reacBtn(){
			this.addEventListener(MouseEvent.MOUSE_OVER,overHandler)
			this.addEventListener(MouseEvent.MOUSE_OUT,outHandler)
			this.titulo.alpha = 1
		}
		private function buildbtn(){
			
			this.graphics.beginFill(fondo)
			this.graphics.lineStyle(.5,borde,1,true); //(.5,borde,1,true)
			this.graphics.drawRect(posX,posY,ancho,alto)
			
			overShape.graphics.beginFill(fondoOv)
			overShape.graphics.lineStyle(.5,bordeOv,1,true);
			overShape.graphics.drawRect(posX,posY,ancho,alto)
			overShape.alpha = 0
			this.addChild(overShape)
			
			titulo.x = posX+2
			titulo.y = posY+1
			titulo.width = ancho-4
			titulo.height = alto-2
			titulo.selectable=false
			var myFont:Font =  new myLucida();	
			var myFormat:TextFormat = new TextFormat();
			myFormat.font = myFont.fontName;
			myFormat.align= TextFormatAlign.CENTER;
			myFormat.size = 11;
			myFormat.letterSpacing = 2;
			myFormat.color = 0x000000;
			titulo.embedFonts=true;
			titulo.text = btnName;
			titulo.setTextFormat(myFormat);
			titulo.filters = [getSombraLetra(0xffffff)];
			this.addChild(titulo)
			
		}
		
		private function overHandler(ev:MouseEvent):void{
				ev.currentTarget.overShape.alpha = 1
				ev.currentTarget.titulo.textColor = colorGlow
				ev.currentTarget.titulo.filters = [getBevelFilter()]
				
				ev.currentTarget.filters = [getColorGlow(colorGlow)]
		}
		private function outHandler(ev:MouseEvent):void{
			ev.currentTarget.overShape.alpha = 0
			ev.currentTarget.titulo.textColor = 0x000000
			ev.currentTarget.titulo.filters = [getSombraLetra(0xffffff)]
			ev.currentTarget.filters = [resetGlow]
		}
		private function downHandler(ev:MouseEvent):void{
			//ev.currentTarget.overShape.alpha = .5
			//ev.currentTarget.titulo.textColor = 0xffffff
			ev.currentTarget.titulo.filters = [getSombraLetra(0x000000)]
		}
		private function getColorGlow(color:int):GlowFilter{
			return new GlowFilter(color,1,8,8,2,2,true,false)
		}
		private function getSombraLetra(color:int):DropShadowFilter{
			return new DropShadowFilter(1,90,color,1,0,0,1,1,false,false,false)
		}
		private function getBevelFilter():BevelFilter{
			return new BevelFilter(1,45,0xffffff,1,0,1,3,3,1,1,"Outer",false)
		}
	}
}