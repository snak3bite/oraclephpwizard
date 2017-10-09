<?php
require_once( "../php/config.php" );
require_once( "../php/codemaker.php" );

echo "<html><body><pre>";

$lpack = 'PCK_OPW_TEST';
$lproc = 'PRC_GET_CHAPTERS';

$cm = new Codemaker();
$code = $cm->make_class( $lpack, $lproc, false );
echo 'Test make_class: ' . NL . '//-------------------------------' . NL;
echo $code;
echo NL . '//-------------------------------' . NL;

echo "Test of write_to_file returns: ";
$path = '/var/www/html/rw';

$fname = $lpack . '.' . $lproc . '.php';
echo $cm->write_to_file( $path, $fname, $code, true ) . NL;

echo 'Test make_skeleton: ' . NL . '//-------------------------------' . NL;
echo $code = $cm->make_skeleton();
echo NL . '//-------------------------------' . NL;
echo "</pre></body></html>";
?>
