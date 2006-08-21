<?php
/*
 * file: mod_blog_html.php
 *       Blog module - HTML methods
 *       v0.4.04 2006-08-21
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


/***    Rants   ***/

/*
 * Build rantlist from $rants
 * $rants:
 * messageID
 * date
 * user
 * ip
 * title
 * message
 * modified
 * modifiedDate
 * location
 * nrOfComments
 */
function buildRants( $rants )
{
	$rantsHTML = '';
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($rants); $i++)
	{
		$thisDate = getNormalDate($rants[$i]['date']);
		if ($thisDate != $previousDate)
		{
			$rantsHTML .= '<h2>' . $thisDate . "</h2>\n";
			$previousDate = $thisDate;
		}
		/* Title */
		$rantsHTML .= "<div class=\"rant\">\n<h3>" . $rants[$i]['title'] . "</h3>\n";
		/* Info about the rant entry */
		$rantsHTML .= "<div class=\"info\">";
		$rantsHTML .= $rants[$i]['location'] . " | ";
		$rantsHTML .= "Posted " . getTime($rants[$i]['date']) . "</div>\n";
		/* Rant itself */
		$rantsHTML .= $rants[$i]['message'] . "\n";

		$rantsHTML .= "<div class=\"related\">";
		if (true == isLoggedIn())
		{
			$rantsHTML .= "<a href=\"root.php?action=editrant&amp;rantid=" . $rants[$i]['messageID'] . "\">Edit</a> | ";
		}
		
		if ($rants[$i]['modified'] > 0)
		{
			/* Modified at least once */
			if ($rants[$i]['modified'] == 1)
			{
				$rantsHTML .= 'Modified 1 time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
			} else
			{
				$rantsHTML .= 'Modified ' . $rants[$i]['modified'] . ' times, last time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
			}
			$rantsHTML .= " | ";
		}
		
		$commentText = "comments";
		if ($rants[$i]["nrOfComments"] == 1)
		{
			$commentText = "comment";
		}
		$rantsHTML .= "<a href=\"index.php?rantid=" . $rants[$i]['messageID'] . "\">" . $rants[$i]["nrOfComments"] . " " . $commentText . "&nbsp;&raquo;</a>";
		
		$rantsHTML .= "</div>\n";
		$rantsHTML .= "</div>\n\n";
	}
	return $rantsHTML;
}


/*
 * Builds a list with short information of all rants. Used for editing
 * $rants[$i]['messageID'] = $row[0];
 * $rants[$i]['date'] = $row[1];
 * $rants[$i]["user"] = $row[2];
 * $rants[$i]['title'] = $row[3];
 * $rants[$i]['modified'] = $row[4];
 * $rants[$i]['modifiedDate'] = $row[5];
 */
function buildRantlist($rants, $enableyear)
{
	$html = '';
	$previousDate = '0000-00-00 00:00:00';
	$previousMonth = "00";

	for ($i = 0; $i < count($rants); $i++)
	{
		$thisDate = getDay($rants[$i]['date']);
		$thisMonth = getMonth($rants[$i]['date']);
		if ($thisMonth != $previousMonth)
		{
			if ($enableyear)
			{
				$html .= '<h2>' . getYear($rants[$i]['date']) . " &gt; " . getMonthName($thisMonth) . "</h2>\n";
			} else
			{
				$html .= '<h2>' . getMonthName($thisMonth) . "</h2>\n";
			}
			$previousMonth = $thisMonth;
		}
		if ($thisDate != $previousDate)
		{
			$html .= "<div class=\"rantitem\">";
			$html .= "<div class=\"date\">" . $thisDate . "</div>";
			$previousDate = $thisDate;
		} else
		{
			$html .= "<div class=\"rantitemsamedate\">";
			$html .= "<div class=\"date\">&nbsp;</div>";
		}
		/* Title */
		$html .= "<a href=\"index.php?rantid=" . $rants[$i]['messageID'] . "\">" . $rants[$i]['title'] . "</a>";

		$html .= "</div>\n";
	}
	return $html;
}


