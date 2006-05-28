<?php
	/*
	 * Configuration file for "Michiel Scholten's blog aka rantbox"
	 * Version: 0.3.18 2005-10-21
	 */

	/*** Site settings ***/
	$skel["testing"] = false;

	$skel["author"] = "Michiel Scholten";
	$skel["authorShortname"] = "Michiel";
	$skel["sitename"] = "dammIT";
	$skel["sitedescription"] = "Michiel's weblog // \"A country that replaces freedom with security deserves neither\" (Thomas Jefferson)";
	$skel["mainEmail"] = "dammit@aquariusoft.org";

	$skel["nrOfRantsPerPage"] = 10;	//varying between 10 and 15 :)
	$skel["nrOfMarksPerPage"] = 20;
	$skel["nrOfMarksInNav"] = 15;
	//$skel["baseHref"] = "http://aquariusoft.org/~mbscholt/";
	$skel["servername"] = "aquariusoft.org";
	$skel["baseHref"] = "/~mbscholt/";
	$skel["basePath"] = "/home/mbscholt/www/";
	if (true == $skel["testing"])
	{
		//$skel["baseHref"] = "http://aquariusoft.org/projects/blog/";
		$skel["baseHref"] = "/projects/blog/";
		$skel["basePath"] = "/var/www/projects/blog/";
	}

	$skel["startyear"] = "2003";
	$skel["logo"] = "images/dammit_logo.png";
	$skel["logoWidth"] = 176;
	$skel["logoHeight"] = 71;
	$skel["license_uri"] = "http://creativecommons.org/licenses/by-nc-sa/2.0/";

	/* If spring */
	if ((date("m") == 3 && date("d") > 20) || (date("m") < 7))
	{
		$skel["logo"] = "images/dammit_cornflower.png";
	}

	/* If summer */
	if ((date("m") == 6 && date("d") > 20) || (date("m") < 10))
	{
		$skel["logo"] = "images/dammit_stones.png";
	}

	/* If autumn */
	if ((date("m") == 9 && date("d") > 20) || (date("m") <= 12))
	{
		$skel["logo"] = "images/dammit_autumn_path.png";
	}

	/* If winter */
	if ((date("m") == 12 && date("d") > 10) || (date("m") < 4))
	{
		$skel["logo"] = "images/dammit_winter.png";
	}

	/* Real path to rdf file [path used on server, like /var/www/blog.rdf] */
	$skel["rssFilename"] = $skel["basePath"] . "blog.rdf";
	$skel["rssWithCommentsFilename"] = $skel["basePath"] . "blog_comments.rdf";
	$skel["rssMarksFilename"] = $skel["basePath"] . "marks.rdf";
	$skel["rssEmail"] = "mbscholt@aquariusoft.notforspambastards.org";
	
	$skel[".plan"] = $skel["basePath"] . "pages/plan.html";
	$skel["about"] = $skel["basePath"] . "pages/about.html";

	/*** Navigation ***/
        $skel["nav_blogs"] = array(
/*		"http://alextreme.org/" => "alextreme",*/
		"http://www.manuzhai.nl/" => "manuzhai",
		"http://roos.b-cool.nl/" => "roosje",
		"http://www.rousette.org.uk/" => "but she's a girl",
		"http://diveintomark.org/" => "dive into mark",
		"http://www.randsinrepose.com/" => "rands in repose",
		"http://www.1976design.com/blog/" => "1976design",
		"http://www.chipx86.com/blog/" => "chipx86",
		"http://www.jaypinkerton.com/blog/" => "jay pinkerton",
		"http://kfx.toastmedia.com/" => "kfx studios",
/*		"http://www.mynx.com/" => "mynxisms",*/
		"http://chongq.blogspot.com/" => "chongqing",
/*		"http://willy.boerland.com/myblog/index.php" => "willy dobbe"*/
		"http://www.anneliesje.thehosting.nl/blog/" => "annelies",
		"http://www.sanderschouten.net/php/weblog.php" => "schande",
		"http://mehellll.web-log.nl/" => "melanie"
	);

        $skel["nav_morphix"] = array(
		"http://alextreme.org/" => "alextreme",
		"http://barwap.com/blog/" => "bmsleight",
		"http://buranen.info/" => "burner",
		"http://g1powermac.rozica.com/" => "g1powermac",
		"http://www.kiberpipa.org/~gandalf/blog/" => "gandalfar",
		"http://blogs.livecd.net/gandalfar/index.php?blog=5" => "gandalfar [old]",
		"http://doid.com/" => "mediovia",
		"http://del.icio.us/tag/livecd" => "mediovia's links",
/*		"http://galaxycow.com/blogs/vermyndax/" => "vermyndax"*/
		"http://www.galaxycow.com/blogs/vermyndax" => "vermyndax"
	);
