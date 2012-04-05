<?php
/**
 * This script renders images of text according to the parameters supplied in $_REQUEST
 * @author Nathan Breit (nabreit@gmail.com)
 */
//error_reporting(0); 


function render_text($params, $outFile){

	$text = $params['text'];
	$fontSize = 18;
	if(array_key_exists('fontSize', $params)){
		$fontSize = $params['fontSize'];
	}
	$width = $params['width'];
	if(array_key_exists('width', $params)){
		$width = $params['width'];
	}
	$font = 'arial';
	if(array_key_exists('font', $params)){
		$font = $params['font'];
	}
	$useMarkup = false;
	if(array_key_exists('useMarkup', $params)){
		$useMarkup = $params['useMarkup'];
	}
	
	//Create a dummy cairo surface and context needed to init the Pango Layout
	$dummy_surface = new CairoImageSurface(CairoFormat::ARGB32, 1, 1);
	$dummy_context = new CairoContext($dummy_surface);
	
	$l = new PangoLayout($dummy_context);
	//Set the font
	$desc = new PangoFontDescription("$font normal $fontSize");
	$l->setFontDescription($desc);
	
	//Here we set the width of the Pango Layout in order to have words wrap.
	//Note the conversion from pixels to Pango units
	$l->setWidth($width * Pango::SCALE);
	//$l->setWrap(PANGO_WRAP_WORD);
	
	if($useMarkup){
		$l->setMarkup($text);
	}
	else{
		$l->setText($text);
	}
	
	
	//Get the computed/logical size of the Pango layout in pixels
	$size = $l->getPixelSize();
	//error_log("H:".$size['height']." W:".$size['width']);
	
	//Create a Cairo surface to draw on
	$s = new CairoImageSurface(CairoFormat::ARGB32, $size['width'], $size['height']);
	$c = new CairoContext($s);
	
	//Set the background and font color
	if(array_key_exists('debug',$params)){
		$c->setSourceRGB(.1,149,.58);
		$c->paint();
		 
		$c->setSourceRGB(.1,.1,.1);
	}
	else{
		$c->setSourceRGB(1.0,1.0,1.0);
		$c->paint();
		 
		$c->setSourceRGB(0,0,0);
	}
	
	$l->showLayout($c);
	$s->writeToPng($outFile);
}

if(basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	//$text = "हिन्दी संवैधानिक रूप से भारत की प्रथम राजभाषा है और भारत की सबसे अधिक बोली और समझी जानेवाली भाषा है";
	//$font = "/opt/bitnami/apache2/htdocs/font_renderer/uploader/php/files/mangal.ttf";
	
	// Write the image to a temporairy file
	$tempFile = tempnam('/tmp/','') . '.png';
	render_text($_REQUEST, $tempFile);
	
	// Send the image back to the requester
	header("Content-type: image/png");
	header("Content-Disposition: filename=rendered_text.png");
	imagepng(imagecreatefrompng($tempFile));
	
}
?>