/*
 * Build HTML for a form where the rant can be edited
 */
function buildEditRant($rant)
{
	$html  = "<h2>Rant info</h2>\n";
	
	if ('1' == $rant['ispublic'])
	{
		$ispublic = 'already published at ' . $rant['published'];
	} else
	{
		$ispublic = 'not yet published';
	}
	$html .= "<p>Initiated at " . $rant['initiated'] . ", " . $ispublic . "</p>\n";
	$html .= "<h2>Location</h2><p><input type=\"text\" name=\"location\" value=\"" . $rant['location'] . "\" size=\"30\" maxlength=\"50\"/></p>\n";
	$html .= "<h2>Title</h2><p><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"250\" value=\"" . $rant['title'] . "\"/></p>\n";
	$html .= "<h2>Rant</h2><p><textarea name=\"rant\" rows=\"30\" cols=\"80\">" . htmlentities($rant['message']) . "</textarea></p>\n";
	$html .= "<input type=\"hidden\" name=\"id\" value=\"" . $rant['messageID'] . "\"/>\n";
	$html .= "<p><input name=\"submitbtn\" value=\"Save\" type=\"submit\"/></p>\n";
	//$html .= "<p><input name=\"savebtn\" value=\"Save\" type=\"submit\"/><input name=\"submitbtn\" value=\"Publish\" type=\"submit\"/></p>\n";

	return $html;
}


/***    Blogmarks   ***/


/*
 * Build list of blogmarks from $marks
 * $marks:
 * messageID
 * date
 * user
 * ip
 * title
 * message
 * modified
 * modifiedDate
 * location
 * blogmark
 */
function buildMarks( $marks )
{
	$marksHTML = '';
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($marks); $i++)
	{
		$thisDate = getNormalDate($marks[$i]['date']);
		if ($thisDate != $previousDate)
		{
			$marksHTML .= '<h2>' . $thisDate . "</h2>\n";
			$previousDate = $thisDate;
		}
		/* Title */
		$marksHTML .= "<div class=\"blogmark\">\n<h3><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a></h3>\n";
		/* Rant itself */
		$marksHTML .= $marks[$i]['message'] . "\n";
		/* Info about the rant entry */
		$marksHTML .= "<div class=\"info\">[ ";
		if ($marks[$i]['modified'] > 0)
		{
			/* Modified at least once */
			if ($marks[$i]['modified'] == 1)
			{
				$marksHTML .= 'Modified 1 time at ' . getLongDate($marks[$i]['modifiedDate']) . " " . getTime($marks[$i]['modifiedDate']);
			} else
			{
				$marksHTML .= 'Modified ' . $marks[$i]['modified'] . ' times, last time at ' . getLongDate($marks[$i]['modifiedDate']) . " " . getTime($marks[$i]['modifiedDate']);
			}
			$marksHTML .= " | ";
		}
		$marksHTML .= "<a href=\"blogmarks.php?markid=" . $marks[$i]["id"] . "\">blogmark</a> | ";
		$marksHTML .= $marks[$i]['location'] . " | ";
		$marksHTML .= "Posted " . getTime($marks[$i]['date']) . " ]</div>\n</div>\n\n";
	}
	return $marksHTML;
}

function buildInPostMarks( $marks )
{
	$marksHTML = '';

	for ($i = 0; $i < count($marks); $i++)
	{
		/* Title */
		$marksHTML .= "<h3><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a></h3>\n";
		/* Rant itself */
		$marksHTML .= $marks[$i]['message'] . "\n";
	}
	return $marksHTML;
}

