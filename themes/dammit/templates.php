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
function buildRants( $skel, $rants, $showcomments = false )
{
	$result = '';
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($rants); $i++)
	{
		$rantsHTML = '';
		$thisRant = $rants[$i];
		$thisDate = getNormalDate($thisRant['date']);
		
		if (CONTENT_MARKDOWN == $rants[$i]['contenttype'])
		{
			# Get Markdown class
			use \Michelf\Markdown;
			//$rantsHTML .= Markdown($rants[$i]['message']) . "\n";
			$rantsHTML .= Markdown::defaultTransform($rants[$i]['message']) . "\n";
		}
		else if (CONTENT_PLAINTEXT == $rants[$i]['contenttype'])
		{
			$rantsHTML .= plaintext2HTML($rants[$i]['message']) . "\n";
		} else
		{
			$rantsHTML .= $rants[$i]['message'] . "\n";
		}
		
		if (true === $showcomments)
		{
			$rantsComments = getComments($skel, $thisRant['messageID']);
		}
		
		ob_start();
		include 'article.php';
		$result .= ob_get_contents();
		ob_end_clean();
/*
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
		// Title
		if ($groupedOnDate)
		{
			$rantsHTML .= '<h3 class="ranttitle"><a href="' . $skel['base_uri'] . 'p/' . $rants[$i]['messageID'] . '">' . $rants[$i]['title'] . "</a></h3>\n";
		} else
		{
			$rantsHTML .= '<h2 class="ranttitle"><a href="' . $skel['base_uri'] . 'p/' . $rants[$i]['messageID'] . '">' . $rants[$i]['title'] . "</a></h2>\n";
		}
		if (count($rants) > 1 && substr($rants[$i]['title'], 0, 13) == 'Blogmarks for')
		{
			$blogmarksContent = true;
		}
		// Info about the rant entry
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
		// Rant itself
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

		$rantsHTML .= '<a href="http://www.technorati.com/search/' . 'http://dammit.nl/' . $skel['base_uri'] . 'p/' . $rants[$i]['messageID'] . '" title="Search for related articles"><img src="' . $skel['base_uri'] . 'images/technorati_related.gif" alt="Search for related articles" /></a> | ';

		if ($rants[$i]['modified'] > 0)
		{
			// Modified at least once
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
		$rantsHTML .= '<a href="' . $skel['base_uri'] . 'p/' . $rants[$i]['messageID'] . '#comments">' . $rants[$i]['nrOfComments'] . ' ' . $commentText . '&nbsp;&raquo;</a>';
		if (0 == $rants[$i]['commentsenabled'])
		{
			$rantsHTML .= '</span>';
		}
		
		$rantsHTML .= "</div>\n";
		$rantsHTML .= "</div>\n\n";
	}
	// Close the grouped div
	if ($groupedOnDate)
	{
		$rantsHTML .= "</div>\n";
	}
	*/
	}
	return $result;
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
				$html .= '<h2><a href="' . $skel['base_uri'] . 'p/archive/month/' . getYear($rants[$i]['date']) . $thisMonth . '">' . getMonthName($thisMonth) . "</a></h2>\n";
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
		$html .= "<a href=\"" . $skel['base_uri'] . "p/" . $rants[$i]['messageID'] . "\">" . $rants[$i]['title'] . "</a>";

		$html .= "</li>\n";
	}
	$html .= "</ul>\n";
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

/**
 * Simple list of blogmarks to include in (weekly) post
 */
function buildInPostMarks( $marks )
{
	$marksHTML = '<ul class="blogmarks">';

	for ($i = 0; $i < count($marks); $i++)
	{
		/* Title */
		//$marksHTML .= "<h3><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a></h3>\n";
		$marksHTML .= "<li><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a> ";
		/* Rant itself */
		//$marksHTML .= $marks[$i]['message'] . "\n";
		$marksHTML .= strip_tags($marks[$i]['message']) . "</li>\n";
	}
	$marksHTML .= "</ul>\n";
	return $marksHTML;
}


/**
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


/**
 * Build a simple list of webmarks [used for listing NN marks in the navigation]
 */
function buildMarksList( $marks )
{
	$marksHTML = "\t\t<ul class=\"content\">\n";
	$previousDate = '0000-00-00 00:00:00';

	for ($i = 0; $i < count($marks); $i++)
	{
		$marksHTML .= "\t\t\t<li><a href=\"" . $marks[$i]['uri'] . "\">" . $marks[$i]['title'] . "</a> <div class=\"blogmarkmeta\">" . $marks[$i]['message'] . "</div></li>\n";
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
		$commentsHTML .= ' | <a href="' . $skel['base_uri'] . 'p/' . $comments[$i]['rantId'] . '/#comment' . $comments[$i]['id'] . '">link</a> ';

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
