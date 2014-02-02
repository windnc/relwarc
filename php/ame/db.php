#!/usr/bin/php
<?
require_once "../src/usqlite3.php";
require_once "./setting.php";

// DB 준비
function prepare_db( $label )
{
	system( "mkdir -p out/page out/entry out/img" );
	$db = new USQLite3();
	$db->OpenDB( "out/$label.sqlite3" );
	$query = 	"CREATE TABLE $label ( " .
					"id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ".
					"eid INT, ".
					"label TEXT, ".
					"code TEXT, ".
					"stamp INT, ".
					"enable INT DEFAULT 1, ".
					"status INT DEFAuLT 0, ".
					"retry INT DEFAULT 0, ".
					"url TEXT, ".
					"title TEXT, ".
					"content TEXT, ".
					"thumb BLOB, ".
					"img1 BLOB, ".
					"img2 BLOB, ".
					"img3 BLOB, ".
					"img4 BLOB, ".
					"img5 BLOB, ".
					"file1 BLOB, ".
					"file2 BLOB, ".
					"file3 BLOB, ".
					"file4 BLOB, ".
					"file5 BLOB ".
				")";
	$ret = @$db->exec( $query );
	if( !$ret ) return false;

	return true;
}


/*
$query = "INSERT INTO $label (EID) VALUES ('3');";
$ret = $db->exec( $query );
if( $ret ) { echo "ok"; }
else { echo "no"; }

$query = "SELECT * from $label";
$ret = $db->query( $query );
while( $row = $ret->fetchArray(SQLITE3_BOTH) ) {
	echo "ID = " . $row['ID']. "\n";
}


$query = "UPDATE $label SET EID = 33 WHERE ID=2";
$ret = $db->exec( $query );
if( $ret ) { echo "ok"; }
else { echo "no"; }

*/


?>
