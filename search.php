<?php
/*
 * file: search.php
 *
 * Copyright 2003-2006 mbscholt at aquariusoft.org
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor Boston, MA 02110-1301,  USA
 */

$lastmodified = '2006-10-23';
$page_version = '0.5.01';
$dateofcreation = '2005-01-11';

$section_name = 'home';
$page_name = 'search';

include 'inc/inc_init.php';

addToLog( $skel, $section_name, $page_name, $page_version );

$page_body = "<h1>Search</h1>\n";

$searchkey = getRequestParam('searchkey', '');;
if ('' != $searchkey)
{
	$searchkey = mysql_real_escape_string($searchkey);
}

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

$page_body .= "<h2>webmarks</h2>\n";
if ($searched_webmarks != null)
{
	$page_body .= buildMarks($searched_webmarks);
} else
{
	$page_body .= "<p>No matching entries found</p>\n";
}

include 'inc/inc_pagetemplate.php';
?>
