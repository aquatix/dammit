<?php
/*
 * $Id$
 * 
 * File containing the page template of the dammIT webblog
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


/* Check for IE with its stupid width bugs */
$useragent = getenv("HTTP_USER_AGENT");
$iebrowser = (eregi("msie", $useragent) && !(eregi("opera", $useragent) || eregi("gecko", $useragent)));

$page_title = $page_name;
//$page .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
//$page .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";

$page  = "<?xml version=\"1.0\"?>\n";
$page .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
$page .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

$page .= "<head>\n";
$page .= "<title>" . $page_title . ' | ' . $skel['siteName'] . "</title>\n";
//$page .= "<base href=\"http://" . $skel["servername"] . $skel["baseHref"] . "\"/>\n";
$page .= "<meta name=\"robots\" content=\"index, follow\"/>\n";
$page .= "<meta name=\"generator\" content=\"Err... Myself :) Read: vim\"/>\n";
//$page .= "<meta name=\"author\" content=\"Michiel Scholten\"/>\n";
//$page .= "<meta name=\"author\" link=\"" . $skel["baseHref"] . "about.php\" title=\"Michiel Scholten\"/>\n";
//$page .= "<meta name=\"author\" link=\"about.php\" title=\"Michiel Scholten\"/>\n";
$page .= "<meta name=\"keywords\" content=\"" . $skel['siteKeywords'] . "\"/>\n";
$page .= "<meta name=\"description\" content=\"" . $skel['siteDescription'] . "\"/>\n";

//$page .= "<meta name=\"author\" href=\"/~mbscholt/about.php\" title=\"Contact the author\"/>\n";
$page .= "<meta name=\"author\" content=\"" . $skel['author'] . "\"/>\n";
/*
   <link rel="copyright" href="/blog/copyright/" title="Copyright details" />
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
 */
$page .= "<link rel=\"start\" href=\"/~mbscholt/\" title=\"Home page\" />\n";
$page .= "<link rel=\"up\" href=\"/~mbscholt/\" title=\"Home page\" />\n";

$page .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"http://aquariusoft.org/~mbscholt/blog.rdf\" />\n";

$page .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"themes/" . $skel["cssTheme"] . "/style.css?20081222\"/>\n";
$page .= "<!--[if gte ie 5.5000]><link rel=\"stylesheet\" type=\"text/css\" href=\"themes/" . $skel['cssTheme'] . "/ie.css\" /><![endif]-->\n";

$page .= "</head>\n";
$page .= "<body>\n";

$page .= "<div id=\"logo\" title=\"Rantbox of a natural geek\"><a href=\"" . $skel["baseHref"] . "\"><img src=\"" . $skel["logo"] . "\" alt=\"logo\"/></a></div>\n";


/*** Navigation part ***/
$page .= "<div id=\"left-navbar\">\n";
$page .= "\t<div class=\"nav-header\">dammIT</div>\n";
$page .= "\t<ul id=\"main-nav\">\n";
$page .= "\t\t<li><a href=\"./\" accesskey=\"h\" title=\"Home\">home</a></li>\n";
$page .= "\t\t<li><a href=\"index.php?page=archive\" accesskey=\"a\" title=\"View all post titles in the archive\">archive</a></li>\n";
$page .= "\t\t<li><a href=\"blogmarks.php\" accesskey=\"m\" title=\"View interesting links\">blogmarks</a></li>\n";
$page .= "\t\t<li><a href=\"index.php?page=about\" accesskey=\"?\" title=\"Information about the author\">about</a></li>\n";
$page .= "\t</ul>\n";

/* Images */
$page .= "\t<div class=\"images\">\n";
$page .= "\t\t<ul>\n";
$page .= "\t\t\t<li><a href=\"blog.rdf\" title=\"Get my feed into your reader :)\"><img src=\"images/logos/rss_20.png\" alt=\"RSS feed\"/></a></li>\n";
$page .= "\t\t\t<li><a href=\"blog_comments.rdf\" title=\"Get my feed with comments into your reader :)\"><img src=\"images/logos/rsscomments.gif\" alt=\"RSS feed with comments\"/></a></li>\n";
$page .= "\t\t\t<li><a href=\"marks.rdf\" title=\"Get my blogmarks into your reader :)\"><img src=\"images/logos/rss_marks.png\" alt=\"RSS feed - blogmarks\"/></a></li>\n";
$page .= "\t\t</ul>\n\t</div>\n";

/* Version information */
$page .= "\t<div class=\"pageversion\">v" . $page_version . "</div>\n</div>\n";


/*** Main content block ***/

$page .= "<div id=\"main-content\">\n";

