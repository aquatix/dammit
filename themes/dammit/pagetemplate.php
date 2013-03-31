<?php
/*
 * The page template of the dammIT webblog
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


function buildPage($skel, $section_name, $page_name, $page_body)
{
	/* https://github.com/murtaugh/HTML5-Reset */
	include 'header.php';

/*	
	$page .= "<meta name=\"robots\" content=\"index, follow\"/>\n";
	$page .= "<meta name=\"generator\" content=\"Err... Myself :) Read: vim\"/>\n";
	$page .= "<meta name=\"keywords\" content=\"" . $skel['siteKeywords'] . "\"/>\n";
	$page .= "<meta name=\"description\" content=\"" . $skel['siteDescription'] . "\"/>\n";

	$page .= "<meta name=\"author\" content=\"" . $skel['author'] . "\"/>\n";
	$page .= "<link rel=\"start\" href=\"" . $skel['base_uri'] . "\" title=\"Home page\" />\n";
	$page .= "<link rel=\"up\" href=\"" . $skel['base_uri'] . "\" title=\"Home page\" />\n";

	$page .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"" . $skel['base_uri'] . "blog.rdf\" />\n";

	$page .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $skel['base_uri'] . "themes/" . $skel['theme'] . "/css/style.css\"/>\n";

	$page .= "</head>\n";
	$page .= "<body>\n";

	$page .= "<div class=\"wrapperbox\"><div class=\"page\">\n";

	$page .= "<div id=\"logo\" title=\"Rantbox of a natural geek\"><a href=\"" . $skel['base_uri'] . "\"><img src=\"" . $skel['base_uri'] . $skel["logo"] . "\" alt=\"logo\"/></a></div>\n";

	$page .= "<div id=\"main-content\">\n";

	$page .= "\t<div id=\"license\"><a href=\"" . $skel["license_uri"] . "\"><img src=\"" . $skel['base_uri'] . "images/logos/creativecommons.png\" alt=\"Creative Commons License\" title=\"Creative Commons: attribution, noncommercial, share alike\"/></a></div>\n";


	if ($iebrowser === true)
	{
		$page .= "\t<div class=\"ie-warning\">You are using <acronym title=\"Internet Explorer\">IE</acronym>. Please use a browser that <em>has</em> a valid notion of <acronym title=\"Cascading Style Sheets\">CSS</acronym> for viewing the modern web, it'll make your general web browsing experience a lot better. Take a look at <a href=\"http://www.mozilla.org/products/firefox/\">Mozilla Firefox</a> [Free], or <a href=\"http://www.opera.com/\">Opera</a> [non-Free], or <a href=\"http://www.google.com/chrome\">Google Chrome</a> [non-Free] for some modern webbrowsers.</div>";
	}

	$page .= $page_body;

	$page .= "</div>\n";

	$page .= "\t<div id=\"main-content-nav\">\n";
*/

	include 'sidebar.php';

	//$page .= buildNavigation($skel);



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
	\t<Work rdf:about=\"" . $skel['base_server'] . $skel['base_uri'] . "\">
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
	-->\n";

	if (false === $skel['testing'])
	{
		/* Analytics; only when live */

		/* Google Analytics stuff */
		$page .= "<script type=\"text/javascript\">\n";
		$page .= "var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");\n";
		$page .= "document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));\n";
		$page .= "</script>\n";
		$page .= "<script type=\"text/javascript\">\n";
		$page .= "try {\n";
		//$page .= "var pageTracker = _gat._getTracker(\"UA-10643901-2\");\n";
		$page .= "var pageTracker = _gat._getTracker(\"UA-10643901-3\");\n";
		$page .= "pageTracker._trackPageview();\n";
		$page .= "} catch(err) {}</script>\n";

		$page .= <<<'EOD'
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://aquariusoft.org/r00t/webstats/" : "http://aquariusoft.org/r00t/webstats/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 2);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://aquariusoft.org/r00t/webstats/piwik.php?idsite=2" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
EOD;
	}

	include 'footer.php';


	/****** Now finally print the contents of the page to the browser ******/
	return $page;
}