function buildCondensedMarks( $marks )
{
	$marksHTML = '';
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($marks); $i++)
	{
		$marksHTML .= "<div id=\"uri" . $marks[$i]['id'] . "\"><div class=\"blogmark\">\n";
		/* Title */
		$marksHTML .= "<h3><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a></h3>\n";
		/* Comment on the blogmark */
		$marksHTML .= $marks[$i]['message'] . "\n";
		/* Info about the rant entry */
		$marksHTML .= "<div class=\"info\">[ ";
		if ($marks[$i]['modified'] > 0)
		{
			/* Modified at least once */
			if ($marks[$i]['modified'] == 1)
			{
				$marksHTML .= 'Modified 1 time at ' . getLongDate($marks[$i]['modifiedDate']) . " " . getTime($marks[$i]['modifiedDate']);
			} else
			{
				$marksHTML .= 'Modified ' . $marks[$i]['modified'] . ' times, last time at ' . getLongDate($marks[$i]['modifiedDate']) . " " . getTime($marks[$i]['modifiedDate']);
			}
			$marksHTML .= " | ";
		}
		$marksHTML .= $marks[$i]['location'];
		$marksHTML .= " | <a href=\"" . markUri($marks[$i]) . "\">" . getMonthDate($marks[$i]['date']) . ", " . getTime($marks[$i]['date']) . "</a>";
		$marksHTML .= " ]</div>\n";
		$marksHTML .= "</div></div>\n\n";
	}
	return $marksHTML;
}


/*
 * Build a simple list of webmarks [used for listing NN marks in the navigation]
 */
function buildSimpleMarks( $marks )
{
	$marksHTML = "\t\t<ul>\n";
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($marks); $i++)
	{
		//$marksHTML .= "\t\t\t<li><a href=\"" . markuri($marks[$i]) . "\">" . $marks[$i]['title'] . "</a></li>\n";
		$marksHTML .= "\t\t\t<li><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a> <span class=\"note\">[<a href=\"" . markUri($marks[$i]) . "\">more</a>]</span></li>\n";
	}
	$marksHTML .= "\t\t</ul>\n";
	return $marksHTML;
}



/*
 * Builds a list of comments on a rant. Newest on top [already done]
 *
 * $comments
id
rantId
date
ip
client
name
email
uri
message
 */
function buildComments( $comments )
{
	$commentsHTML = '';

	for ($i = 0; $i < count($comments); $i++)
	{
		$uri = $comments[$i]["name"];
		$message = str_replace("\n", "<br/>\n", $comments[$i]['message']);
		if ($comments[$i]['uri'] != '')
		{
			$uri = "<a href=\"" . $comments[$i]['uri'] . "\">" . $comments[$i]["name"] . "</a>";
		}
		$commentsHTML .= "<div id=\"comment" . $comments[$i]["id"] . "\"><div class=\"comment\">\n<div class=\"comment_info\">";
		if ($comments[$i]["state"] == 0)
		{
			$commentsHTML .= "<span class=\"comment_nr_disabled\">";
		} else
		{
			$commentsHTML .= "<span class=\"comment_nr\">";
		}
		$commentsHTML .= ($i + 1) . ".&nbsp;</span><span class=\"comment_datestamp\">Posted at " . $comments[$i]['date'] . "</span>&nbsp;<span class=\"comment_name\">by " . $uri . "</span>";
		$commentsHTML .= " [ <a href=\"?rantid=" . $comments[$i]["rantId"] . "#comment" . $comments[$i]["id"] . "\">Permalink</a> ";

		if ( isLoggedIn() )
		{
			// delete button -> state = 0
			$commentsHTML .= "| Notify ";
			if ($comments[$i]["wantnotifications"] == 0)
			{
				$commentsHTML .= "off ";
			} else
			{
				$commentsHTML .= "on ";
			}

			if ($comments[$i]["state"] == 0)
			{
				$commentsHTML .= "| <a href=\"root.php?action=enablecomment&amp;commentid=" . $comments[$i]["id"] . "\">Show comment in list</a> ";
			} else
			{
				$commentsHTML .= "| <a href=\"root.php?action=disablecomment&amp;commentid=" . $comments[$i]["id"] . "\">Hide comment from list</a> ";
			}
		}
		$commentsHTML .= "]</div>\n";

		$commentsHTML .= "<div class=\"comment_message\">" . $message . "</div>\n</div></div>";
	
	}

	return $commentsHTML;
}


/***   Feeds   ***/

/*
 * Generic function for generating RSS v2.0 feeds
 */
