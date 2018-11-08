# ORA-06512
You get an error that looks similar to this
```
6502 ORA-06502: PL/SQL: numeric or value error ORA-06512: at "MYDB.PCK_TROUBLES", line 6 ORA-06512: at line 1
```

Most likely the memory allocation for oci_bind_by_name is too small.

Look at the table you are getting data from and pay attention to the sizes. If anyone that you select into an OUT parameter is bigger than the size for that type declared in defsizes.php, it will trigger an ORA-0615.

So you can either change the default size in defsizes.php
```
define( "SIZE_ORATYPE_VARCHAR2" , 128 );
```
or make a change only for the file that causes the error (probably a better idea). You do this after creating the instance and before executing the procedure, like so:
```
$proc = new Pck_troubles__prc_trouble( $dbconn, $inargs );

$proc->lenAVO_TEXT = 4000;

if ( ! $proc->exec() ) {
...
```

You'll find all the output size variables in the class declaration