function buildNavigation($skel)
{
	/*** Navigation part ***/
/*
	$page .= "<div id=\"main-navbar\">\n";
	$page .= "\t<ul id=\"main-nav\">\n";
	$page .= "\t\t<li><a href=\"./\" accesskey=\"h\" title=\"Home\">home</a></li>\n";
	$page .= "\t\t<li><a href=\"index.php?page=archive\" accesskey=\"a\" title=\"View all post titles in the archive\">archive</a></li>\n";
	$page .= "\t\t<li><a href=\"blogmarks.php\" accesskey=\"m\" title=\"View interesting links\">blogmarks</a></li>\n";
	$page .= "\t\t<li><a href=\"index.php?page=kudos\" accesskey=\"k\" title=\"View a list of sites that deserve kudos\">kudos</a></li>\n";
	$page .= "\t\t<li><a href=\"index.php?page=about\" accesskey=\"?\" title=\"Information about the author\">about</a></li>\n";
	$page .= "\t</ul>\n";
*/
	$page .= "<div id=\"main-navbar\">\n";
	$page .= "\t<ul id=\"main-nav\">\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "\" accesskey=\"h\" title=\"Home\">home</a></li>\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "p/archive\" accesskey=\"a\" title=\"View all post titles in the archive\">archive</a></li>\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "m\" accesskey=\"m\" title=\"View interesting links\">blogmarks</a></li>\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "p/books\" accesskey=\"b\" title=\"View interesting links\">books</a></li>\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "p/kudos\" accesskey=\"k\" title=\"View a list of sites that deserve kudos\">kudos</a></li>\n";
	$page .= "\t\t<li><a href=\"" . $skel['base_uri'] . "p/about\" accesskey=\"?\" title=\"Information about the author\">about</a></li>\n";
	$page .= "\t</ul>\n";

	/* Version information */
	$page .= "\t<div class=\"pageversion\">v" . $skel['page_version'] . "</div>\n</div>\n";

//	$page .= "\t\t</div>\n";

	/*** Links-menu ***/
	/* Search field */
	if (!isset($searchkey))
	{
		$searchkey = '';
	} 
	$page .= "\t\t<form style=\"margin-top: 1em;\" action=\"" . $skel['base_uri'] . "search\" method=\"post\"><div><input type=\"text\" class=\"searchfield\" name=\"searchkey\" size=\"12\" maxlength=\"250\" value=\"" . $searchkey . "\" /><input name=\"searchbtn\" value=\"Find\" type=\"submit\" /></div></form>\n";


	$page .= "\t\t<div class=\"nav-header\">distracted by</div>\n";
	$page .= buildSimpleMarks(getMarks($skel, 0, $skel["nrOfMarksInNav"]));

	/* Images */
	$page .= "\t<div class=\"images\">\n";
	$page .= "\t\t<ul>\n";
	$page .= "\t\t\t<li><a href=\"blog.rdf\" title=\"Get my feed into your reader :)\"><img src=\"" . $skel['base_uri'] . "images/logos/rss_20.png\" alt=\"RSS feed\"/></a></li>\n";
	$page .= "\t\t\t<li><a href=\"blog_comments.rdf\" title=\"Get my feed with comments into your reader :)\"><img src=\"" . $skel['base_uri'] . "images/logos/rsscomments.gif\" alt=\"RSS feed with comments\"/></a></li>\n";
	$page .= "\t\t\t<li><a href=\"marks.rdf\" title=\"Get my blogmarks into your reader :)\"><img src=\"" . $skel['base_uri'] . "images/logos/rss_marks.png\" alt=\"RSS feed - blogmarks\"/></a></li>\n";
	$page .= "\t\t</ul>\n\t</div>\n";
	return $page;
}

