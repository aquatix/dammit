<?php
/*
 * $Id$
 *
 * Copyright 2003-2008 mbscholt at aquariusoft.org
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

$skel['lastmodified'] = '2008-12-28';
$skel['page_version'] = '0.6.01';
$skel['dateofcreation'] = '2005-01-11';

$section_name = 'home';
$page_name = 'search';

include 'modules/init.php';

addToLog( $skel, $section_name, $page_name, $page_version );

$page_body = "<h1>Search</h1>\n";

$searchkey = getRequestParam('searchkey', '');;
if ('' != $searchkey)
{
	$searchkey = mysql_real_escape_string($searchkey);

	$searched_weblogentries = findRants($skel, $searchkey);
	$searched_webmarks = findMarks($skel, $searchkey);

	$page_body .= '<p>Searched for "' . $searchkey . "\"</p>\n";

	$page_body .= "<h2>weblog entries</h2>\n";
	if ($searched_weblogentries != null)
	{
		$page_body .= buildRantlist($searched_weblogentries, true);
	} else
	{
		$page_body .= "<p>No matching posts found</p>\n";
	}

	$page_body .= "<h2>blogmarks</h2>\n";
	if ($searched_webmarks != null)
	{
		$page_body .= buildMarks($searched_webmarks);
	} else
	{
		$page_body .= "<p>No matching entries found</p>\n";
	}
} else
{
	$page_body .= "<h2>sorry</h2>\n";
	$page_body .= "<p>You will have to enter a text to search on</p>\n";
}

echo buildPage($skel, $section_name, $page_name, $page_body)
?>
