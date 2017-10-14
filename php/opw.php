<?php
require_once( "config.php" );
require_once( "codemaker.php" );

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
switch ($lang){
  case "sv":
    include("lang_sv.php");
    break;
  case "es":
    include("lang_es.php");
    break;
  default:
    include("lang_en.php");
    break;
}

main();

function main() {

  $apc = new AutoProcCaller();

  if ( ! $apc->connectdb() ) {
    echo LS_ERR_CONNECT;
    echo NL . $apc->get_errmsg();
    die;
  }

  $packages = array();
  $procedures = array();

  $licode = $apc->fnc_proc_prc_packages( ORACLE_OBJECT_OWNER, $packages, $ls_err );

  $LS_SUBMIT_CODE = "sbmt";
  $LS_PAC_CODE = "pack";
  $LS_PRC_CODE = "proc";
  $LS_NO_PACK_CODE = "-1";
  $LS_CHOOSE_PROC_CODE = "-1";
  $LS_WRITE_FILE = "w";
  $LS_OVERWRITE_FILE = "ow";
  $LS_SHOW_CLASS = "sc";

  $LS_NAME = "The OraclePHPWizard";

  $selectedpack = '';
  $selectedproc = '';
  $show_class = false;
  $show_skeleton = false;
  $write_to_file = false;
  $overwrite_file = false;
  $ls_bg_proc_color = "";
  $makeclasserr = "";
  $filwriteerr = "";
  $chk_show_class = "";
  $chkwrite_to_file = "";

  if ( isset($_POST[ $LS_PAC_CODE ] ) && $_POST[ $LS_PAC_CODE ] != $LS_NO_PACK_CODE ) {
    $selectedpack = $_POST[ $LS_PAC_CODE ];
  }

  //$proc=$_POST[ $LS_PRC_CODE ];
  if ( isset($_POST[ $LS_SHOW_CLASS ] ) && $_POST[ $LS_SHOW_CLASS ] == $LS_SHOW_CLASS ) {
    $show_class = true;
    $chk_show_class = ' checked';
  }

  if ( isset($_POST[ $LS_WRITE_FILE ] ) && $_POST[ $LS_WRITE_FILE ] == $LS_WRITE_FILE ) {
    $write_to_file = true;
    $chkwrite_to_file = ' checked';
  }

  if ( isset($_POST[ $LS_OVERWRITE_FILE ] ) && $_POST[ $LS_OVERWRITE_FILE ] == $LS_OVERWRITE_FILE ) {
    $overwrite_file = true;
  }

  if ( ! isset($_POST[ $LS_SUBMIT_CODE ] ) || empty($_POST[ $LS_SUBMIT_CODE ] ) ) {
    // Not submit
    $show_class = false;
    $write_to_file = false;
  } else {

    if ( ! isset( $_POST[ $LS_PRC_CODE ] ) || $_POST[ $LS_PRC_CODE ] == $LS_CHOOSE_PROC_CODE ) {
      $ls_bg_proc_color = ' style="background-color: yellow;"';
    } else {
      // do it
      $selectedproc =  $_POST[ $LS_PRC_CODE ];

      $cm = new Codemaker();

      $php_class_code = $cm->make_class( $apc, ORACLE_OBJECT_OWNER, $selectedpack, $selectedproc, false );
      $php_class_code_screen = htmlentities( $php_class_code, ENT_QUOTES );

      if ( ! $cm->get_init_status() ) {
        //fail
        $makeclasserr = $php_class_code_screen; //  cointains error
        $show_class = false; // dont try to show class, isn't created
      } else {

        if ( $write_to_file ) {
          if ( $selectedpack ) {
            $fname = $selectedpack . '.' . $selectedproc . '.php';
          } else {
            $fname = $selectedproc . '.php';
          }

          $writefilecode = $cm->write_to_file( REPO, $fname, $php_class_code, $overwrite_file );

          if ( $writefilecode != 0 ) {
            $show_class = true; // Show code on screen since it wasn't possible to write file
            $filwriteerr = parse_savecodes( $writefilecode );
          }
        }

        $show_skeleton = true;
        $skeleton = htmlentities( $cm->make_skeleton(), ENT_QUOTES );
      }
    }
  }

  // Get list of procedures for the dropdown§
  $lv_input = array( ORACLE_OBJECT_OWNER, $selectedpack );
  $licode = $apc->fnc_proc_prc_procs( $lv_input, $procedures, $ls_err );

?><!DOCTYPE html>
<html lang="<?php echo $lang ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $LS_NAME ?></title>
    <?php echo PRISMA_CSS ?>

    <style type="text/css">
    body {background-color: #ffffff;color: #000000;margin: 90px 10px 10px 10px;  font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;}
    h1 {font-family: inherit;font-size: 20px;font-weight: normal;}
    h2 {font-family: inherit;font-size: 16px;font-weight: normal; margin-top:20px;}
    input {background-color: #ffffff;color: #000000;margin: 0px;font-family: Verdana, Arial, Helvetica, sans-serif;  font-size: inherit;border-top: 1px solid #d3d3d3;border-bottom: 1px solid #d3d3d3;border-right: 1px solid #d3d3d3;  border-left: 1px solid #d3d3d3;}
    input:hover, select:hover, input:focus, select:focus {background-color: #d3d3d3;}
    select {background-color: #ffffff;color: #000000;margin: 0px;font-family: Verdana, Arial, Helvetica, sans-serif;  font-size: inherit;border-top: 1px solid #d3d3d3;border-bottom: 1px solid #d3d3d3;border-right: 1px solid #d3d3d3;  border-left: 1px solid #d3d3d3;}
    option {background-color: #ffffff;color: #000000;margin: 0px;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: inherit;  border-top: 0px none;border-bottom: 1px solid #d3d3d3;border-right: 1px solid #d3d3d3;border-left: 1px solid #d3d3d3;}
    a {color: #000000;}
    #frm {overflow: hidden;position: fixed;top: 0;}
    .errormsg {padding: 10px;font-weight: bold;color: #ff5500;border: 1px solid #d3d3d3;}
    #div_expl {border: 1px solid #d3d3d3;padding-left: 7px; margin-top: 7px; margin-bottom: 7px;}
    #div_buttons {border: 1px solid #d3d3d3;background-color:white;}
    #table_buttons {margin-top: 7px; margin-bottom: 7px;}
    </style>

  </head>
  <body>
    <form id="frm" method="post" action="opw.php">
      <div id="div_buttons">
        <table id="table_buttons">
          <tr>
            <td><?php echo LS_INPUTDES_PACK ?></td>
            <td>
              <select id="<?php echo $LS_PAC_CODE?>" name="<?php echo $LS_PAC_CODE?>" onchange="submitfrm();">
                <option value="<?php echo $LS_NO_PACK_CODE ?>"><?php echo LS_NO_PACK_DES ?></option>
<?php
  while ( $pck = oci_fetch_assoc ( $packages ) ) {
    if ( $selectedpack == $pck[ 'OBJECT_NAME' ] ) {
      $selected = ' selected';
    } else {
        $selected = '';
    }

    echo'<option' . $selected . ' value="' . $pck[ 'OBJECT_NAME' ] . '">' . $pck[ 'OBJECT_NAME' ] . '</option>';
  }
?>              </select>
          </td>
          <td><?php echo LS_INPUTDES_FILE ?></td>
          <td><input<?php echo $chkwrite_to_file ?> type="checkbox" id="<?php echo $LS_WRITE_FILE ?>" name="<?php echo $LS_WRITE_FILE ?>" value="<?php echo $LS_WRITE_FILE ?>"/></td>
          <td><?php echo LS_INPUTDES_SCREEN ?></td>
          <td><input<?php echo $chk_show_class ?> type="checkbox" id="<?php echo $LS_SHOW_CLASS ?>" name="<?php echo $LS_SHOW_CLASS ?>" value="<?php echo $LS_SHOW_CLASS ?>"/></td>
        </tr>
        <tr>
          <td<?php echo $ls_bg_proc_color?>><?php echo LS_INPUTDES_PROC ?></td>
          <td<?php echo $ls_bg_proc_color?>>
            <select id="<?php echo $LS_PRC_CODE?>" name="<?php echo $LS_PRC_CODE?>">
              <option value="<?php echo $LS_CHOOSE_PROC_CODE ?>"><?php echo LS_CHOOSE_PROC_DES ?></option>
<?php
  while ( $prc = oci_fetch_assoc ( $procedures ) ) {
    if ( ! empty( $prc[ 'PNAME' ] ) ) {
      if ( $selectedproc == $prc[ 'PNAME' ] ) {
        $selected = ' selected';
      } else {
          $selected = '';
      }
      echo '<option' . $selected . ' value="' . $prc[ 'PNAME' ] . '">' . $prc[ 'PNAME' ] . '</option>' . NL;
    }
  }
?>           </select>
          </td>
          <td><?php echo LS_INPUTDES_OWRW ?></td>
          <td><input type="checkbox" id="<?php echo $LS_OVERWRITE_FILE ?>" name="<?php echo $LS_OVERWRITE_FILE ?>" value="<?php echo $LS_OVERWRITE_FILE ?>"/></td>
          <td colspan="2" style="text-align:right;"><input type="submit" id="<?php echo $LS_SUBMIT_CODE ?>" name="<?php echo $LS_SUBMIT_CODE ?>" value="<?php echo LS_SUBMIT_DES ?>"/></td>
        </tr>
      </table>
    </div>
  </form>
<?php
  if ( $makeclasserr ) {
    echo "<div class='errormsg'>$makeclasserr</div>";
  }

  if ( $filwriteerr ) {
    echo "<div class='errormsg'>$filwriteerr</div>";
  }

  if ( $show_skeleton ) {
    echo '<h2>' . LS_TITLE_SKELETON . '</h2>';
    echo '<pre><code class="language-php">'.$skeleton.'</code></pre>';
  }

  if ( $show_class ) {
    echo '<h2>' . LS_TITLE_CLASS . '</h2>';
    echo '<pre><code class="language-php">&lt;?php'.NL.BLANKLINE.$php_class_code_screen.'?&gt;</code></pre>';
  }

  if ( ! $show_skeleton && ! $show_class ) {
    echo '<div id=\'div_expl\'><h1>' . LS_TITLE_MAIN . '</h1>';
    echo '<h2>' . LS_TITLE_HELP . '</h2>';
    echo '<ul>';
    echo '<li>' . LS_INPUTDES_PACK . '<br/>' . LS_INPUTDES_PACK_DES . '<br/></li>';
    echo '<li>' . LS_INPUTDES_PROC . '<br/>' . LS_INPUTDES_PROC_DES . '<br/></li>';
    echo '<li>' . LS_INPUTDES_FILE . '<br/>' . LS_INPUTDES_FILE_DES . '<br/></li>';
    echo '<li>' . LS_INPUTDES_OWRW . '<br/>' . LS_INPUTDES_OWRW_DES . '<br/></li>';
    echo '<li>' . LS_INPUTDES_SCREEN . '<br/>' . LS_INPUTDES_SCREEN_DES . '<br/></li>';
    echo '</ul></div>' . NL;
  }
?>
    <?php echo PRISMA_JS ?>
    <script>
      function submitfrm() {
        document.getElementById("frm").submit();
      }
    </script>
  </body>
</html>
<?php
}

function parse_savecodes( $code ) {
  switch ( $code ){
    case -10:
      $retval = LS_ERR_NOT_INIT;
      break;
    case -100:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_NO_FILENAME;
      break;
    case -110:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_DIR_NOT_SET;
      break;
    case -200:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_DIR_DOES_NOT_EXIST;
      break;
    case -210:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_DIR_READONLY;
      break;
    case -220:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_FILE_EXISTS;
      break;
    case -230:
      $retval = LS_ERR_CANT_WRITE_FILE . ': '. LS_ERR_FILE_EXISTS_READONLY;
      break;
    default:
      $retval = LS_ERR_CANT_WRITE_FILE;
    }

  return( $retval );
}
?>