function generateFeed($skel, $filename, $feedtitle, $body)
{
	$feed  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$feed .= "<rss version=\"2.0\"\n";
	$feed .= "\txmlns:dc=\"http://puri.org/dc/elements/1.1/\"\n";
	$feed .= "\txmlns:sy=\"http://puri.org/rss/1.0/modules/syndication/\"\n";
	$feed .= "\txmlns:admin=\"http://webns.net/mvcb/\"\n";
	$feed .= "\txmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
	$feed .= "\txmlns:content=\"http://puri.org/rss/1.0/modules/content/\">\n";


	$feed .= "\t<channel>\n";
	$feed .= "\t\t<title>" . $feedtitle . "</title>\n";
	$feed .= "\t\t<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	$feed .= "\t\t<description>" . $skel["sitedescription"] . "</description>\n";

	$feed .= "\t\t<image>\n";
	$feed .= "\t\t\t<title>" . $skel["sitename"] . "</title>\n";
	$feed .= "\t\t\t<url>http://" . $skel["servername"] . $skel["baseHref"] . $skel["logo"] . "</url>\n";
	$feed .= "\t\t\t<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	$feed .= "\t\t\t<width>" . $skel["logoWidth"] . "</width>\n";
	$feed .= "\t\t\t<height>" . $skel["logoHeight"] . "</height>\n";
	$feed .= "\t\t\t<description>" . $skel["sitename"] . "</description>\n";
	$feed .= "\t\t</image>\n";

	$feed .= "\t\t<dc:language>en-us</dc:language>\n";
	$feed .= "\t\t<dc:rights>Copyright " . $skel["startyear"] . "-" . date("Y") . " " .$skel["author"] . "</dc:rights>\n";
	$feed .= "\t\t<dc:creator>" . $skel["rssEmail"] . "</dc:creator>\n";
	$feed .= "\t\t<dc:date>" . date('Y-m-d\TH:i:s+01:00') . "</dc:date>\n";
	$feed .= "\t\t<admin:generatorAgent rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "\" />\n";
	$feed .= "\t\t<sy:updatePeriod>hourly</sy:updatePeriod>\n";
	$feed .= "\t\t<sy:updateFrequency>1</sy:updateFrequency>\n";
	$feed .= "\t\t<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>\n";

	$feed .= $body;

	$feed .= "\t</channel>\n";
	$feed .= "</rss>\n";
	//$file = fopen($skel["rssFilename"], "w");
	$file = fopen($filename, "w");
	fputs($file, $feed);
	fclose($file);
}


/*
 * Writes a feed with the latest $nrOfRantsPerPage postings to a hardcoded blog.rss file
 *
 * $rants:
 * id
 * date
 * user
 * ip
 * title
 * uri
 * location
 * message
 * modified
 * modifiedDate
 */
function updateWeblogFeed($skel, $rants)
{
	//$feed = '';
	$feed = "\t\t<items>\n\t\t\t<rdf:Seq>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t\t\t\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "\" />\n";
	}
	$feed .= "\t\t\t</rdf:Seq>\n\t\t</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		//$feed .= "<item rdf:about=\"" . $skel_base_href . "index.php?rantid=" . $rants[$i]['messageID'] . "\">\n";
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "\t\t\t<link>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "</link>\n";
		//$feed .= "<description>" . $rants[$i]['message'] . "</description>\n";
		//$feed .= "<description></description>\n";
		$feed .= "\t\t\t<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>]]></content:encoded>\n";
		//$feed .= "<description>" . strip_tags($rants[$i]['message']) . "</description>\n";
		//$feed .= "<description>blah</description>\n";
		$feed .= "\t\t\t<dc:subject></dc:subject>\n";
		$feed .= "\t\t\t<dc:creator>" . $skel["authorShortname"] . "</dc:creator>\n";
		$feed .= "\t\t\t<comments>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "#comments</comments>\n";
		//<dc:date>2004-02-26T01:17:33+01:00</dc:date>
		$feed .= "\t\t\t<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "\t\t</item>\n";
	}
	/* now generate the file */
	generateFeed($skel, $skel["rssFilename"], $skel["sitename"], $feed);
}


