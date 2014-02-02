#!/usr/bin/php
<?
require_once "../src/curl.php";
require_once "./db.php";
require_once "./board.php";
require_once "./setting.php";

echo $label . "\n";
echo "BASE: " .$base_url . "\n";

// DB 준비
prepare_db( $label );



// 다음 로그인 URL 
$curl = new Curl(); 
$curl->Login( $base_url, $login_path, $login_data );

$board = new Board( $base_url, $board_code, $label );

$first_page_url = $board->GenerateUrl( 1 );
$result = $curl->GetUrl( $first_page_url );
$last_page_num = $board->GetLastPageNum( $result );

for( $p=1; $p<$last_page_num; $p++ ) {

	$page_url = $board->GenerateUrl( $p );
	echo $page_url;

	$html = $curl->GetUrl( $page_url );
	$entry_arr = $board->GetEntryList( $html );

	for( $e=0; $e<count($entry_arr); $e++ ) {

		$html = $curl->GetUrl( $entry_arr[$e]->url );
		$entry_arr[$e]->GetContent( $html );
		if( $entry_arr[$e]->PushDB() === false ) { 
			echo "Up-to-date\n";
			break;
		}
	}

	//echo "\n";
	if( $p ==1 ) break;
}

?>

