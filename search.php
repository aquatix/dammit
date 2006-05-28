<?php
	$lastmodified = "2005-04-27";
	$page_version = "0.3.04";
	$dateofcreation = "2005-01-11";
	
	$section_name = "home";
	$page_name = "search";

	include "inc/inc_init.php";

	addToLog( $skel, $section_name, $page_name, $page_version );

	$page_body = "<h1>Search</h1>\n";

	$searchkey = "";
	if (isset($_REQUEST['searchkey']))
	{
		//$searchkey = mysql_escape_string($_POST['searchkey']);
		$searchkey = mysql_escape_string($_REQUEST['searchkey']);
	}

//	$page_body .= "<form action=\"search.php\" method=\"post\"><input type=\"text\" name=\"searchkey\" size=\"12\" maxlength=\"250\"/><input name=\"searchbtn\" value=\"Find\" type=\"submit\"/></form>\n";

	$searched_weblogentries = findRants($skel, $searchkey);
	$searched_webmarks = findMarks($skel, $searchkey);

	$page_body .= "<p>Searched on \"" . $searchkey . "\"</p>\n";

	$page_body .= "<h2>weblog entries</h2>\n";
	if ($searched_weblogentries != null)
	{
		$page_body .= buildRantlist($searched_weblogentries, true);
	} else
	{
		$page_body .= "<p>No matching posts found</p>\n";
	}

	$page_body .= "<h2>webmarks</h2>\n";
	if ($searched_webmarks != null)
	{
		$page_body .= buildCondensedMarks($searched_webmarks);
	} else
	{
		$page_body .= "<p>No matching entries found</p>\n";
	}

	include "inc/inc_pagetemplate.php";
?>