function buildKudos($skel)
{
	/*
	$page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
	<script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=5&callback=GRC_p(%7Bc%3A\'blue\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
	*/
	//$page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
	//<script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=10&amp;callback=GRC_p(%7Bc%3A\'blue\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
	// disabled at 20071213 $page .= '<script type="text/javascript" src="http://www.google.com/reader/ui/publisher.js"></script>
	// disabled at 20071213 <script type="text/javascript" src="http://www.google.com/reader/public/javascript/user/00682473562631681597/state/com.google/broadcast?n=10&amp;callback=GRC_p(%7Bc%3A\'-\'%2Ct%3A\'Michiel%5C047s%20shared%20items\'%2Cs%3A\'false\'%7D)%3Bnew%20GRC"></script>';
	// disabled at 20071213 $page .= "<p><a href=\"http://www.google.com/reader/public/atom/user/00682473562631681597/state/com.google/broadcast\">Feed</a></p>\n";

	$page = '';

	/* Generate links-menu */
	foreach ( array_keys($skel['nav_sections']) as $navsection_name )
	{
		$page .= "\t\t<h3>" . $skel['nav_sections'][$navsection_name] . "</h3>\n";
		$page .= "\t\t<ul>\n";
		foreach ( array_keys($skel[$navsection_name]) as $navitem_name )
		{
			$page .= "\t\t\t<li><a href=\"" . $navitem_name . "\">" . $skel[$navsection_name][$navitem_name] . "</a></li>\n";
		}
		$page .= "\t\t</ul>\n";
	}
	$page .= "\t\t<h3>images</h3>\n";
	$page .= "\t\t<div class=\"images\">\n";
	$page .= "\t\t\t<ul>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.debian.org/\" title=\"Debian GNU/Linux\"><img src=\"" . $skel['base_uri'] . "images/logos/debian.png\" alt=\"Debian GNU/Linux\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.fark.com/\" title=\"Fark.com\"><img src=\"" . $skel['base_uri'] . "images/logos/fark.png\" alt=\"Fark.com\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.tapestrycomics.com/\" title=\"Tapestry comics\"><img src=\"" . $skel['base_uri'] . "images/logos/gotcomics.png\" alt=\"Got comics\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.deviantart.com/\" title=\"deviantART\"><img src=\"" . $skel['base_uri'] . "images/logos/deviantart_logo.gif\" alt=\"deviantART logo\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.scripting.com/\"><img src=\"" . $skel['base_uri'] . "images/logos/thanksdave.jpg\" title=\"My thanks to Dave Winer for his visionary role in the development of weblogs, RSS, podcasting, SOAP, XML-RPC, OPML, and outliners.\" alt=\"Thanks Dave\" /></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.technorati.com/profile/aquatix/3133231/41b298b8585eafc9e8c55a0b30c90f65\" title=\"My Technorati profile\"><img src=\"" . $skel['base_uri'] . "images/logos/technorati.gif\" alt=\"Technorati profile\"/></a></li>\n";
	$page .= "\t\t\t</ul>\n";
	$page .= "\t\t</div>\n";

	$page .= "\t\t<div class=\"images\">\n";
	$page .= "\t\t\t<ul>\n";
	$page .= "\t\t\t\t<li><a href=\"http://no-www.org/\" title=\"www. is deprecated\"><img src=\"" . $skel['base_uri'] . "images/logos/no-www.gif\" alt=\"www. is deprecated\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"index.php?page=about#browser\" title=\"Readable with Any Browser(tm)\"><img src=\"" . $skel['base_uri'] . "images/logos/browser_all.png\" alt=\"Optimized for all browsers\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://validator.w3.org/check/referer\" title=\"Using valid xhtml1.1\"><img src=\"" . $skel['base_uri'] . "images/logos/valid_xhtml11.gif\" alt=\"Valid xhtml1.1\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://jigsaw.w3.org/css-validator/check/referer\" title=\"Using valid CSS\"><img src=\"" . $skel['base_uri'] . "images/logos/valid_css.gif\" alt=\"Valid CSS\"/></a></li>\n";
	$page .= "\t\t\t</ul>\n";
	$page .= "\t\t</div>\n";

	$page .= "\t\t<div class=\"images\">\n";
	$page .= "\t\t\t<ul>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.catb.org/hacker-emblem/\" title=\"Solidary with white hat hacking\"><img src=\"" . $skel['base_uri'] . "images/logos/hacker_logo.png\" alt=\"Hacker emblem\"/></a></li>\n";
	//$page .= "\t\t\t\t<li><a href=\"callto:aquatix\" title=\"Skype me! [aquatix]\"><img src=\"images/logos/skypeme.gif\" alt=\"Skype me! [aquatix]\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.mozilla.com/firefox/\" title=\"Get Firefox - Web Browsing Redefined [and take back the web]\"><img src=\"" . $skel['base_uri'] . "images/logos/firefox_pixel.png\" alt=\"Get Firefox\"/></a></li>\n";
	$page .= "\t\t\t\t<li><a href=\"http://www.mozilla.com/thunderbird/\" title=\"Get Thunderbird and reclaim your inbox!\"><img src=\"" . $skel['base_uri'] . "images/logos/thunderbird_pixel.png\" alt=\"Get Thunderbird\"/></a></li>\n";
	$page .= "\t\t\t</ul>\n";
	$page .= "\t\t</div>\n";

	$page .= "\t\t<div class=\"images\">\n";
	$page .= "\t\t\t<ul>\n";
	$page .= "\t\t\t\t<li><a href=\"http://eldred.cc/\"><img src=\"" . $skel['base_uri'] . "images/logos/create_like_its_1790.gif\" title=\"Save Orphan Works\" alt=\"create like it's 1790\" width=\"88\" height=\"31\" /></a></li>\n";
	$page .= "\t\t\t</ul>\n";
	$page .= "\t\t</div>\n";

	return $page;
}
