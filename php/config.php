<?php
define( "DB_USER", "dbopw" );
define( "DB_PASSWORD", "dbopw" );
define( "DB_CONNSTR", "localhost/XE" );

// The owner of packages and procedures
define( "ORACLE_OBJECT_OWNER", DB_USER );

// Path to save files to
define( "REPO", "/var/www/html/rw" );

define( "COMPANY", "Me!" );

// Include prisma, nicifier for code on web pages
// Download prisma from http://prismjs.com/download.html
// You only need support for PHP
define( "PRISMA_CSS", '<link href="prism.css" rel="stylesheet" />' );
define( "PRISMA_JS", '<script src="prism.js"></script>' );

// Path and filename to definitions of default sizes for oracle types
// Remove this line if you want to include the file elsewhere
define( "DEFSIZELOCATION", "defsizes.php" );

// Prefix for oracle types default size constants
// if this is changed all constant names must be chanchged as well
define( "ORATYPESIZEPREFIX", "SIZE_" );

define ( "FILENAME_LCASE", true );
define ( "CLASSNAME_INITCAP", true );

// To change indentation alter value for IND1
define( "IND1", "  " );
define( "IND2", IND1 . IND1 );
define( "IND3", IND1 . IND2 );

// Newline and blankline constants for class
define( "BLANKLINE", "\n");
define( "NL", "\n");

// Newline and blankline constants for skeleton
define( "SBLANKLINE", "\n");
define( "SNL", "\n");
?>