$page .= "\t<div id=\"license\"><a href=\"" . $skel["license_uri"] . "\"><img src=\"images/logos/creativecommons.png\" alt=\"Creative Commons License\" title=\"Creative Commons: attribution, noncommercial, share alike\"/></a></div>\n";


/*** Body of the page, generated by index.php or other page ***/

if ($iebrowser === true)
{
	$page .= "\t<div class=\"ie-warning\">You are using <acronym title=\"Internet Explorer\">IE</acronym>. Please use a browser that <em>has</em> a valid notion of <acronym title=\"Cascading Style Sheets\">CSS</acronym> for viewing the modern web, it'll make your general web browsing experience a lot better. Take a look at <a href=\"http://www.mozilla.org/products/firefox/\">Mozilla Firefox</a> [Free], or <a href=\"http://www.opera.com/\">Opera</a> [non-Free], or <a href=\"http://www.google.com/chrome\">Google Chrome</a> [non-Free] for some modern webbrowsers.</div>";
}

$page .= $page_body;
$page .= "\t<div id=\"last-modified\">[ Valid <a href=\"http://validator.w3.org/check/referer\">XHTML 1.0</a> | &copy; " . $skel['startyear'] . "-" . date('Y') . " <a href=\"index.php?page=about\">" . $skel['author'] . "</a> under a <a href=\"" . $skel['license_uri'] . "\"><acronym title=\"Creative Commons\">CC</acronym> License</a> ]</div>\n";

$page .= "\t<div id=\"main-content-nav\">\n";


/*** Links-menu ***/
/* Search field */
if (!isset($searchkey))
{
	$searchkey = '';
} 
$page .= "\t\t<form action=\"search.php\" method=\"post\"><div><input type=\"text\" class=\"searchfield\" name=\"searchkey\" size=\"12\" maxlength=\"250\" value=\"" . $searchkey . "\" /><input name=\"searchbtn\" value=\"Find\" type=\"submit\" /></div></form>\n";


$page .= "\t\t<div class=\"nav-header\">distracted by</div>\n";
$page .= buildSimpleMarks(getMarks($skel, 0, $skel["nrOfMarksInNav"]));

/*
$page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
<script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=5&callback=GRC_p(%7Bc%3A\'blue\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
*/
//$page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
//<script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=10&amp;callback=GRC_p(%7Bc%3A\'blue\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
// disabled at 20071213 $page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
// disabled at 20071213 <script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=10&amp;callback=GRC_p(%7Bc%3A\'-\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
// disabled at 20071213 $page .= "<p><a href=\"http://www.google.com/reader/public/atom/user/00682473562631681597/state/com.google/broadcast\">Feed</a></p>\n";

/* Generate links-menu */
foreach ( array_keys($skel['nav_sections']) as $navsection_name )
{
	//
	$page .= "\t\t<div class=\"nav-header\">" . $skel['nav_sections'][$navsection_name] . "</div>\n";
	$page .= "\t\t<ul>\n";
	foreach ( array_keys($skel[$navsection_name]) as $navitem_name )
	{
		$page .= "\t\t\t<li><a href=\"" . $navitem_name . "\">" . $skel[$navsection_name][$navitem_name] . "</a></li>\n";
	}
	$page .= "\t\t</ul>\n";
}

$page .= "\t\t<div class=\"images\">\n";
$page .= "\t\t\t<ul>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.debian.org/\" title=\"Debian GNU/Linux\"><img src=\"images/logos/debian.png\" alt=\"Debian GNU/Linux\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.fark.com/\" title=\"Fark.com\"><img src=\"images/logos/fark.png\" alt=\"Fark.com\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.tapestrycomics.com/\" title=\"Tapestry comics\"><img src=\"images/logos/gotcomics.png\" alt=\"Got comics\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.deviantart.com/\" title=\"deviantART\"><img src=\"images/logos/deviantart_logo.gif\" alt=\"deviantART logo\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.scripting.com/\"><img src=\"images/logos/thanksdave.jpg\" title=\"My thanks to Dave Winer for his visionary role in the development of weblogs, RSS, podcasting, SOAP, XML-RPC, OPML, and outliners.\" alt=\"Thanks Dave\" /></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.technorati.com/profile/aquatix/3133231/41b298b8585eafc9e8c55a0b30c90f65\" title=\"My Technorati profile\"><img src=\"images/logos/technorati.gif\" alt=\"Technorati profile\"/></a></li>\n";
$page .= "\t\t\t</ul>\n";
$page .= "\t\t</div>\n";

