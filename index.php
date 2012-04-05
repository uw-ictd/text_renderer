<?php header( 'Content-Type: text/html; charset=UTF-8' ); ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Text Renderer</title>
<link rel="stylesheet" href="bootstrap.min.css">
<style type="text/css">
body {padding-top: 2%;}
</style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Text Renderer</h1>
        	<br />
			<div>
        	<p>
            This site is intended for rendering text images for use with ODK Collect. Not all scripts are completely supported. For example this Oriya character will render incorrectly: 'ଥି'
        	If there is a script which does not render at all (i.e. you see only boxes), please post the issue on <a href="http://groups.google.com/group/opendatakit">the ODK mailing list</a> and we will try to accommodate it if possible.
        	This site only supports unicode UTF-8 text. Certain encodings, for exmaple ISFOC, will not work.
        	If this text renderer does not meet your needs, as a backup plan you can always take screenshots of the text in your choice text editor.
            </p>
			</div>
    </div>

   <div>
	<img id="generated-image" />
   </div>

<form id="myForm" action="gen_zip.php?width=400" method="post" enctype="multipart/form-data">
	<label>Text: </label>
	<div>
		<textarea rows="5" name='text'>Enter text here</textarea>
	</div>
<!--
	<label>Font Filename</label>
	<div>
		<select name='font' style="background-color:white;">
		  <option>mangal</option>
		  <option>oriya</option>  
		  <option>assamese</option>
		  <option>times</option>
		</select>
	</div>
-->
	<label>Font Size: </label>
	<div>
		<select name='fontSize' style="background-color:white;">
		  <option>14</option>
		  <option>18</option>
		  <option>24</option>
		  <option>28</option>
		  <option>32</option>
		  <option>40</option>
		  <option>48</option>
		</select>
	</div>
	
    <label>Use <a href="http://developer.gnome.org/pango/stable/PangoMarkupFormat.html">Pango Markup</a>:</label>
    <input type="checkbox" name="useMarkup" value="true" />
    
	<div>
		<button id="generate" class="btn" >Render Text</button>
	</div>
    <hr />
	<div class="header">
		<h1>Batch Renderer</h1>
		<br />
        <div>
        	<p>
            If you need to render a large amount of text, you can create a spreadsheet and use the batch renderer to process it all at once.
        	The spreadsheet must be a CSV and follow the formatting requirements below.
        	The output will be a zipped directory. If you are using a Windows machine you might need to use a program like 7-zip to properly extract the directory.
        	The font size parameter is taken from above.</p>
    	</div>
	</div>

	
	<label>CSV: </label>
	<div>
		<input type="file" name="file" id="file" /> 
	</div>

	<div>
		<input id="render-csv" class="btn" type=submit value="Render CSV" />
	</div>
    <br />
	<div class="well">
        <h3>CSV Formatting Requirements:</h3>
        <ul>
            <li>The CSV must be saved so that it uses UTF-8 character encoding and all values are quoted. (OpenOffice and LibreOffice make this easy to do).</li>
            <li>The first column should be the text to be rendered (with the column header "translation") and the second should be the output filename (with the column header "filename").</li>
        </ul>
	</div>
</form>

<div id="csv-out"></div>

</div>
<footer><center><a href="https://github.com/nathanathan/font_renderer">Source code available here</a></center></footer>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
function addParams(baseURL){
	var text_field = $('textarea[name=text]').val();
	var font = "arial";//$('select[name=font]').val();
	var fontSize = $('select[name=fontSize]').val();
	var useMarkup = $('input[name=useMarkup]').is(':checked');
	
	var font_params = "";
	font_params += "&font=" + font;
	font_params += "&fontSize=" + fontSize;
	font_params += "&useMarkup=" + useMarkup;

	var src = baseURL;
	src += "?width=400";
	//src += "&background=FFF&color=000";
	//src += "&padding=5";
	
	src += "&text=" + text_field;
	src += font_params;
	return src;
}
function updateImage(){
	src = addParams("render_text.php");
	$("#generated-image").attr("src", src);
	return false;
}
function renderCSV(){
	src = addParams("render_text.php");
	src += "&text=" + text_field;
	
	$("#generated-image").attr("src", src);
	
	$('#csv-out').html('<p>Rendering...</p>')
	$.ajax({
	  url: src,
	  success: function(data) {
	    $('#csv-out').html(data);
	  }
	});
}
$(document).ready(function(){
	$("#generate").click(
		updateImage
	);
	/*
	$('#myForm').submit(function() {
		$('#render-csv').replaceWith('<div>Rendering...</div>');
	});
	
	$("#render-csv").click(
		renderCSV
	);
	*/
});
//Initialize:
updateImage();
</script>
</body> 
</html>