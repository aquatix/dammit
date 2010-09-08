<?php
/**
 * Blog module - HTML methods
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
function buildRants( $skel, $rants, $groupedOnDate = false )
{
	$rantsHTML = '';
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($rants); $i++)
	{
		$thisDate = getNormalDate($rants[$i]['date']);
		if ($groupedOnDate && $thisDate != $previousDate)
		{
			if ('0000-00-00 00:00:00' != $previousDate)
			{
				$rantsHTML .= "</div>\n";
			}
			$rantsHTML .= '<h2>' . $thisDate . "</h2>\n";
			$rantsHTML .= "<div class=\"grouped\">\n";
			$previousDate = $thisDate;
		}
		$blogmarksContent = false;
		/* Title */
		//$rantsHTML .= '<h3>' . $rants[$i]['title'] . "</h3>\n";
		if ($groupedOnDate)
		{
			$rantsHTML .= '<h3 class="ranttitle"><a href="' . $skel['baseHref'] . 'p/' . $rants[$i]['messageID'] . '">' . $rants[$i]['title'] . "</a></h3>\n";
		} else
		{
			$rantsHTML .= '<h2 class="ranttitle"><a href="' . $skel['baseHref'] . 'p/' . $rants[$i]['messageID'] . '">' . $rants[$i]['title'] . "</a></h2>\n";
		}
		if (count($rants) > 1 && substr($rants[$i]['title'], 0, 13) == 'Blogmarks for')
		{
			$blogmarksContent = true;
		}
		/* Info about the rant entry */
		$rantsHTML .= "<div class=\"info\">";
		$rantsHTML .= $rants[$i]['location'] . " | ";
		//if (isLoggedIn())
		//{
		//	$rantsHTML .= "Posted " . getTime($rants[$i]['date']) . " | Watched " . getNumberOfViews( $skel, 'home', 'posting.' . $rants[$i]['messageID']) . " times</div>\n";
		//} else
		//{
			$postedDate = '';
			if (!$groupedOnDate)
			{
				$postedDate = 'on ' . $thisDate . ' at ';
			}
			$rantsHTML .= "Posted " . $postedDate . getTime($rants[$i]['date']) . "</div>\n";
		//}
		//$rantsHTML .= "Posted " . getTime($rants[$i]['date']) . " | Watched " . $rants[$i]['nrviews'] . " times</div>\n";
		/* Rant itself */
		$rantsHTML .= "<div class=\"rant\">\n";
		if ($blogmarksContent) { $rantsHTML .= '<div id="posting' . $rants[$i]['messageID'] . '" class="weeklyblogmarks">'; }
		if (CONTENT_MARKDOWN == $rants[$i]['contenttype'])
		{
			$rantsHTML .= Markdown($rants[$i]['message']) . "\n";
		}
		else if (CONTENT_PLAINTEXT == $rants[$i]['contenttype'])
		{
			$rantsHTML .= plaintext2HTML($rants[$i]['message']) . "\n";
		} else
		{
			$rantsHTML .= $rants[$i]['message'] . "\n";
		}
		if ($blogmarksContent) { $rantsHTML .= '</div>'; }

		$rantsHTML .= "<div class=\"related\">";
		if (true == isLoggedIn())
		{
			$rantsHTML .= "<a href=\"root.php?action=editrant&amp;rantid=" . $rants[$i]['messageID'] . "\">Edit</a> | ";
		}

		$rantsHTML .= 'Posted by ' . $rants[$i]['username'] . ' | ';

		//$rantsHTML .= '<div><a href="http://www.technorati.com/search/' . 'http://aquariusoft.org/~mbscholt/index.php' . '?rantid=' . $rants[$i]['messageID'] . '"><img src="images/technorati_link.gif" alt="Search for related articles" title="Search for related articles" /></a></div>';
		//$rantsHTML .= '<a href="http://www.technorati.com/search/' . 'http://aquariusoft.org/~mbscholt/index.php' . '?rantid=' . $rants[$i]['messageID'] . '" title="Search for related articles"><img src="images/technorati_related.gif" alt="Search for related articles" /></a> | ';
		$rantsHTML .= '<a href="http://www.technorati.com/search/' . 'http://dammit.nl/' . $skel['baseHref'] . 'p/' . $rants[$i]['messageID'] . '" title="Search for related articles"><img src="images/technorati_related.gif" alt="Search for related articles" /></a> | ';

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
			$rantsHTML .= ' | ';
		}
		
		$commentText = 'comments';
		if ($rants[$i]['nrOfComments'] == 1)
		{
			$commentText = 'comment';
		}
		if (0 == $rants[$i]['commentsenabled'])
		{
			$rantsHTML .= '<span class="strike" title="Commenting has been disabled for this post">';
		}
		$rantsHTML .= '<a href="' . $skel['baseHref'] . 'p/' . $rants[$i]['messageID'] . '#comments">' . $rants[$i]['nrOfComments'] . ' ' . $commentText . '&nbsp;&raquo;</a>';
		if (0 == $rants[$i]['commentsenabled'])
		{
			$rantsHTML .= '</span>';
		}
		
		$rantsHTML .= "</div>\n";
		$rantsHTML .= "</div>\n\n";
	}
	/* Close the grouped div */
	if ($groupedOnDate)
	{
		$rantsHTML .= "</div>\n";
	}
	return $rantsHTML;
}


/*
 * Builds a list with short information of all rants. Used for archive and search
 */
