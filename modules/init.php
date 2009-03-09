<?php
/**
 * Initialization module
 * $Id$
 * 
 * Copyright 2003-2009 mbscholt at aquariusoft.org
 *
 * simplog is the legal property of its developer, Michiel Scholten
 * [mbscholt at aquariusoft.org]
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

$skel['starttime'] = microtime();

/* Load the settings for this web log */
include 'config.php';

if (!isset($skel['restricttoip']))
{
	$skel['restricttoip'] = '';
}

if (true == $skel['testing'])
{
	//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
	//error_reporting( 0 );
	error_reporting( E_ALL );
//	print_r($_REQUEST);
} else
{
	error_reporting( 0 );
}

/* RSS feeds by default have 10 items in them [standardized] */
$skel['nrOfItemsInFeed'] = 10;


// session check
if (isset($_REQUEST[$skel['session_name']]))
{
	/* root has already logged in, resume session */
	session_name($skel['session_name']);
	session_start();
}


/****** Create DB connection ******/
$skel['dbLink'] = mysql_connect($db_url, $db_user, $db_pass)
or die("Could not connect to database!\n");

if ( !$skel['dbLink'] )
{
	echo 'ERROR: Didn\'t Connect to DB!';
} else
{
	mysql_select_db($db_name, $skel['dbLink']);
}


/****** Add to log ******/
/*
   if (false == $skel['testing'])
   {
   $ipaddr = getenv('REMOTE_ADDR');
   $time = date('Y-m-d G:i:s', time());

   $pagename2log = $page_name;
   if (isset($page_log))
   {
// Alternate exists with more info, use this one
$pagename2log = $page_log;
}

$user_id = 0;

$query = 'INSERT INTO Log ' .
'SET Date='' . $time . '', Ip='' . $ipaddr . '', Client='' . getenv('HTTP_USER_AGENT') . 
'', Referer='' . getenv('HTTP_REFERER') .
'', Section='' . $section_name . '', Page='' . $pagename2log . '', PageVersion='' .
$page_version . '', UserId='' . $user_id . '';'; 

$result = mysql_query($query, $skel['dbLink']);
}
 */

/****** Include the underlying methods ******/
include 'modules/toolkit_html.php';
include 'modules/toolkit_methods.php';
include 'modules/blog_html.php';
include 'modules/blog_methods.php';
include 'modules/log_html.php';
include 'modules/log_methods.php';
include 'modules/pagetemplate.php';
?>
