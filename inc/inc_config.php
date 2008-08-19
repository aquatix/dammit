<?php
/*
 * $Id$
 *
 * Configuration file for 'Michiel Scholten's blog aka rantbox'
 * Version: 0.5.07 2008-08-19
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


/*** Site settings ***/
$skel['testing'] = false;
$skel['testing'] = true;

/* Global comments enabled yes/no toggle */
//$skel['commentsenabled'] = true;
$skel['commentsenabled'] = false;

$skel['author'] = 'Michiel Scholten';
$skel['authorShortname'] = 'Michiel';
$skel['siteName'] = 'dammIT';
$skel['mainEmail'] = 'dammit@aquariusoft.org';
$skel['siteKeywords'] = 'Michiel Scholten,michiel,scholten,web,log,weblog,blog,rantbox,rant,rants,frustrations,personal,homepage,netherlands,nederland,nederlands,the netherlands,website,cool,dammit,damn,it,damnit,blah,linux,unix,script,scripts,shell,bash,web';
$skel['siteDescription'] = 'This is the rantbox of Michiel Scholten, Netherlands. It\'s my blog, so you can read about my frustrations and surf the links I share with the rest of the world :)';
$skel['feedDescription'] = 'Michiel\'s weblog // "A country that replaces freedom with security deserves neither" (Thomas Jefferson)';

/* Message to be shown on the main page, archive page and individual posting pages */
//$skel['globalmessage'] = 'dammIT is getting tweaked';
$skel['globalmessage'] = null; /* Use when you don't want such a message */

$skel['nrOfRantsPerPage'] = 10;	//varying between 10 and 15 :)
$skel['nrOfMarksPerPage'] = 20;
$skel['nrOfMarksInNav'] = 15;
$skel['servername'] = 'aquariusoft.org';
$skel['baseHref'] = '/~mbscholt/';
$skel['basePath'] = '/home/mbscholt/www/';
if (true == $skel['testing'])
{
	/*
	$skel['baseHref'] = '/projects/blog/';
	$skel['basePath'] = '/var/www/projects/blog/';
	*/
}

$skel['startyear'] = '2003';
$skel['logo'] = 'images/dammit_logo.png';
$skel['logoWidth'] = 176;
$skel['logoHeight'] = 71;
$skel['license_uri'] = 'http://creativecommons.org/licenses/by-nc-sa/2.0/';

/* If spring */
if ((date('m') == 3 && date('d') > 20) || (date('m') < 7))
{
	$skel['logo'] = 'images/dammit_cornflower.png';
}

/* If summer */
if ((date('m') == 6 && date('d') > 20) || (date('m') < 10))
{
	$skel['logo'] = 'images/dammit_stones.png';
}

/* If autumn */
if ((date('m') == 9 && date('d') > 20) || (date('m') <= 12))
{
	//$skel['logo'] = 'images/dammit_autumn_path.png';
	$skel['logo'] = 'images/dammit_autumn.png';
}

/* If winter */
if ((date('m') == 12 && date('d') > 10) || (date('m') < 4))
{
	$skel['logo'] = 'images/dammit_winter.png';
}

/*
 * Real path to rdf file [path used on server, like /var/www/blog.rdf]
 * Files should be writable for the webapp [chmod o+rw <filename>]
 */
$skel['rssFilename'] = $skel['basePath'] . 'blog.rdf';
$skel['rssWithCommentsFilename'] = $skel['basePath'] . 'blog_comments.rdf';
$skel['rssMarksFilename'] = $skel['basePath'] . 'marks.rdf';
$skel['rssEmail'] = 'mbscholt@aquariusoft.notforspambastards.org';

$skel['.plan'] = $skel['basePath'] . 'pages/plan.html';
$skel['about'] = $skel['basePath'] . 'pages/about.html';

/*** Navigation ***/
/*
$skel['nav_shared'] = array(
		'http://www.google.com/reader/shared/00682473562631681597' => 'from my google reader'
		);
*/
$skel['nav_blogs'] = array(
		'http://www.manuzhai.nl/' => 'manuzhai',
		'http://bloempje.nl/' => 'roosje',
		'http://www.rousette.org.uk/' => 'but she\'s a girl',
		'http://diveintomark.org/' => 'dive into mark',
		'http://www.randsinrepose.com/' => 'rands in repose',
		'http://www.1976design.com/blog/' => '1976design',
		'http://www.chipx86.com/blog/' => 'chipx86',
		'http://www.jaypinkerton.com/blog/' => 'jay pinkerton',
		'http://chongq.blogspot.com/' => 'chongqing',
		'http://anneliesje.nl/' => 'annelies',
		'http://tisseenschande.nl/' => 'schande',
		'http://mehellll.web-log.nl/' => 'melanie',
		'http://moniquearntz.web-log.nl/' => 'monique'
		);

