<?php 

session_start();

require( "memory.php" );
getglobalvar(1); // draw $STORED Variables...
$sql = new MySQL;
$sql->Connect( $CONFIG_sql_host, $CONFIG_sql_username, $CONFIG_sql_password );

// save the SQL Link in SG CP's global var... so the Querys should work
$GLOBALS['link'] = $sql->link;

$site = $_GET['site'];
$link = unserialize( VOTE_LINK );

if( !isset( $site ) || !isset( $link[ $site ] ) ){
	header( 'Location: index.php' );
} else if( !isset( $STORED_loginname ) ){
	votes();
} else {
	$sql->result = $sql->execute_query( "SELECT `last_vote".$site."` FROM $CONFIG_sql_dbname.`vote_point` WHERE `loginname` = '".$STORED_loginname."' LIMIT 0,1", "vote.php" );
	if( $sql->count_rows() > 0 ) {
		$row = $sql->fetch_row();

		if( ( time() - $row[ 0 ] ) > (60 * 60 * VOTE_TIME) )
			$sql->execute_query("UPDATE $CONFIG_sql_dbname.`vote_point` SET `point` = `point` + 1 , `last_vote".$site."` = '".time()."', `date` = '".date("d-M-Y H:i")."' WHERE `loginname` = '".$STORED_loginname."'", "vote.php");

		votes();
	} else {
		$sql->execute_query("INSERT INTO $CONFIG_sql_dbname.`vote_point` ( `loginname` , `point` , `last_vote".$site."` , `date` ) VALUES ( '".$STORED_loginname."' , 1 , '".time()."' , '".date("d-M-Y H:i")."')", "vote.php");
		votes();
	}
}



function votes() {
	global $site, $link;

	if( isset( $link[ $site ] ) ){
		header( 'Location: '.$link[ $site ] ); 
	} else
		header( 'Location: index.php' );
	die();
}



?>