function buildRantlist($skel, $rants, $enableyear)
{
	$html = '';
	$previousDate = '0000-00-00 00:00:00';
	$previousMonth = '00';

	for ($i = 0; $i < count($rants); $i++)
	{
		$thisDate = getDay($rants[$i]['date']);
		$thisMonth = getMonth($rants[$i]['date']);
		if ($thisMonth != $previousMonth)
		{
			if ('00' != $previousMonth)
			{
				$html .= "</ul>\n";
			}
			if ($enableyear)
			{
				$html .= '<h2>' . getYear($rants[$i]['date']) . " &gt; " . getMonthName($thisMonth) . "</h2>\n";
			} else
			{
				$html .= '<h2><a href="' . $skel['baseHref'] . 'p/archive/month/' . getYear($rants[$i]['date']) . $thisMonth . '">' . getMonthName($thisMonth) . "</a></h2>\n";
			}
			$html .= "<ul class=\"archive\">\n";
			$previousMonth = $thisMonth;
		}
		if ($thisDate != $previousDate)
		{
			$html .= '<li><span class="date">' . $thisDate . ' : </span>';
			$previousDate = $thisDate;
		} else
		{
			$html .= '<li><span class="date">&nbsp;</span>';
		}
		/* Title */
		$html .= "<a href=\"" . $skel['baseHref'] . "p/" . $rants[$i]['messageID'] . "\">" . $rants[$i]['title'] . "</a>";

		$html .= "</li>\n";
	}
	$html .= "</ul>\n";
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
		$marksHTML .= "<div id=\"uri" . $marks[$i]['id'] . "\">\n";
		/* Title */
		$marksHTML .= "<h3><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a></h3>\n";
		/* Info about the blogmark */
		$marksHTML .= '<div class="info">';
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
		$marksHTML .= "</div>\n";
		/* Comment on the blogmark */
		$marksHTML .= $marks[$i]['message'] . "\n";
		$marksHTML .= "</div>\n\n";
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


/*
 * Build a simple list of webmarks [used for listing NN marks in the navigation]
 */
function buildSimpleMarks( $marks )
{
	$marksHTML = "\t\t<ul>\n";
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($marks); $i++)
	{
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
		$uri = $comments[$i]['name'];
		$message = str_replace("\n", "<br />\n", $comments[$i]['message']);
		if ($comments[$i]['uri'] != '')
		{
			$uri = "<a href=\"" . $comments[$i]['uri'] . "\">" . $comments[$i]["name"] . "</a>";
		}
		$commentsHTML .= "<div id=\"comment" . $comments[$i]["id"] . "\">\n<div>\n";
		if ($comments[$i]["state"] == 0)
		{
			$commentsHTML .= '<span class="comment_nr_disabled">';
		} else
		{
			$commentsHTML .= '<span class="comment_nr">';
		}
		$commentsHTML .= ($i + 1) . '.&nbsp;</span><span class="comment_info">Posted at ' . $comments[$i]['date'] . '&nbsp;by ' . $uri;
		$commentsHTML .= ' [ <a href="?rantid=' . $comments[$i]['rantId'] . '#comment' . $comments[$i]['id'] . '">Permalink</a> ';

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
				$commentsHTML .= "| <a href=\"root.php?action=enablecomment&amp;commentid=" . $comments[$i]['id'] . "&amp;rantid=" . $comments[$i]['rantId'] . "\">Show comment in list</a> ";
			} else
			{
				$commentsHTML .= "| <a href=\"root.php?action=disablecomment&amp;commentid=" . $comments[$i]['id'] . "&amp;rantid=" . $comments[$i]['rantId'] . "\">Hide comment from list</a> ";
			}
		}
		$commentsHTML .= "]</span></div>\n";

		$commentsHTML .= "<div class=\"comment_message\">" . $message . "</div>\n</div>\n";
	
	}

	return $commentsHTML;
}


function buildCommentsList( $skel, $comments )
{
	$commentsHTML = '';

	for ($i = 0; $i < count($comments); $i++)
	{
		$uri = $comments[$i]['name'];
		$message = str_replace("\n", "<br/>\n", $comments[$i]['message']);
		if ($comments[$i]['uri'] != '')
		{
			$uri = "<a href=\"" . $comments[$i]['uri'] . "\">" . $comments[$i]["name"] . "</a>";
		}
		$commentsHTML .= "<li>\n";
		if ($comments[$i]["state"] == 0)
		{
			$commentsHTML .= '<span class="comment_listitem_disabled">';
		} else
		{
			$commentsHTML .= '<span class="comment_listitem">';
		}
		$commentsHTML .= $comments[$i]['date'] . '&nbsp;by ' . $uri;
		$commentsHTML .= ' | <a href="' . $skel['baseHref'] . 'p/' . $comments[$i]['rantId'] . '/#comment' . $comments[$i]['id'] . '">link</a> ';

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
				$commentsHTML .= "| <a href=\"root.php?action=enablecomment&amp;commentid=" . $comments[$i]['id'] . "&amp;rantid=" . $comments[$i]['rantId'] . "\">Show</a> ";
			} else
			{
				$commentsHTML .= "| <a href=\"root.php?action=disablecomment&amp;commentid=" . $comments[$i]['id'] . "&amp;rantid=" . $comments[$i]['rantId'] . "\">Hide</a> ";
			}
		//$commentsHTML .= "</span></div>\n";
		$commentsHTML .= "</span>\n";

		$commentsHTML .= '<br />' . textSnippet($message, 160) . "</li>\n";
	
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

?>
