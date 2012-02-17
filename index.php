<?php header( 'Content-Type: text/html; charset=UTF-8' ); ?>
<!DOCTYPE HTML>
<!--
/*
 * jQuery File Upload Plugin Demo 6.0.4
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
-->
<html lang="en">
<head>
<meta charset="utf-8">
<title>Text Renderer</title>
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
<style type="text/css">
body {padding-top: 80px;}
</style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Text Renderer</h1>
    </div>

   <div>
	<img id="generated-image" />
   </div>

<form id="myForm" action="gen_zip.php?width=400" method="post" enctype="multipart/form-data">
	<label>Text</label>
	<div>
		<textarea rows="5" name='text'>Enter text here</textarea>
	</div>

	<label>Font Filename</label>
	<div>
		<select name='font' style="background-color:white;">
		  <option>mangal</option>
		  <!--<option>oriya</option>  -->
		  <!--<option>assamese</option>-->
		  <option>times</option>
		</select>
	</div>

	<label>Font Size</label>
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

	<div>
		<button id="generate" class="btn" >Render Text</button>
	</div>

	<div class="well">
        <h3>CSV Formatting Requirements:</h3>
        <ul>
            <li>Save the CSV using UTF-8 character encoding.</li>
            <li>The first column should be the text to be converted and the second should be the output filename.</li>
            <li>The values should all be quoted.</li>
        </ul>
	</div>

	<label>CSV</label>
	<div>
		<input type="file" name="file" id="file" /> 
	</div>

	<div>
		<input id="render-csv" class="btn" type=submit value="Render CSV" />
	</div>
</form>

<div id="csv-out"></div>

</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
function addParams(baseURL){
	var text_field = $('textarea[name=text]').val();
	var font = $('select[name=font]').val();
	var fontSize = $('select[name=fontSize]').val();
	
	var font_params = "";
	font_params += "&font=" + font;
	font_params += "&fontSize=" + fontSize;

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
