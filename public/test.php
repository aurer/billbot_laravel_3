<?php

$base = "http://images1.bonhams.com/image?src=Images%2Flive%2F2012-12%2F03%2F8685725-2-2.jpg&tmp=web300&top=0.000000&left=0.000000&right=1.000000&bottom=1.000000&dt=zoom_image&format=jpg&autosizefit=0&strip=1&nostats=1&width=2584&height=3272&";

$zip = new ZipArchive;

if ( $zip->open('images.zip', ZIPARCHIVE::CREATE) ){

	for($i=1;$i<65;$i++){
		$file = file_get_contents($base.'tile='.$i.'%3A64');
		$zip->addFromString("image_$i.jpg", $file);
	}

	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment; filename=images.zip"); 
	header("Content-Length: ".filesize('images.zip'));
	ob_end_flush();
	@readfile('images.zip');
	$zip->close();
	unlink('tmp.zip');
}

//header("Content-type: application/octet-stream"); 
//header("Content-disposition: attachment; filename=$zip_path"); 

//$zip->close();