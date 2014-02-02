<?
require_once "db.php";

class BoardEntry {
	var $org_str;
	var $url;
	var $id;
	var $base_url;
	var $label;
	var $title;
	var $thumb_url;
	var $board_code;
	var $stamp;

	var $url_begin1 = '<a href="';
	var $url_end1 = '"';
	var $thumb_begin1 = '<img src="';
	var $thumb_end1 = '"';
	var $title_begin1 = '<span>';
	var $title_end1 = '</span>';

	var $content_begin1 = "<div id=view_content>";
	var $content_end1 = "</div>";

	var $date_begin1 = "<span class=mw_basic_view_datetime>";
	var $date_end1 = "</span>";

	function __construct( $str, $curr_url, $base_url, $label, $board_code ) {
		$this->org_str = $str;
		$this->label = $label;
		$this->board_code = $board_code;
		$this->base_url = $base_url;

		$pos1 = strpos( $str, $this->url_begin1 );
		$pos2 = strpos( $str, $this->url_end1, $pos1 + strlen( $this->url_begin1 ) );
		$this->url = $curr_url . substr( $str, $pos1+strlen($this->url_begin1), $pos2-($pos1+strlen($this->url_begin1)) );
		//print( $pos1 . " " . $pos2 . "  " . $this->url. "\n" );

		$pos1 = strpos( $str, $this->thumb_begin1 );
		$pos2 = strpos( $str, $this->thumb_end1, $pos1 + strlen( $this->thumb_begin1 ) );
		$this->thumb_url = $curr_url .  substr( $str, $pos1+strlen($this->thumb_begin1), $pos2-($pos1+strlen($this->thumb_begin1)) );
		//print( $pos1 . " " . $pos2 . "  " . $this->thumb_url. "\n" );

		$pos1 = strpos( $str, $this->title_begin1 );
		$pos2 = strpos( $str, $this->title_end1, $pos1 + strlen( $this->title_begin1 ) );
		$this->title = substr( $str, $pos1+strlen($this->title_begin1), $pos2-($pos1+strlen($this->title_begin1)) );
		//print( $pos1 . " " . $pos2 . "  " . $this->title. "\n" );

		// id
		$pos1 = strrpos( $this->thumb_url, "/" );
		$this->id = substr( $this->thumb_url, $pos1+1 );
	}

	function GetContent( $html ) {
		$pos1 = strpos( $html, $this->content_begin1 );
		$pos2 = strpos( $html, $this->content_end1, $pos1 + strlen( $this->content_begin1 ) );
		$content = substr( $html, $pos1+strlen($this->content_begin1), $pos2-($pos1+strlen($this->content_begin1)) );
		$content = str_replace( "../data/file", $this->base_url. "/data/file", $content );
		$this->content = $content;

		// date
		$pos1 = strpos( $html, $this->date_begin1 );
		$pos2 = strpos( $html, $this->date_end1, $pos1 + strlen( $this->date_begin1 ) );
		$date = substr( $html, $pos1+strlen($this->date_begin1), $pos2-($pos1+strlen($this->date_begin1)) );
		$pos1 = strpos( $date, "(" );
		$pos2 = strpos( $date, ")", $pos1+1 );
		$date = substr( $date, 0, $pos1 ) .  substr( $date, $pos2+1 );
		$this->stamp = strtotime( $date );
		if( $this->stamp === false ) {
			//print( $pos1 . " " . $pos2 . "  " . $date . " FAIL!\n" );
		}
		else {
			//print( $pos1 . " " . $pos2 . "  " . $date . " " . $this->stamp. "\n" );
		}
	}


	function PushDB()
	{
		$db = new USQLite3();
		$db->OpenDB( "out/$this->label.sqlite3" );
		$query = "SELECT * FROM $this->label WHERE label = '$this->label' and code = '$this->board_code' and eid = '$this->id' ";
		//echo $query. "\n";
		$ret = $db->query( $query );
		$cnt = 0;
		while( $row = $ret->fetchArray( SQLITE3_ASSOC )  ) {
			$cnt++;
		}

		if( $cnt > 0 )	return false;

		$title = SQLite3::escapeString( $this->title );
		$content = trim( $this->content );
		$content = SQLite3::escapeString( $content );
		$thumb = $this->thumb_url;
		$query = "INSERT INTO $this->label (eid, label, code, stamp, title, url, content, thumb) VALUES ('$this->id', '$this->label', '$this->board_code', '$this->stamp', '$title', '$this->url', '$content', '$thumb') ";
//		echo $query . "\n";
		$ret = $db->exec( $query );
		return $ret;
	}

}

class Board { 

	var $label;
	var $verbose = 1;
	var $entry_begin1 = '<div class="mw_basic_list_gall';
	var $entry_end1 = '</td>';

	var $last_page_begin1 = ">다음</a>";
	var $last_page_end1 = ">맨끝</a>";
	var $last_page_begin2 = "page=";
	var $last_page_end2 = "'";


	var $base_url;
	var $board_code;

	function __construct( $base_url, $board_code, $label ) {
		$this->base_url = $base_url;
		$this->board_code = $board_code;
		$this->label = $label;
	}

	function __destruct() {
	}

	function GenerateUrl( $page )
	{
		return $this->base_url . "/bbs/board.php?bo_table="  . $this->board_code . "&page=" . $page;
	}

	function GetEntryList( $html ) {

		$entry_arr = array();
		$tmp = $html;
		while( true ) {
			$pos1 = strpos( $tmp, $this->entry_begin1 );
			if( $pos1 === false )	break;

			$pos2 = strpos( $tmp, $this->entry_end1, $pos1 + strlen( $this->entry_begin1 ) );
			if( $pos2 === false )	break;

			$str = substr( $tmp, $pos1, $pos2-$pos1 );

			$entry = new BoardEntry( $str, $this->base_url."/bbs/", $this->base_url, $this->label, $this->board_code );
			$entry->str = $str;
			$entry_arr[] = $entry;

			$tmp = substr( $tmp, $pos2 + strlen( $this->entry_end1 ) );
		}

		return $entry_arr;

	}

	function GetLastPageNum( $html )
	{
		$pos1 = strpos( $html, $this->last_page_begin1 );
		$pos2 = strpos( $html, $this->last_page_end1, $pos1+strlen( $this->last_page_begin1 ) );
		$tmp = substr( $html, $pos1, $pos2-$pos1 );
		$pos1 = strpos( $tmp, $this->last_page_begin2 );
		$pos2 = strpos( $tmp, $this->last_page_end2, $pos1+strlen( $this->last_page_begin2 ) );
		$tmp = substr( $tmp, $pos1+strlen($this->last_page_begin2), $pos2-($pos1+strlen($this->last_page_begin2)) );
		return intval($tmp);
	}

} 

?>
