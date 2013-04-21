<?php
/**
 * Configuration file for 'Michiel Scholten's blog aka rantbox'
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


/*** Site settings ***/
$skel['testing'] = false;
$skel['testing'] = true;

/* Global comments enabled yes/no toggle */
$skel['commentsenabled'] = true;
//$skel['commentsenabled'] = false;

$skel['author'] = 'Your Name';
$skel['authorShortname'] = 'You';
$skel['siteName'] = 'Yet Another Rantbox';
$skel['mainEmail'] = 'dammit@aquariusoft.org';
$skel['rssEmail'] = 'dammit@aquariusoft.notforspambastards.org';
$skel['tagline'] = 'Curiosity is a virtue';
$skel['siteKeywords'] = 'Michiel Scholten,michiel,scholten,web,log,weblog,blog,rantbox,rant,rants,frustrations,personal,homepage,netherlands,nederland,nederlands,the netherlands,website,cool,dammit,damn,it,damnit,blah,linux,unix,script,scripts,shell,bash,web';
$skel['siteDescription'] = 'This is the rantbox of Michiel Scholten, Netherlands. It\'s my blog, so you can read about my findings, interests, frustrations and surf the links I share with the rest of the world :)';
$skel['feedDescription'] = 'Michiel\'s weblog // Those who would give up Essential Liberty to purchase a little Temporary Safety, deserve neither Liberty nor Safety (Benjamin Franklin)';

/* Social. Uncomment and fill in as you please */
$skel['twitter_username'] = 'michielscholten';
$skel['gplus_username'] = '112196316359193473289';
$skel['flickr_username'] = 'aquatix';
//$skel['picasa_username'] = '';
$skel['linkedin_username'] = 'mbscholten';

/* Your Google Analytics code. Set to null if you don't have one */
$skel['googleAnalyticsCode'] = null;
//$skel['googleAnalyticsCode'] = 'UA-0000000-0';

$skel['piwikURL'] = 'example.com/piwik/';
$skel['piwikSiteID'] = '2';

/* Message to be shown on the main page, archive page and individual posting pages */
//$skel['globalmessage'] = 'This weblog is getting tweaked';
$skel['globalmessage'] = null; /* Use when you don't want such a message */


/*** Stylesheet filename ***/
/* ./themes/NAME/ */
$skel['theme'] = 'dammit';


/*** Extra pages ***/
/* Syntax: $skel['page_SLUG'] = $skel['base_dir'] . 'pages/SLUG.html'; */
$skel['page_plan'] = $skel['base_dir'] . '/pages/plan.html';
$skel['page_plan_title'] = '.plan';
$skel['page_about'] = $skel['base_dir'] . '/pages/about.html';
$skel['page_about_title'] = 'About';
$skel['page_books'] = $skel['base_dir'] . '/pages/books.html';
$skel['page_books_title'] = 'Books';


/*** Educated guess for location when adding new rant or blogmark ***/
$skel['locations'] = array(
		'192.168.*.*' => 'Home',
		//'94.208.231.130' => 'Home',
		'83.82.64.55' => 'Home',
		//'213.84.100.*' => 'Work',
		'62.69.187.5' => 'Work',
		'130.37.*.*' => 'Vrije Universiteit',
		'130.37.24.*' => 'Vrije Universiteit - Computer lab',
		'130.37.26.*' => 'Vrije Universiteit - Laptop over wifi',
		'84.80.247.120' => 'Mother-in-law\'s place'
		);

/* Automated tasks, like the posting of the blogmarks of this week, are restricted to IP: */
$skel['restricttoip'] = '94.142.246.68';	// typically 127.0.0.1
$skel['restricttoip'] = '2a02:898:62:f6::44';	// typically 127.0.0.1


/*** Database settings ***/
$db_url = 'localhost';
$db_name = 'simple_weblog';
$db_user = 'blog';
$db_pass = 'smplWblgPw!';
