# Oracle PHP Wizard
A Web-Based tool that creates the php code needed to connect to oracle procedures

## For who and why
This is for you who write php pages connecting to oracle stored procedures and are sick of the tedius work writing the connections.
Oracle PHP Wizard let you concentrate on the fun stuff!

## How
Say you have this procedure
```
  procedure prc_get_chapters(ahid in number,
                             ahc in out number,
                             aheader out varchar2,
                             aheader2 out varchar2,
                             achapters out sys_refcursor);
```
On the OPW web page you select the package (if applicable) and procedure and OPW creates a class for you and a code snippet on how to use it, like this:
```
  require_once( "pck_opw_test.prc_get_chapters.php" );

  $inargs[ 'AHID' ] = $value; // ORATYPE_NUMBER
  $inargs[ 'AHC' ] = $value; // ORATYPE_NUMBER

  $proc = new Pck_opw_test__prc_get_chapters( $dbconn, $inargs );

  if ( ! $proc->exec() ) {
    echo 'Fail: ' . $proc->get_errcode() . ' ' . $proc->get_errmsg();
  } else {

    echo $inargs[ 'AHC' ]; // ORATYPE_NUMBER (IN/OUT)
    echo $proc->get_output( 'AHEADER' ); // ORATYPE_VARCHAR2
    echo $proc->get_output( 'AHEADER2' ); // ORATYPE_VARCHAR2

    while ( $row = $proc->get_curs( 'ACHAPTERS' ) ) {
      print_r( $row ); // $row[ $argname ]
    }

    $proc->free_curs();

  }
```
And thats that :)