/*
 * Same as generateBlogFeed, but now with comments too
 */
function updateWeblogCommentsFeed($skel, $rants)
{
	$feed  = "\t\t<items>\n\t\t\t<rdf:Seq>\n";

	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t\t\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "\" />\n";
	}
	$feed .= "\t\t\t</rdf:Seq>\n\t\t</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$rantcomments = '';
		$comments = getAllComments($skel, $rants[$i]['messageID'], false);
		if (count($comments) > 0)
		{
			$rantcomments = "<br /><hr /><p><b>Comments:</b></p>\n";
			for ($j = 0; $j < count($comments); $j++)
			{
				$rantcomments .= "<div>";
				if ($comments[$j]['uri'] != '')
				{
					$rantcomments .= "<a href=\"" . $comments[$j]['uri'] . "\">" . $comments[$j]["name"] . "</a>";
				} else
				{
					$rantcomments .= $comments[$j]["name"];
				}
				$message  = str_replace("\n", "<br />\n", $comments[$j]['message']);
				$message .= "<br />";
				//$rantcomments .= " @ " . $comments[$j]['date'] . ":<font size=-1><p>" . $message . "</p></font>\n";
				$rantcomments .= " @ " . $comments[$j]['date'] . ":<p>" . $message . "</p></div>\n";
			}
		}
		//$feed .= "<item rdf:about=\"" . $skel_base_href . "index.php?rantid=" . $rants[$i]['messageID'] . "\">\n";
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "\t\t\t<link>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "</link>\n";
		//$feed .= "<description>" . $rants[$i]['message'] . "</description>\n";
		//$feed .= "<description></description>\n";
		$feed .= "\t\t\t<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>" . $rantcomments . "]]></content:encoded>\n";
		$feed .= "\t\t\t<dc:subject></dc:subject>\n";
		$feed .= "\t\t\t<dc:creator>" . $skel["authorShortname"] . "</dc:creator>\n";
		$feed .= "\t\t\t<comments>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "#comments</comments>\n";
		//<dc:date>2004-02-26T01:17:33+01:00</dc:date>
		$feed .= "\t\t\t<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "\t\t</item>\n";
	}
	/* now generate the file */
	generateFeed($skel, $skel["rssWithCommentsFilename"], $skel["sitename"] . " [with comments]", $feed);
}


/*
 * Writes a feed with the latest $nrOfMarksPerPage postings to a hardcoded blogmarks.rss file
 *
 * $marks:
 * id
 * date
 * user
 * ip
 * title
 * uri
 * location
 * message
 * modified
 * modifiedDate
 */
function updateWebmarksFeed($skel, $marks)
{
	$feed  = "\t\t<items>\n\t\t\t<rdf:Seq>\n";
	for ($i = 0; $i < count($marks); $i++)
	{
		$feed .= "\t\t\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "blogmarks.php?markid=" . $marks[$i]["id"] . "\" />\n";
	}
	$feed .= "\t\t\t</rdf:Seq>\n\t\t</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($marks); $i++)
	{
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $marks[$i]['title'] . "</title>\n";
		/* Atom links, usable in the future when I come around to implement an Atom feed
		$feed .= "<link rel=\"alternate\" type=\"application/xhtml+xml\" href=\"" . $skel["baseHref"] . markuri($marks[$i]) . "\" />\n";
		$feed .= "<link rel=\"related\" type=\"text/html\" href=\"" . $marks[$i]['uri'] . "\" />\n";
		*/
		$feed .= "\t\t\t<link>" . $marks[$i]['uri'] . "</link>\n";
		$feed .= "\t\t\t<content:encoded><![CDATA[" . $marks[$i]['message'] . "<p>Location: " . $marks[$i]['location'] . "</p>]]></content:encoded>\n";
		$feed .= "\t\t\t<dc:subject></dc:subject>\n";
		$feed .= "\t\t\t<dc:creator>" . $skel["authorShortname"] . "</dc:creator>\n";
		$feed .= "\t\t\t<dc:date>" . getRSSDateTime($marks[$i]['date']) . "</dc:date>\n";
		$feed .= "\t\t</item>\n";
	}
	/* now generate the file */
	generateFeed($skel, $skel["rssMarksFilename"], $skel["sitename"] . " blogmarks", $feed);
}




