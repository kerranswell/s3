<?php

array_shift($nodes);

$file_path = implode("/", $nodes);

$dir = DOCS_DIR;
$file = $dir."/".$file_path;
$file_name = explode("/", $file_path);
$file_name = array_pop($file_name);

if (is_file($file))
{
//    $type = getMimeType($file);

    header("Cache-control: private");
    header("Content-type: application/force-download");
    header("Content-transfer-encoding: binary\n");
    header("Content-disposition: attachment; filename=\"".$filename."\"");
    header("Content-Length: ".filesize($file));
    readfile($file);
    exit;
}


function getMimeType( $filename ) {
        $realpath = realpath( $filename );
        if ( $realpath
                && function_exists( 'finfo_file' )
                && function_exists( 'finfo_open' )
                && defined( 'FILEINFO_MIME_TYPE' )
        ) {
                // Use the Fileinfo PECL extension (PHP 5.3+)
                return finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $realpath );
        }
        if ( function_exists( 'mime_content_type' ) ) {
                // Deprecated in PHP 5.3
                return mime_content_type( $realpath );
        }
        return false;
}