/*
        $skel["nav_morphix"] = array(
		"http://alextreme.org/" => "alextreme",
		"http://barwap.com/blog/" => "bmsleight",
		"http://s88449685.onlinehome.us/index.php" => "burner",
		"http://g1powermac.rozica.com/" => "g1powermac",
		"http://blogs.livecd.net/skrat/" => "gasper koren",
		"http://blogs.livecd.net/index.php?blog=7" => "gasper zejn",
		"http://blogs.livecd.net/gandalfar/index.php?blog=5" => "gandalfar",
		"http://doid.com/" => "mediovia",
		"http://del.icio.us/tag/livecd" => "mediovia's links",
		"http://galaxycow.com/blogs/vermyndax/" => "vermyndax",
		"http://blogs.livecd.net/index.php?blog=4" => "linkblog"
	);
*/
	$skel["nav_collectiveblogs"] = array(
		"http://planet.debian.net/" => "planet debian",
		"http://planet.livecd.net/" => "planet livecd",
		"http://www.boingboing.net/" => "boingboing",
/*		"http://www.kuro5hin.org/" => "kuro5hin.org",*/
		"http://live.linuxchix.org/" => "linuxchix",
		"http://www.mobilewhack.com/" => "mobilewhack",
		"http://www.engadget.com/" => "engadget"
	);


        $skel["nav_webdev"] = array(
		"http://alistapart.com/" => "a list apart",
		"http://annevankesteren.nl/" => "van kesteren"
	);

	/* All sections to iterate over */
	$skel["nav_sections"] = array(
		"nav_blogs" => "more weblogs",
		"nav_morphix" => "morphix",
		"nav_collectiveblogs" => "collective",
		"nav_webdev" => "webdev"
	);


	/*** Session identification ***/
	$skel["session_name"] = "WEBLOGSESSID";


	/*** Path used for sending mail ***/
	$skel["mailPath"] = "/usr/sbin/sendmail -t";
	$skel["mailFrom"] = $skel["mainEmail"];
	$skel["mailFromName"] = "dammIT";
	$skel["mailTo"] = "mbscholt@aquariusoft.org";
	/* Notification for weblog owner */
	$skel["mailSubject"] = "[dammIT] New comment";
	/* Notification for other posters */
	$skel["mailNotificationSubject"] = "[dammIT] New comment posted";


	/*** Stylesheet filename ***/
	//$skel["cssFilename"] = "main.css";
	$skel["cssTheme"] = "blue";


	/*
	 * Sort of hack - If browser is NetFront [used on pda's], get the plain, simpler css
	 * Add useragents if you want them to use the simpler stylesheet to:
	 * if (eregi("NetFront", getenv("HTTP_USER_AGENT")) || eregi("someotherbrowser", getenv("HTTP_USER_AGENT")))
	 * But use with care so you don't exclude capable browsers.
	 */
	if (eregi("NetFront", getenv("HTTP_USER_AGENT"))|| eregi("Motorola A1000", getenv("HTTP_USER_AGENT")))
	{
		/* @TODO: update the plain theme */
		$skel["cssTheme"] = "plain";
	}
	/* End of simpler css "hack" */


	/*** Database settings ***/
	$db_url = "localhost";
	$db_name = "blog";
	$db_user = "blog";
	$db_pass = "blogPs666!";

?>