/*
 * Writes a feed with the latest $nrOfRantsPerPage postings to a hardcoded blog.rss file
 *
 * $rants:
 * id
 * date
 * user
 * ip
 * title
 * uri
 * location
 * message
 * modified
 * modifiedDate
 */
function generateBlogFeed($rants, $skel)
{
	$feed  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$feed .= "<rss version=\"2.0\"\n";
	$feed .= "xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n";
	$feed .= "xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n";
	$feed .= "xmlns:admin=\"http://webns.net/mvcb/\"\n";
	$feed .= "xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
	$feed .= "xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\n";


	$feed .= "<channel>\n";
	//$feed .= "<title>dammIT</title>\n";
	$feed .= "<title>" . $skel["sitename"] . "</title>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	//$feed .= "<description>dammIT - rantbox of Michiel Scholten</description>\n";
	$feed .= "<description>" . $skel["sitedescription"] . "</description>\n";

	$feed .= "<image>\n";
	$feed .= "<title>" . $skel["sitename"] . "</title>\n";
	$feed .= "<url>http://" . $skel["servername"] . $skel["baseHref"] . $skel["logo"] . "</url>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	$feed .= "<width>176</width>\n";
	$feed .= "<height>71</height>\n";
	$feed .= "<description>" . $skel["sitename"] . "</description>\n";
	$feed .= "</image>\n";

	$feed .= "<dc:language>en-us</dc:language>\n";
	//$feed .= "<dc:rights>Copyright 2003-2004 Michiel Scholten</dc:rights>\n";
	$feed .= "<dc:rights>Copyright " . $skel["startyear"] . "-" . date("Y") . " " .$skel["author"] . "</dc:rights>\n";
	$feed .= "<dc:creator>" . $skel["rssEmail"] . "</dc:creator>\n";
	$feed .= "<dc:date>" . date('Y-m-d\TH:i:s+01:00') . "</dc:date>\n";
	$feed .= "<admin:generatorAgent rdf:resource=\"" . $skel["servername"] . $skel["baseHref"] . "\" />\n";
	$feed .= "<sy:updatePeriod>hourly</sy:updatePeriod>\n";
	$feed .= "<sy:updateFrequency>1</sy:updateFrequency>\n";
	$feed .= "<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>\n";


	$feed .= "<items>\n\t<rdf:Seq>\n";
	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "\" />\n";
	}
	$feed .= "\t</rdf:Seq>\n</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		//$feed .= "<item rdf:about=\"" . $skel_base_href . "index.php?rantid=" . $rants[$i]['messageID'] . "\">\n";
		$feed .= "<item>\n";
		$feed .= "<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "</link>\n";
		//$feed .= "<description>" . $rants[$i]['message'] . "</description>\n";
		//$feed .= "<description></description>\n";
		$feed .= "<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>]]></content:encoded>\n";
		$feed .= "<dc:subject></dc:subject>\n";
		$feed .= "<dc:creator>Michiel</dc:creator>\n";
		$feed .= "<description>" . striptags($rants[$i]['message']) . "</description>\n";
		$feed .= "<comments>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "#comments</comments>\n";
		//<dc:date>2004-02-26T01:17:33+01:00</dc:date>
		$feed .= "<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "</item>\n";
	}

	$feed .= "\t\n</channel>\n";

	$feed .= "</rss>\n";
	$file = fopen($skel["rssFilename"], "w");
	fputs($file, $feed);
	fclose($file);
}


/*
 * Same as generateBlogFeed, but now with comments too
 */
