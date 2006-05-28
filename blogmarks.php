<?php
	$lastmodified = "2005-09-16";
	$page_version = "0.3.09";
	$dateofcreation = "2003-12-21";
	
	$section_name = "blogmarks";
	$page_name = "home";
	$subpage = "";

	include "inc/inc_init.php";

	if (isset($_GET['page']))
	{
		$subpage = $_GET['page'];
		$page_name = $page_name . "." . $subpage;
	}

	addToLog( $skel, $section_name, $page_name, $page_version );

	$page_body = "";

	$page_name = "blogmarks";

	$year = date("Y");	/* Default to current year */
	$month = date("m");	/* Default to current month */

	$months = array();

	if (isset($_GET['year']) && myIsInt($_GET['year']))
	{
		$year = $_GET['year'];

		$months = getMarkMonths( $skel, $year );

		if (isset($_GET['month']) && myIsInt($_GET['month']))
		{
			$month = $_GET['month'];
		} else
		{
			$month = max($months);
		}
	} else
	{
		/* determine last month blogmarks where posted */
		$months = getMarkMonths( $skel, $year );
		$month = 1;
		if ($months != null)
		{
			/* Only do the max() if there are months with blogmarks this year */
			$month = max($months);
		}
	}

	/*** Show the blogmark overview ***/
	$page_body .= "<h1>Blogmarks</h1>\n<h2>Interesting stuff I came across</h2>\n<p>This section contains my blogmarks: links to interesting sites I stumbled apon, or pictures I liked.</p>\n";
	$offset = 0;
	$nrBack = 0;
	$nrForward = $skel["nrOfMarksPerPage"];
	$nrOfMarks = getNrOfMarks($skel);

	$browse_nav = "<div class=\"mininav\">[ <span class=\"heading\">" . $year . "</span>";
	for ($i = 0; $i < count($months); $i++)
	{
		if ($months[$i] == $month)
		{
			$browse_nav .= " | " . $months[$i];
		} else
		{
			$browse_nav .= " | <a href=\"blogmarks.php?year=" . $year . "&amp;month=" . $months[$i] . "\">" . $months[$i] . "</a>";
		}
	}
	$browse_nav .= " ]</div>\n";

	/* Show the nav */
	$page_body .= $browse_nav;
	/* Show the blogmarks */
	$page_body .= buildCondensedMarks(getMarksByMonth($skel, $year, $month));

	$page_body .= "<h2>Blogmarks by month</h2>\n";
	$page_body .= "<h3>" . $year . "</h3>\n";
	$page_body .= "<ul>\n";

	$nrOfMarksByMonth = getMarksPerMonth( $skel, $year );
	for ($i = 0; $i < count($nrOfMarksByMonth); $i++)
	{
		$page_body .= "<li><a href=\"blogmarks.php?year=" . $year . "&amp;month=" . $nrOfMarksByMonth[$i]["month"] . "\">" . getMonthName($nrOfMarksByMonth[$i]["month"]) . "</a> (" . $nrOfMarksByMonth[$i]["nrofpostings"] . ")</li>\n";
	}
	$page_body .= "</ul>\n";


	$page_body .= "<h3>All years</h3>\n";
	$page_body .= "<ul>\n";
	$nrOfMarksByYear = getMarksPerYear( $skel );
	for ($i = 0; $i < count($nrOfMarksByYear); $i++)
	{
		$page_body .= "<li><a href=\"blogmarks.php?year=" . $nrOfMarksByYear[$i]["year"] . "\">" . $nrOfMarksByYear[$i]["year"] . "</a> (" . $nrOfMarksByYear[$i]["nrofpostings"] . ")</li>\n";
	}
	$page_body .= "</ul>\n";

	include "inc/inc_pagetemplate.php";
?>
