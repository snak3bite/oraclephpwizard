<?php
require_once( "../php/config.php" );
require_once( "../php/autoproccaller.php" );

echo "<html><body><pre>";

$apc = new AutoProcCaller();
if ( ! $apc->connectdb() ) {
  echo "failed to connect to db";
} else {

  echo "Test get_params:" . NL;
  $licode = $apc->get_params( 'PCK_OPW_TEST', 'prc_get_chapters', $l_lista_params, $ls_err );
  if ( $licode != 0 ) {
    echo "Failed prc_get_chapters: " . $ls_err;
  } else {
    while ( $entry = oci_fetch_assoc ( $l_lista_params ) ) {
      print_r ($entry);
    }
  }

  echo NL . "Test fnc_proc_prc_packages:" . NL;
  $licode = $apc->fnc_proc_prc_packages( DB_USER, $lo_cur, $ls_err );
  if ( $licode != 0 ) {
    echo "Failed fnc_proc_prc_packages: " . $ls_err;
  } else {
    while ( $entry = oci_fetch_assoc ( $lo_cur ) ) {
      print_r ($entry);
    }
  }

  echo NL . "Test fnc_proc_prc_procs:" . NL;
  $lv_input = array( DB_USER, 'PCK_OPW_TEST' );
  $licode = $apc->fnc_proc_prc_procs( $lv_input, $lo_cur, $ls_err );
  if ( $licode != 0 ) {
    echo "Failed fnc_proc_prc_procs: " . $ls_err;
  } else {
    while ( $entry = oci_fetch_assoc ( $lo_cur ) ) {
      print_r ($entry);
    }
  }
}
echo "</pre></body></html>";
?>