function generateBlogWithCommentsFeed($rants, $skel)
{
	$feed  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$feed .= "<rss version=\"2.0\"\n";
	$feed .= "xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n";
	$feed .= "xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n";
	$feed .= "xmlns:admin=\"http://webns.net/mvcb/\"\n";
	$feed .= "xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
	$feed .= "xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\n";


	$feed .= "<channel>\n";
	//$feed .= "<title>dammIT [with comments]</title>\n";
	$feed .= "<title>" . $skel["sitename"] . " [with comments]</title>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	//$feed .= "<description>dammIT - rantbox of Michiel Scholten</description>\n";
	$feed .= "<description>" . $skel["sitedescription"] . "</description>\n";

	$feed .= "<image>\n";
	$feed .= "<title>" . $skel["sitename"] . "</title>\n";
	$feed .= "<url>http://" . $skel["servername"] . $skel["baseHref"] . $skel["logo"] . "</url>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	$feed .= "<width>176</width>\n";
	$feed .= "<height>71</height>\n";
	$feed .= "<description>" . $skel["sitename"] . "</description>\n";
	$feed .= "</image>\n";

	$feed .= "<dc:language>en-us</dc:language>\n";
	$feed .= "<dc:rights>Copyright " . $skel["startyear"] . "-" . date("Y") . " " .$skel["author"] . "</dc:rights>\n";
	//$feed .= "<dc:creator>mbscholt@aquariusoft.notforspambastards.org</dc:creator>\n";
	$feed .= "<dc:creator>" . $skel["rssEmail"] . "</dc:creator>\n";
	$feed .= "<dc:date>" . date('Y-m-d\TH:i:s+01:00') . "</dc:date>\n";
	$feed .= "<admin:generatorAgent rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "\" />\n";
	$feed .= "<sy:updatePeriod>hourly</sy:updatePeriod>\n";
	$feed .= "<sy:updateFrequency>1</sy:updateFrequency>\n";
	$feed .= "<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>\n";


	$feed .= "<items>\n\t<rdf:Seq>\n";
	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "\" />\n";
	}
	$feed .= "\t</rdf:Seq>\n</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$rantcomments = '';
		$comments = getAllComments($skel, $rants[$i]['messageID'], false);
		if (count($comments) > 0)
		{
			$rantcomments .= "<div>";
			$rantcomments = "<br /><hr /><p><b>Comments:</b></p>\n";
			for ($j = 0; $j < count($comments); $j++)
			{
				if ($comments[$j]['url'] != '')
				{
					$rantcomments .= "<a href=\"" . $comments[$j]['url'] . "\">" . $comments[$j]["name"] . "</a>";
				} else
				{
					$rantcomments .= $comments[$j]["name"];
				}
				$message  = str_replace("\n", "<br />\n", $comments[$j]['message']);
				$message .= "<br />";
				//$rantcomments .= " @ " . $comments[$j]['date'] . ":<font size=-1><p>" . $message . "</p></font>";
				$rantcomments .= " @ " . $comments[$j]['date'] . ":<p>" . $message . "</p></div>\n";
			}
		}
		//$feed .= "<item rdf:about=\"" . $skel_base_href . "index.php?rantid=" . $rants[$i]['messageID'] . "\">\n";
		$feed .= "<item>\n";
		$feed .= "<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "</link>\n";
		//$feed .= "<description>" . $rants[$i]['message'] . "</description>\n";
		//$feed .= "<description></description>\n";
		$feed .= "<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>" . $rantcomments . "]]></content:encoded>\n";
		$feed .= "<dc:subject></dc:subject>\n";
		$feed .= "<dc:creator>Michiel</dc:creator>\n";
		$feed .= "<comments>http://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rants[$i]['messageID'] . "#comments</comments>\n";
		//<dc:date>2004-02-26T01:17:33+01:00</dc:date>
		$feed .= "<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "</item>\n";
	}

	$feed .= "\t\n</channel>\n";
	$feed .= "</rss>\n";

	$file = fopen($skel["rssWithCommentsFilename"], "w");
	fputs($file, $feed);
	fclose($file);
}


/*
 * Writes a feed with the latest $nrOfMarksPerPage postings to a hardcoded blogmarks.rss file
 *
 * $marks:
 * id
 * date
 * user
 * ip
 * title
 * url
 * location
 * message
 * modified
 * modifiedDate
 */
