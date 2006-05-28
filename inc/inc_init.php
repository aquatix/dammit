<?php
/*
 * Initialization
 * Version: 0.4.01 2006-04-28
 */

/* Load the settings for this web log */
include "inc/inc_config.php";

if (true == $skel["testing"])
{
	//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
	//error_reporting( 0 );
	error_reporting( E_ALL );
} else
{
	//error_reporting( 0 );
}

/* RSS feeds by default have 10 items in them [standardized] */
$skel["nrOfItemsInFeed"] = 10;


// session check
if (isset($_REQUEST[$skel["session_name"]]))
{
	/* root has already logged in, resume session */
	session_name($skel["session_name"]);
	session_start();
}


/****** Create DB connection ******/
$skel["dbLink"] = mysql_connect($db_url, $db_user, $db_pass)
or die("Could not connect to database!\n");

if ( !$skel["dbLink"] )
{
	echo "ERROR: Didn't Connect to DB!";
} else
{
	mysql_select_db($db_name, $skel["dbLink"]);
}


/****** Add to log ******/
/*
   if (false == $skel["testing"])
   {
   $ipaddr = getenv("REMOTE_ADDR");
   $time = date("Y-m-d G:i:s", time());

   $pagename2log = $page_name;
   if (isset($page_log))
   {
// Alternate exists with more info, use this one
$pagename2log = $page_log;
}

$user_id = 0;

$query = 'INSERT INTO Log ' .
'SET Date="' . $time . '", Ip="' . $ipaddr . '", Client="' . getenv("HTTP_USER_AGENT") . 
'", Referer="' . getenv("HTTP_REFERER") .
'", Section="' . $section_name . '", Page="' . $pagename2log . '", PageVersion="' .
$page_version . '", UserId="' . $user_id . '";'; 

$result = mysql_query($query, $skel["dbLink"]);
}
 */

/****** Include the underlying methods ******/
include "inc/inc_methods.php";
include "inc/inc_html.php";
?>
