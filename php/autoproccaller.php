<?php

require_once("config.php");

class AutoProcCaller {

  private $lconn;
  private $as_err;

//------------------------------------------------------------------
  function connectdb() {
    $retval = true;
    $this->lconn = oci_connect( DB_USER, DB_PASSWORD, DB_CONNSTR );
    if ( ! $this->lconn ) {
      $retval = false;
      $e = oci_error();
      $this->ai_err = $e['code'];
      $this->as_err = htmlentities($e['message'], ENT_QUOTES);
    }
    return $retval;
  }
  public function get_errmsg() {
    return( $this->as_err );
  }
  //------------------------------------------------------------------
  // Get parameters for procedure $as_proc in package $as_pack
  function get_params( $av_uname, $as_pack, $as_proc, &$a_lista_params, &$as_err ) {
    $retval = 0;

    $ls_plsqlstmnt='BEGIN PCK_OPW_PROCINFO.ARG_NAMEZ(
      :avi_uname,
      :avi_pack_n,
      :avi_proc_n,
      :aro_params);END;';

    $stmt = oci_parse( $this->lconn, $ls_plsqlstmnt );

    oci_bind_by_name( $stmt, ":avi_uname", $av_uname, -1 );
    oci_bind_by_name( $stmt, ":avi_pack_n", $as_pack, -1 );
    oci_bind_by_name( $stmt, ":avi_proc_n", $as_proc, -1 );

    $a_lista_params = oci_new_cursor( $this->lconn );
    oci_bind_by_name( $stmt, ":aro_params", $a_lista_params, -1, OCI_B_CURSOR );

    if ( ! oci_execute( $stmt ) ) {
      $e = oci_error($stmt);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);

    } elseif ( ! oci_execute( $a_lista_params ) ) {
      $e = oci_error($stmt);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);
    }
    oci_free_statement( $stmt );
    return( $retval );
  }

  //------------------------------------------------------------------
  function fnc_proc_prc_packages( $av_uname, &$ao_cur, &$as_err ) {
    $retval = 0;

    $ls_plsqlstmnt='Begin PCK_OPW_PROCINFO.PRC_PACKAGES( :AVI_UNAME, :ARO_PACKS );end;';

    $stmt = oci_parse( $this->lconn, $ls_plsqlstmnt );

    oci_bind_by_name( $stmt, ":AVI_UNAME", $av_uname, -1 );

    $ao_cur = oci_new_cursor( $this->lconn );
    oci_bind_by_name( $stmt, ":ARO_PACKS", $ao_cur, -1, OCI_B_CURSOR );

    if ( ! oci_execute( $stmt ) ) {
      $e = oci_error($stmt);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);

    } elseif ( ! oci_execute( $ao_cur ) ) {
      $e = oci_error($ao_cur);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);
    }
    oci_free_statement( $stmt );
    return ( $retval );
  }

  //------------------------------------------------------------------
  function fnc_proc_prc_procs( $av_input, &$ao_cur, &$as_err ) {

    $retval = 0;

    $ls_plsqlstmnt="Begin PCK_OPW_PROCINFO.PRC_PROCS(:AVI_UNAME,:AVI_PACK,:ARO_PROCS);end;";

    $stmt = oci_parse( $this->lconn, $ls_plsqlstmnt );

    oci_bind_by_name( $stmt, ":AVI_UNAME", $av_input[0], -1 );
    oci_bind_by_name( $stmt, ":AVI_PACK", $av_input[1], -1 );

    $ao_cur = oci_new_cursor( $this->lconn );
    oci_bind_by_name( $stmt, ":ARO_PROCS", $ao_cur, -1, OCI_B_CURSOR );

    if ( ! oci_execute( $stmt ) ) {
      $e = oci_error($stmt);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);
    } elseif ( ! oci_execute( $ao_cur ) ) {
      $e = oci_error($ao_cur);
      $retval = $e['code'];
      $as_err = htmlentities($e['message'], ENT_QUOTES);
    }
    oci_free_statement( $stmt );
    return ( $retval );
  }
}
?>