function generateBlogmarkFeed($marks, $skel)
{
	$feed  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$feed .= "<rss version=\"2.0\"\n";
	$feed .= "xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n";
	$feed .= "xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n";
	$feed .= "xmlns:admin=\"http://webns.net/mvcb/\"\n";
	$feed .= "xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
	$feed .= "xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\n";

	$feed .= "<channel>\n";
	//$feed .= "<title>dammIT blogmarks</title>\n";
	$feed .= "<title>" . $skel["sitename"] . " blogmarks</title>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	//$feed .= "<description>dammIT - rantbox of Michiel Scholten</description>\n";
	$feed .= "<description>" . $skel["sitedescription"] . "</description>\n";

	$feed .= "<image>\n";
	//$feed .= "<title>dammIT</title>\n";
	$feed .= "<title>" . $skel["sitename"] . "</title>\n";
	$feed .= "<url>http://" . $skel["servername"] . $skel["baseHref"] . $skel["logo"] . "</url>\n";
	$feed .= "<link>http://" . $skel["servername"] . $skel["baseHref"] . "</link>\n";
	$feed .= "<width>176</width>\n";
	$feed .= "<height>71</height>\n";
	//$feed .= "<description>Rantbox of Michiel Scholten</description>\n";
	$feed .= "<description>" . $skel["sitename"] . "</description>\n";
	$feed .= "</image>\n";

	$feed .= "<dc:language>en-us</dc:language>\n";
	//$feed .= "<dc:rights>Copyright 2003-2004 Michiel Scholten</dc:rights>\n";
	$feed .= "<dc:rights>Copyright " . $skel["startyear"] . "-" . date("Y") . " " .$skel["author"] . "</dc:rights>\n";
	//$feed .= "<dc:creator>mbscholt@aquariusoft.notforspambastards.org</dc:creator>\n";
	$feed .= "<dc:creator>" . $skel["rssEmail"] . "</dc:creator>\n";
	$feed .= "<dc:date>" . date('Y-m-d\TH:i:s+01:00') . "</dc:date>\n";
	$feed .= "<admin:generatorAgent rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "\" />\n";
	$feed .= "<sy:updatePeriod>hourly</sy:updatePeriod>\n";
	$feed .= "<sy:updateFrequency>1</sy:updateFrequency>\n";
	$feed .= "<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>\n";

	$feed .= "<items>\n\t<rdf:Seq>\n";
	for ($i = 0; $i < count($marks); $i++)
	{
		$feed .= "\t<rdf:li rdf:resource=\"http://" . $skel["servername"] . $skel["baseHref"] . "blogmarks.php?markid=" . $marks[$i]["id"] . "\" />\n";
	}
	$feed .= "\t</rdf:Seq>\n</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($marks); $i++)
	{
		$feed .= "<item>\n";
		$feed .= "<title>" . $marks[$i]['title'] . "</title>\n";
		/* Atom links, usable in the future when I come around to implement an Atom feed
		$feed .= "<link rel=\"alternate\" type=\"application/xhtml+xml\" href=\"" . $skel["baseHref"] . markurl($marks[$i]) . "\" />\n";
		$feed .= "<link rel=\"related\" type=\"text/html\" href=\"" . $marks[$i]['url'] . "\" />\n";
		*/
		$feed .= "<link>" . $marks[$i]['url'] . "</link>\n";
		$feed .= "<content:encoded><![CDATA[" . $marks[$i]['message'] . "<p>Location: " . $marks[$i]['location'] . "</p>]]></content:encoded>\n";
		$feed .= "<dc:subject></dc:subject>\n";
		$feed .= "<dc:creator>Michiel</dc:creator>\n";
		$feed .= "<dc:date>" . getRSSDateTime($marks[$i]['date']) . "</dc:date>\n";
		$feed .= "</item>\n";
	}

	$feed .= "\t\n</channel>\n";

	$feed .= "</rss>\n";

	$file = fopen($skel["rssMarksFilename"], "w");
	fputs($file, $feed);
	fclose($file);
}

?>