$page .= "\t\t<div class=\"images\">\n";
$page .= "\t\t\t<ul>\n";
$page .= "\t\t\t\t<li><a href=\"http://no-www.org/\" title=\"www. is deprecated\"><img src=\"images/logos/no-www.gif\" alt=\"www. is deprecated\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"index.php?page=about#browser\" title=\"Readable with Any Browser(tm)\"><img src=\"images/logos/browser_all.png\" alt=\"Optimized for all browsers\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://validator.w3.org/check/referer\" title=\"Using valid xhtml1.1\"><img src=\"images/logos/valid_xhtml11.gif\" alt=\"Valid xhtml1.1\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://jigsaw.w3.org/css-validator/check/referer\" title=\"Using valid CSS\"><img src=\"images/logos/valid_css.gif\" alt=\"Valid CSS\"/></a></li>\n";
$page .= "\t\t\t</ul>\n";
$page .= "\t\t</div>\n";

$page .= "\t\t<div class=\"images\">\n";
$page .= "\t\t\t<ul>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.catb.org/hacker-emblem/\" title=\"Solidary with white hat hacking\"><img src=\"images/logos/hacker_logo.png\" alt=\"Hacker emblem\"/></a></li>\n";
//$page .= "\t\t\t\t<li><a href=\"callto:aquatix\" title=\"Skype me! [aquatix]\"><img src=\"images/logos/skypeme.gif\" alt=\"Skype me! [aquatix]\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.mozilla.com/firefox/\" title=\"Get Firefox - Web Browsing Redefined [and take back the web]\"><img src=\"images/logos/firefox_pixel.png\" alt=\"Get Firefox\"/></a></li>\n";
$page .= "\t\t\t\t<li><a href=\"http://www.mozilla.com/thunderbird/\" title=\"Get Thunderbird and reclaim your inbox!\"><img src=\"images/logos/thunderbird_pixel.png\" alt=\"Get Thunderbird\"/></a></li>\n";
$page .= "\t\t\t</ul>\n";
$page .= "\t\t</div>\n";

$page .= "\t\t<div class=\"images\">\n";
$page .= "\t\t\t<ul>\n";
$page .= "\t\t\t\t<li><a href=\"http://eldred.cc/\"><img src=\"images/logos/create_like_its_1790.gif\" title=\"Save Orphan Works\" alt=\"create like it's 1790\" width=\"88\" height=\"31\" /></a></li>\n";
$page .= "\t\t\t</ul>\n";
$page .= "\t\t</div>\n";

$page .= "\t</div>\n";
$page .= "</div>\n";

$page .= "<!-- rendered in " . (microtime() - $starttime) . "sec -->\n";

/*
 * Creative Commons license
 * Note: if you license it under something else as by-nc-sa, see http://creativecommons.org/technology/licenseoutput for the right
 * license requires/permits/prohibits lines
 */
$page .= "
<!--
\t<rdf:RDF xmlns=\"http://web.resource.org/cc/\"
\txmlns:dc=\"http://purl.org/dc/elements/1.1/\"
\txmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">
\t<Work rdf:about=\"http://" . $skel["servername"] . $skel["baseHref"] . "\">
\t<dc:title>" . $skel['siteName'] . ", weblog of " . $skel["author"] . "</dc:title>
\t<dc:date>2003-12-24</dc:date>
\t<dc:description>A weblog maintained by " . $skel["author"] . "</dc:description>
\t<dc:creator><Agent>
\t<dc:title>" . $skel["author"] . "</dc:title>
\t</Agent></dc:creator>
\t<dc:rights><Agent>
\t<dc:title>" . $skel["author"] . "</dc:title>
\t</Agent></dc:rights>
\t<dc:type rdf:resource=\"http://purl.org/dc/dcmitype/Text\" />
\t<license rdf:resource=\"" . $skel["license_uri"] . "\" />
\t</Work>
\t<License rdf:about=\"" . $skel["license_uri"] . "\">
\t\t<requires rdf:resource=\"http://web.resource.org/cc/Attribution\" />
\t\t<permits rdf:resource=\"http://web.resource.org/cc/DerivativeWorks\" />
\t\t<permits rdf:resource=\"http://web.resource.org/cc/Reproduction\" />
\t\t<permits rdf:resource=\"http://web.resource.org/cc/Distribution\" />
\t\t<prohibits rdf:resource=\"http://web.resource.org/cc/CommercialUse\" />
\t\t<requires rdf:resource=\"http://web.resource.org/cc/Notice\" />
\t\t<requires rdf:resource=\"http://web.resource.org/cc/ShareAlike\" />
\t</License>
\t</rdf:RDF>
-->
";
$page .= "</body></html>\n";


/****** Now finally print the contents of the page to the browser ******/
echo $page;
?>
