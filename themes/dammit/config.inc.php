<?php
/**
 * Configuration file for theme dammIT 'Michiel Scholten's blog aka rantbox'
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

$skel['nrOfRantsPerPage'] = 10;	//varying between 10 and 15 :)
$skel['nrOfMarksPerPage'] = 20;
$skel['nrOfMarksInNav'] = 15;

$skel['startyear'] = '2003';
$skel['logo'] = 'images/dammit_logo.png';
$skel['logoWidth'] = 176;
$skel['logoHeight'] = 71;
$skel['license_uri'] = 'http://creativecommons.org/licenses/by-nc-sa/3.0/';
$skel['license_text'] = '<acronym title="Creative Commons">CC</acronym> License</a>';
$skel['license_text'] = '<span class="license"><acronym title="Creative Commons Attribution-NonCommercial-ShareAlike 3.0">&#128326;&#128327;&#128330;</acronym></span> License</a>';

if ((date('m') == 3 && date('d') > 20) || (date('m') < 7))
{
	/* If spring */
	$skel['logo'] = 'img/header_daffodil.jpg';
	$skel['logo'] = 'img/header_sunsetsky.jpg';
} else if ((date('m') == 6 && date('d') > 20) || (date('m') < 10))
{
	/* If summer */
	$skel['logo'] = 'images/dammit_namibia.jpg';
} else if ((date('m') == 9 && date('d') > 20) || (date('m') <= 12))
{
	/* If autumn */
	$skel['logo'] = 'images/dammit_mountains.jpg';
} else if ((date('m') == 12 && date('d') > 10) || (date('m') < 4))
{
	/* If winter */
	$skel['logo'] = 'images/dammit_rietsigaar.jpg';
	$skel['logoWidth'] = 880;
	$skel['logoHeight'] = 140;
}

/*** Navigation ***/
/*
$skel['nav_shared'] = array(
		'http://www.google.com/reader/shared/00682473562631681597' => 'from my google reader'
		);
*/
$skel['nav_blogs'] = array(
		//'http://www.manuzhai.nl/' => 'manuzhai',
		'http://anneliesje.nl/' => 'annelies',
		'http://basvandijk.eu/' => 'bas\' osiblog',
		'http://www.rousette.org.uk/' => 'but she\'s a girl',
		'http://www.chipx86.com/blog/' => 'chipx86',
		'http://chongq.blogspot.com/' => 'chongqing',
		'http://diveintomark.org/' => 'dive into mark',
		'http://languagelog.ldc.upenn.edu/nll/' => 'language log',
		'http://moniquearntz.web-log.nl/' => 'monique',
		'http://nugigeruli.com/' => 'nugigeruli',
		'http://www.randsinrepose.com/' => 'rands in repose',
		'http://bloempje.nl/' => 'roosje',
		'http://tisseenschande.nl/' => 'schande',
		//'http://mehellll.web-log.nl/' => 'melanie',
		);

$skel['nav_morphix'] = array(
		'http://alextreme.org/' => 'alextreme',
		'http://barwap.com/blog/' => 'bmsleight',
		'http://buranen.info/' => 'burner',
		'http://g1powermac.com/' => 'g1powermac',
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
		'http://www.engadget.com/' => 'engadget',
		'http://www.mobilewhack.com/' => 'mobilewhack',
		);

$skel['nav_webdev'] = array(
		'http://alistapart.com/' => 'a list apart',
		'http://annevankesteren.nl/' => 'van kesteren',
		'http://web404.blogspot.com/' => 'web404 tips'
		);

$skel['nav_photo'] = array(
		'http://dpreview.com' => 'dpreview',
		'http://www.diyphotography.net/' => 'dyi photography',
		'http://aquariusoft.org/photolog/' => '<em>my photolog</em>',
		);

$skel['nav_patents'] = array(
		'http://www.freepatentsonline.com' => 'free patents online',
		'http://www.sumobrain.com' => 'sumobrain patent searching',
		);

$skel['nav_memorable'] = array(
		'http://www.1976design.com/blog/' => '1976design',
		'http://www.jaypinkerton.com/blog/' => 'jay pinkerton',
		);


/* All sections to iterate over */
$skel['nav_sections'] = array(
		'nav_blogs' => 'more weblogs',
		'nav_photo' => 'photography',
		'nav_morphix' => 'morphix',
		'nav_collectiveblogs' => 'collective',
		'nav_patents' => 'patent research',
		'nav_webdev' => 'webdev',
		'nav_memorable' => 'memorable'
		);
