<?php
/**
 * Blog module - HTML methods
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
	$rawhtml = '';
	$markdown = '';
	$plaintext = '';
	if (CONTENT_RAWHTML == $rant['contenttype'])
	{
		$rawhtml = ' selected';
	} else if (CONTENT_MARKDOWN == $rant['contenttype'])
	{
		$markdown = ' selected';
	} else
	{
		$plaintext = ' selected';
	}
	$html .= "<p><select name=\"contenttype\">\n\t<option value=\"" . CONTENT_RAWHTML . "\"" . $rawhtml . ">Raw HTML</option>\n\t<option value=\"" . CONTENT_MARKDOWN . "\"" . $markdown . ">Markdown markup</option>\n\t<option value=\"" . CONTENT_PLAINTEXT . "\"" . $plaintext . ">Plain text</option>\n</select>\n";
	$public_selected = '';
	$hidden_selected = '';
	if (ISPUBLIC_NO == $rant['ispublic'])
	{
		$hidden_selected = ' selected';
	} else if (ISPUBLIC_YES == $rant['ispublic'])
	{
		$public_selected = ' selected';
	}
	$html .= "<select name=\"ispublic\">\n\t<option value=\"" . ISPUBLIC_NO . "\"" . $hidden_selected . ">Rant is hidden for the public</option>\n\t<option value=\"" . ISPUBLIC_YES . "\"" . $public_selected . ">Rant is public</option>\n</select></p>\n";
	$html .= "<input type=\"hidden\" name=\"id\" value=\"" . $rant['messageID'] . "\"/>\n";
	$html .= "<input type=\"hidden\" name=\"initiated\" value=\"" . $rant['initiated'] . "\"/>\n";
	$html .= "<p><input name=\"submitbtn\" value=\"Save\" type=\"submit\"/></p>\n";

	return $html;
}


/***   Feeds   ***/

/*
 * Generic function for generating RSS v2.0 feeds
 */
function generateFeed($skel, $filename, $feedtitle, $body)
{
	$feed  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$feed .= "<rss version=\"2.0\"\n";
	$feed .= "\txmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n";
	$feed .= "\txmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n";
	$feed .= "\txmlns:admin=\"http://webns.net/mvcb/\"\n";
	$feed .= "\txmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
	$feed .= "\txmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\n";


	$feed .= "\t<channel>\n";
	$feed .= "\t\t<title>" . $feedtitle . "</title>\n";
	$feed .= "\t\t<link>http://" . $skel['servername'] . $skel['baseHref'] . "</link>\n";
	$feed .= "\t\t<description>" . $skel["feedDescription"] . "</description>\n";

	$feed .= "\t\t<image>\n";
	$feed .= "\t\t\t<title>" . $skel["siteName"] . "</title>\n";
	$feed .= "\t\t\t<url>http://" . $skel['servername'] . $skel['baseHref'] . $skel["logo"] . "</url>\n";
	$feed .= "\t\t\t<link>http://" . $skel['servername'] . $skel['baseHref'] . "</link>\n";
	$feed .= "\t\t\t<width>" . $skel["logoWidth"] . "</width>\n";
	$feed .= "\t\t\t<height>" . $skel["logoHeight"] . "</height>\n";
	$feed .= "\t\t\t<description>" . $skel["siteName"] . "</description>\n";
	$feed .= "\t\t</image>\n";

	$feed .= "\t\t<dc:language>en-us</dc:language>\n";
	$feed .= "\t\t<dc:rights>Copyright " . $skel["startyear"] . "-" . date("Y") . " " .$skel["author"] . "</dc:rights>\n";
	$feed .= "\t\t<dc:creator>" . $skel["rssEmail"] . "</dc:creator>\n";
	$feed .= "\t\t<dc:date>" . date('Y-m-d\TH:i:s+01:00') . "</dc:date>\n";
	$feed .= "\t\t<admin:generatorAgent rdf:resource=\"http://" . $skel['servername'] . $skel['baseHref'] . "\" />\n";
	$feed .= "\t\t<sy:updatePeriod>hourly</sy:updatePeriod>\n";
	$feed .= "\t\t<sy:updateFrequency>1</sy:updateFrequency>\n";
	$feed .= "\t\t<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>\n";

	$feed .= $body;

	$feed .= "\t</channel>\n";
	$feed .= "</rss>\n";
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
		$feed .= "\t\t\t\t<rdf:li rdf:resource=\"http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "\" />\n";
	}
	$feed .= "\t\t\t</rdf:Seq>\n\t\t</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "\t\t\t<link>http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "</link>\n";
		$feed .= "\t\t\t<guid>http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "</guid>\n";
		$feed .= "\t\t\t<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>]]></content:encoded>\n";
		$feed .= "\t\t\t<dc:subject></dc:subject>\n";
		$feed .= "\t\t\t<dc:creator>" . $skel["authorShortname"] . "</dc:creator>\n";
		$feed .= "\t\t\t<comments>http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "#comments</comments>\n";
		$feed .= "\t\t\t<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "\t\t</item>\n";
	}
	/* now generate the file */
	generateFeed($skel, $skel["rssFilename"], $skel["siteName"], $feed);
}


/*
 * Same as generateBlogFeed, but now with comments too
 */
function updateWeblogCommentsFeed($skel, $rants)
{
	$feed  = "\t\t<items>\n\t\t\t<rdf:Seq>\n";

	for ($i = 0; $i < count($rants); $i++)
	{
		$feed .= "\t\t\t<rdf:li rdf:resource=\"http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "\" />\n";
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
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $rants[$i]['title'] . "</title>\n";
		$feed .= "\t\t\t<link>http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "</link>\n";
		$feed .= "\t\t\t<content:encoded><![CDATA[" . $rants[$i]['message'] . "<p>Location: " . $rants[$i]['location'] . "</p>" . $rantcomments . "]]></content:encoded>\n";
		$feed .= "\t\t\t<dc:subject></dc:subject>\n";
		$feed .= "\t\t\t<dc:creator>" . $skel["authorShortname"] . "</dc:creator>\n";
		$feed .= "\t\t\t<comments>http://" . $skel['servername'] . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "#comments</comments>\n";
		$feed .= "\t\t\t<dc:date>" . getRSSDateTime($rants[$i]['date']) . "</dc:date>\n";
		$feed .= "\t\t</item>\n";
	}
	/* now generate the file */
	generateFeed($skel, $skel["rssWithCommentsFilename"], $skel["siteName"] . " [with comments]", $feed);
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
		$feed .= "\t\t\t<rdf:li rdf:resource=\"http://" . $skel['servername'] . $skel['baseHref'] . "blogmarks.php?markid=" . $marks[$i]["id"] . "\" />\n";
	}
	$feed .= "\t\t\t</rdf:Seq>\n\t\t</items>\n";

	/* count($rants) should be $nrOfRantsPerPage */
	for ($i = 0; $i < count($marks); $i++)
	{
		$feed .= "\t\t<item>\n";
		$feed .= "\t\t\t<title>" . $marks[$i]['title'] . "</title>\n";
		/* Atom links, usable in the future when I come around to implement an Atom feed
		$feed .= "<link rel=\"alternate\" type=\"application/xhtml+xml\" href=\"" . $skel['baseHref'] . markuri($marks[$i]) . "\" />\n";
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
	generateFeed($skel, $skel["rssMarksFilename"], $skel["siteName"] . " blogmarks", $feed);
}
