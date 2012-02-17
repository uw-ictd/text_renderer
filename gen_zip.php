<?php

//Disable caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once('render_text.php');

/* creates a temporairy directory in /tmp */
function tempdir($dir=false,$prefix='php') {
    $tempfile=tempnam('','');
    if (file_exists($tempfile)) { unlink($tempfile); }
    mkdir($tempfile);
    if (is_dir($tempfile)) { return $tempfile . '/'; }
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //vars
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,$file);
    }
    //debug
    //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
    
    //close the zip -- done!
    $zip->close();
    
    //check to make sure the file exists
    return file_exists($destination);
  }
  else
  {
    return false;
  }
}

//Check that there were no errors uploading the file.
if ($_FILES["file"]["error"] > 0) {
	echo "Upload Error: " . $_FILES["file"]["error"] . "<br />";
	die();
}

//Check that the file is a csv
if (strstr($_FILES["file"]["type"], '.csv')) {
	echo "Type Error: File type [" . $_FILES["file"]["type"] . "] should be a csv<br />";
	die();
}

/*
echo "Upload: " . $_FILES["file"]["name"] . "<br />";
echo "Type: " . $_FILES["file"]["type"] . "<br />";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
echo "Stored in: " . $_FILES["file"]["tmp_name"];
die();
*/

$output_cache = tempdir();

$files_to_zip = array();

$row = 1;

if (($handle = fopen($_FILES["file"]["tmp_name"], "r")) !== FALSE) {

	//Process the header:
	$data = fgetcsv($handle, 1000, ",");
	$col_idx = 0;
	$translation_col = 0;
	$filename_col = 1;
	foreach($data as $col) {
		if($col === 'translation'){
			if($filename_col == $col_idx){
				$filename_col = $translation_col;
			}
			$translation_col = $col_idx;
		}
		if($col === 'filename'){
			if($translation_col == $col_idx){
				$translation_col = $filename_col;
			}
			$filename_col = $col_idx;
		}
		$col_idx++;
	}
	//Process the rest of the CSV:
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $num = count($data);

        $row++;

	if( $data[$translation_col] && $data[$filename_col] ){
		
		$outFile = $output_cache . $data[$filename_col];
		
		$_REQUEST['text'] = $data[$translation_col];
		
		//$path_parts = pathinfo($data[1]);

		//$gdfont['image_type'] = $path_parts['extension'];

		//error_log($path_parts['filename']);

		//echo $path_parts['basename'], "\n";
		//echo $path_parts['extension'], "\n";
		
		render_text($_REQUEST, $outFile);
		
		$files_to_zip[] = $outFile;

	}

    }

    fclose($handle);

}//ssh://git@github.com/nathanathan/font_renderer.git

$path_parts = pathinfo($_FILES["file"]["name"]);
$zipfilename =  str_replace('.csv', '.zip', $path_parts['basename']);

$zippath = $output_cache . $zipfilename;

//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,$zippath);
if(!$result){
	echo "Zip Error: <br />";
}

// Return Zip File to Browser
header("Content-Disposition: attachment; filename=\"$zipfilename\"");
header("Content-type: application/octet-stream; name=$zipfilename");
readfile( $zippath );

?>