$skel['nav_morphix'] = array(
		'http://alextreme.org/' => 'alextreme',
		'http://barwap.com/blog/' => 'bmsleight',
		'http://buranen.info/' => 'burner',
		'http://g1powermac.rozica.com/' => 'g1powermac',
		'http://www.kiberpipa.org/~gandalf/blog/' => 'gandalfar',
		'http://doid.com/' => 'mediovia',
		'http://del.icio.us/tag/livecd' => 'mediovia\'s links',
		'http://www.galaxycow.com/blogs/vermyndax' => 'vermyndax'
		);

$skel['nav_collectiveblogs'] = array(
		'http://planet.debian.net/' => 'planet debian',
		'http://planet.livecd.net/' => 'planet livecd',
		'http://www.boingboing.net/' => 'boingboing',
		'http://www.linuxchix.org/live/' => 'linuxchix',
		'http://www.mobilewhack.com/' => 'mobilewhack',
		'http://www.engadget.com/' => 'engadget'
		);

$skel['nav_webdev'] = array(
		'http://alistapart.com/' => 'a list apart',
		'http://annevankesteren.nl/' => 'van kesteren',
		'http://web404.blogspot.com/' => 'web404 tips'
		);

$skel['nav_photo'] = array(
		'http://aquariusoft.org/photolog/' => 'my photolog',
		'http://basvandijk.eu/blog/' => 'bas\' photolog'
		);


/* All sections to iterate over */
$skel['nav_sections'] = array(
		'nav_blogs' => 'more weblogs',
		'nav_photo' => 'photography',
		'nav_morphix' => 'morphix',
		'nav_collectiveblogs' => 'collective',
		'nav_webdev' => 'webdev'
		);

/*** Educated guess for location when adding new rant or blogmark ***/
$skel['locations'] = array(
		'192.168.*.*' => 'Home',
		'195.240.156.249' => 'Home',
		'213.84.100.*' => 'Work',
		'130.37.*.*' => 'Vrije Universiteit',
		'130.37.24.*' => 'Vrije Universiteit - Computer lab',
		'130.37.26.*' => 'Vrije Universiteit - Laptop over wifi',
		'84.80.247.120' => 'Mother-in-law\'s place'
		);


/*** Session identification ***/
$skel['session_name'] = 'WEBLOGSESSID';


/*** Path used for sending mail ***/
$skel['mailPath'] = '/usr/sbin/sendmail -t';
$skel['mailFrom'] = $skel['mainEmail'];
$skel['mailFromName'] = 'dammIT';
$skel['mailTo'] = 'mbscholt@aquariusoft.org';
/* Notification for weblog owner */
$skel['mailSubject'] = '[dammIT] New comment';
/* Notification for other posters */
$skel['mailNotificationSubject'] = '[dammIT] New comment posted';


/*** Stylesheet filename ***/
$skel['cssTheme'] = 'blue';


/* Automated tasks, like the posting of the blogmarks of this week, are restricted to IP: */
$skel['restricttoip'] = '127.0.0.1';


/*
 * Sort of hack - If browser is NetFront [used on pda's], get the plain, simpler css
 * Add useragents if you want them to use the simpler stylesheet to:
 * if (eregi('NetFront', getenv('HTTP_USER_AGENT')) || eregi('someotherbrowser', getenv('HTTP_USER_AGENT')))
 * But use with care so you don't exclude capable browsers.
 */
if (eregi('NetFront', getenv('HTTP_USER_AGENT'))|| eregi('Motorola A1000', getenv('HTTP_USER_AGENT')))
{
	/* @TODO: update the plain theme */
	$skel['cssTheme'] = 'plain';
}
/* End of simpler css 'hack' */


/*** Database settings ***/
$db_url = 'localhost';
$db_name = 'dammit_weblog';
$db_user = 'blog';
$db_pass = 'blogPs666!';

?>
