<?php
/*
 * Methods for Michiel's rantbox
 * Version: 0.3.01 2005-01-11
 */


/*** Load modules ***/
include "modules/mod_toolkit_methods.php";
include "modules/mod_blog_methods.php";
include "modules/mod_log_methods.php";


/*
 * Formats normal datetime sting $datetime to an xml datetime format, a.o. used for RSS
 */
function getRSSDateTime($datetime)
{
	/* Make something like "2003-12-22T20:00:00+01:00" from "2003-12-22 20:00:00" */
	$dateAndTime = explode(" ", $datetime);	//split date and time
	return $dateAndTime[0] . "T" . $dateAndTime[1] . "+01:00";
}

?>
