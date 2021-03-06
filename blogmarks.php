<?php
/**
 * Page listing public bookmarks posted on the weblog
 *
 * Copyright 2003-2013 michiel at aquariusoft.org
 *
 * simplog is the legal property of its developer, Michiel Scholten
 * [michiel at aquariusoft.org]
 * Please refer to the COPYRIGHT file distributed with this source distribution.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$skel['lastmodified'] = '2013-04-20';
$skel['page_version'] = '0.8.02';
$skel['dateofcreation'] = '2003-12-21';

$section_name = 'blogmarks';
$page_name = 'home';
$subpage = '';

include 'modules/init.php';

if (isset($_GET['page']))
{
	$subpage = $_GET['page'];
	$page_name = $page_name . '.' . $subpage;
}

addToLog( $skel, $section_name, $page_name, $skel['page_version'] );

$page_body = '';
$page_name = 'blogmarks';

$year = date('Y');	/* Default to current year */
$month = date('m');	/* Default to current month */

$months = array();

if (isset($_GET['year']) && myIsInt($_GET['year']))
{
	$year = $_GET['year'];

	$page_name .= ' ' . $year;

	$months = getMarkMonths( $skel, $year );

	if (isset($_GET['month']) && myIsInt($_GET['month']))
	{
		$month = $_GET['month'];
		$page_name .= '-' . $month;
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
$skel['page_title'] = 'Blogmarks';
$offset = 0;
$nrBack = 0;
$nrForward = $skel['nrOfMarksPerPage'];
$nrOfMarks = getNrOfMarks($skel);

$browse_nav = '<div class="mininav">[ <span class="heading">' . $year . '</span>';
for ($i = 0; $i < count($months); $i++)
{
	if ($months[$i] == $month)
	{
		$browse_nav .= ' | <span class="heading">' . $months[$i] . ' (' . getMonthName($months[$i]) . ')</span>';
	} else
	{
		//$browse_nav .= ' | <a href="blogmarks.php?year=' . $year . '&amp;month=' . $months[$i] . '">' . $months[$i] . '</a>';
		$browse_nav .= ' | <a href="' . $skel['base_uri'] . 'm/' . $year . '/' . $months[$i] . '">' . $months[$i] . '</a>';
	}
}
$browse_nav .= " ]</div>\n";

/* Show the nav */
$page_body .= $browse_nav;
/* Show the blogmarks */
$page_body .= buildMarks(getMarksByMonth($skel, $year, $month));

$page_body .= "<h2>Blogmarks by month</h2>\n";
$page_body .= "<h3>" . $year . "</h3>\n";
$page_body .= "<ul>\n";

$nrOfMarksByMonth = getMarksPerMonth( $skel, $year );
for ($i = 0; $i < count($nrOfMarksByMonth); $i++)
{
	//$page_body .= "<li><a href=\"blogmarks.php?year=" . $year . "&amp;month=" . $nrOfMarksByMonth[$i]["month"] . "\">" . getMonthName($nrOfMarksByMonth[$i]["month"]) . "</a> (" . $nrOfMarksByMonth[$i]["nrofpostings"] . ")</li>\n";
	$page_body .= "<li><a href=\"" . $skel['base_uri'] . "m/" . $year . "/" . $nrOfMarksByMonth[$i]["month"] . "\">" . getMonthName($nrOfMarksByMonth[$i]["month"]) . "</a> (" . $nrOfMarksByMonth[$i]["nrofpostings"] . ")</li>\n";
}
$page_body .= "</ul>\n";


$page_body .= "<h3>All years</h3>\n";
$page_body .= "<ul>\n";
$nrOfMarksByYear = getMarksPerYear( $skel );
for ($i = 0; $i < count($nrOfMarksByYear); $i++)
{
	//$page_body .= "<li><a href=\"blogmarks.php?year=" . $nrOfMarksByYear[$i]["year"] . "\">" . $nrOfMarksByYear[$i]["year"] . "</a> (" . $nrOfMarksByYear[$i]["nrofpostings"] . ")</li>\n";
	$page_body .= "<li><a href=\"" . $skel['base_uri'] . "m/" . $nrOfMarksByYear[$i]["year"] . "\">" . $nrOfMarksByYear[$i]["year"] . "</a> (" . $nrOfMarksByYear[$i]["nrofpostings"] . ")</li>\n";
}
$page_body .= "</ul>\n";

echo buildPage($skel, $section_name, $page_name, $page_body)
?>
