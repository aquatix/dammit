<?php
/**
 * Initialization module
 * 
 * Copyright 2003-2013 mbscholt at aquariusoft.org
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

$skel['base_dir'] = dirname(dirname(__FILE__)); // go up one directory, out of the modules dir

/* Load the settings for this web log */
if (!file_exists('config.inc.php'))
{
	echo "Copy config.inc.sample.php to config.inc.php and configure your weblog settings";
	exit();
}
include 'config.inc.php';

if (!isset($skel['restricttoip']))
{
	$skel['restricttoip'] = '';
}

if (isset($skel['testing']) && true === $skel['testing'])
{
	//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
	//error_reporting( 0 );
	error_reporting( E_ALL );
//	print_r($_REQUEST);
} else
{
	error_reporting( 0 );
}



/*
$skel['servername'] = 'dammit.nl';
$skel['baseHref'] = '/';
$skel['basePath'] = '/var/local/www/dammit.nl/';
if (true == $skel['testing'])
{
	$skel['baseHref'] = '/';
	$skel['basePath'] = '/var/local/www/delta.dammit.nl/';
}

baseHref -> base_uri
servername -> base_server (now including protocol)
basePath -> base_dir
*/


$skel['base_uri'] = dirname($_SERVER['PHP_SELF']) . '/';
if ('//' == $skel['base_uri'])
{
	/* Site is located in the root, compensate for the extra slash */
	$skel['base_uri'] = '/';
}
if (isset($skel['base_uri_mask']))
{
	//$skel['base_uri'] = substr($skel['base_uri'], strlen($skel['base_uri_mask']));
	$skel['base_uri'] = str_replace($skel['base_uri_mask'], '', $skel['base_uri']);
}

$url_pieces = parse_url(getenv('SCRIPT_URI'));
$skel['base_server'] = '';
if (!isset($url_pieces['scheme']))
{
	//$url_pieces = parse_url($_SERVER['SCRIPT_URI']);
	$skel['base_server'] = 'http://' . $_SERVER['SERVER_NAME'];
} else
{
	$skel['base_server'] = $url_pieces['scheme'] . '://' . $url_pieces['host'];
}

/*
 * Real path to rdf file [path used on server, like /var/www/blog.rdf]
 * Files should be writable for the webapp [chmod o+rw <filename>]
 */
$skel['rssFilename'] = $skel['base_dir'] . 'blog.rdf';
$skel['rssWithCommentsFilename'] = $skel['base_dir'] . 'blog_comments.rdf';
$skel['rssMarksFilename'] = $skel['base_dir'] . 'marks.rdf';


/*** Path used for sending mail ***/
$skel['mailPath'] = '/usr/sbin/sendmail -t';
$skel['mailFrom'] = $skel['mainEmail'];
$skel['mailFromName'] = $skel['siteName'];
$skel['mailTo'] = $skel['mainEmail'];
/* Notification for weblog owner */
$skel['mailSubject'] = '[' . $skel['siteName'] . '] New comment';
/* Notification for other posters */
$skel['mailNotificationSubject'] = '[' . $skel['siteName'] . '] New comment posted';


if (!isset($skel['globalmessage']))
{
	/* Message to be shown on the main page, archive page and individual posting pages */
	//$skel['globalmessage'] = 'This weblog is getting tweaked';
	$skel['globalmessage'] = null; /* Use when you don't want such a message */
}

/* RSS feeds by default have 10 items in them [standardized] */
$skel['nrOfItemsInFeed'] = 10;


/*** Session identification ***/
$skel['session_name'] = 'WEBLOGSESSID';

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

$skel['isloggedin'] = isLoggedIn();

/* Theme */
include 'themes/' . $skel['theme'] . '/config.inc.php';
include 'themes/' . $skel['theme'] . '/templates.php';
include 'themes/' . $skel['theme'] . '/pagetemplate.php';
?>
