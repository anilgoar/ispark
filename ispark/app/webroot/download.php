<?php 
function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        
        foreach ($files as $file)
        {
            /*if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				//echo $file."<br/>";
            }
            else */if (is_file($file) === true)
            {
				//echo $file."<br/>";
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}


$folder = $_GET['pdf'];



    $downloadfile = date('Ymd_His').'.zip';
	$zipfile='zip_data/'.$downloadfile;
	Zip('pdffile/'.$folder.'/',$zipfile);

/////////////////////  Empty Directory of PDF /////////////////////////////
 
	$files = glob('pdffile/'.$folder.'/*');
	foreach($files as $file){ 
	if(is_file($file))
	unlink($file); 
	}
	rmdir('pdffile/'.$folder);

///////////////////////////////////////////////////////////////////////////
 
	if(file_exists($zipfile))
	{
/*		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($zipfile)) . ' GMT');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename='.$downloadfile);
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($zipfile));
		readfile($zipfile);
*/		
		header("Content-disposition: attachment; filename=$downloadfile");
		header("Content-type: application/zip");
		readfile($zipfile);
 		unlink($zipfile);
	}
        
       echo  "<script type='text/javascript'>";
echo "window.close();";
echo "</script>";