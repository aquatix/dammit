<?php
/**
 * Index page
 * Shows the main page of the weblog, older postings, discrete postings and comments
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

$skel['lastmodified'] = '2014-03-04';
$skel['page_version'] = '0.8.02';
$skel['dateofcreation'] = '2003-12-21';

$page_name = 'home';
$page_log = 'home';
$section_name = 'home';

include 'modules/init.php';

$subpage = getRequestParam('page', null);
if (null != $subpage)
{
	$page_name = $subpage;
	$page_log = $page_name . '.' . $subpage;
}
$rantid = getRequestParam('rantid', -1);
if (-1 < $rantid)
{
	$page_log = 'posting.' . $rantid;
}
$month = getRequestParam('month', null);

$url_pieces = parse_url(getenv('SCRIPT_URI'));
$pagequery = $_SERVER['REQUEST_URI'];

if (isset($pagequery) && '' != $pagequery && ('' != strstr($pagequery, 'index.php') || '?' == $pagequery[1]))
{
	// Redirect. Check for url injections
	header('HTTP/1.1 301 Moved Permanently');
	$redir = $skel['base_server'] . $skel['base_uri'] . 'p/';
	if (-1 < $rantid)
	{
		$redir .= $rantid . '/';
	} else if (NULL != $subpage)
	{
		$redir .= $subpage;
	}

	header('Location: ' . $redir);
	exit;
}

addToLog( $skel, $section_name, $page_log, $skel['page_version'] );

$page_body = '';

/* Page-switcher */
//if (isset($subpage) && in_array('page_' . $subpage, $skel) && file_exists($skel['page_' . $subpage]))
if (isset($subpage) && isset($skel['page_' . $subpage]) && file_exists($skel['page_' . $subpage]))
{
	$skel['page_title'] = 'Page';
	if (isset($skel['page_' . $subpage . '_title']))
	{
		$skel['page_title'] = $skel['page_' . $subpage . '_title'];
	}
	$skel['page_permalink'] = $skel['base_server'] . $skel['base_uri'] . 'p/' . $subpage;
	$lines = file($skel['page_' . $subpage]);
	for ($i = 0; $i < count($lines); $i++)
	{
		$page_body .= $lines[$i];
	}
} else if ( $rantid > 0 )
{
	$commentsenabled = $skel['commentsenabled'] && areCommentsEnabled($skel, $rantid);
	$commenting = false;
	$submitting = false;
	$comment_preview = '';
	$comment_error = false;
	$comment_name = '';
	$comment_error_name = '';
	$comment_email = '';
	$comment_error_email = '';
	$comment_notify = true;
	$comment_notify_text = " checked=\"checked\" ";
	$comment_comment = '';
	$comment_error_comment = '';
	$comment_url = '';
	$comment_error_spammerchallenge = '';
	$comment_spammer = '';

	if ( $commentsenabled && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['url'])  && isset($_POST['comment']) && isset($_POST['spammer']) && isset($_POST['submitbtn']) )
	{
		$commenting = true;

		/* Get comment content and validate */
		$comment_name = getRequestParam("name", null);
		if ($comment_name == '')
		{
			$comment_error_name = "<p class=\"error\">Please fill in a name</p>\n";
			$comment_error = true;
		}
		$comment_email = getRequestParam("email", null);
		$comment_notify = isset($_POST['wantnotifications']);
		if ($comment_notify == false)
		{
			$comment_notify_text = '';
		} else if ($comment_notify == true && $comment_email == '')
		{
			$comment_error_email = "<p class=\"error\">Provide an e-mail address if you want to be notified</p>\n";
			$comment_error = true;
		}
		$comment_comment = getRequestParam("comment", null);
		if ($comment_comment == '')
		{
			$comment_error_comment = "<p class=\"error\">Please enter some content in your comment</p>\n";
			$comment_error = true;
		}
		$comment_spammer = getRequestParam("spammer", null);
		if (strtolower($comment_spammer) != 'no')
		{
			$comment_error_spammerchallenge = '<em>Get lost, spammer</em>';
			$comment_error = true;
		}
		$comment_url = getRequestParam("url", null);
		$comment_rantid = getRequestParam("rantid", null);

		if ($_POST['submitbtn'] == "Save" && $comment_error == false)
		{
			$submitting = true;
			$wantnotifications = isset($_POST['wantnotifications']);
			$result = addComment($skel, $_GET['rantid'], $comment_name, $comment_email, $wantnotifications, $comment_url, $comment_comment);
			if ($result > -1)
			{
				updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfRantsPerPage']));
				$page_body .= "<h1>Comment added!</h1>\n";
				$page_body .= "<p>Thank you for showing interest in my little rantbox :)</p><p><a href=\"" . $skel['base_uri'] . "p/" . $_POST['rantid'] . "&amp;view\" class=\"button\">&laquo; Go back to the posting</a></p>\n";
			} else
			{
				$page_body .= "<h1>Error</h1>\n";
				$page_body .= "<p>Something went wrong while saving comment. Please try again.</p>\n";
			}
		} else if ($_POST['submitbtn'] == "Preview" || $comment_error )
		{
			$url = '';
			if ($comment_url == '' || $comment_url == "http://")
			{
				$url = $comment_name;
			} else
			{
				$url = '<a href="' . $comment_url . '">' . $comment_name . '</a>';
			}

			//$comment_preview .= "<h1>Comment preview</h1>\n<div id=\"commentpreview\">\n\t<div class=\"comment\">\n\t\t<div class=\"comment_info\"><span class=\"comment_datestamp\">Posted at yyyy-mm-dd hh:mm:ss</span>&nbsp;<span class=\"comment_name\">by " . $url . "</span></div>\n";
			//$comment_preview .= "\t\t<div class=\"comment_message\">" . $message = str_replace("\n", "<br/>\n", htmlentities($comment_comment)) . "</div>\n\t</div>\n</div>\n";
			$comment_preview .= "<h1>Comment preview</h1>\n<div id=\"commentpreview\">\n\t<div>\n<span class=\"comment_nr\">N.&nbsp;</span><span class=\"comment_info\">Posted at yyyy-mm-dd hh:mm:ss&nbsp;by " . $url . " [ Permalink ]</span></div>\n";
			$comment_preview .= "\t\t<div class=\"comment_message\">" . $message = str_replace("\n", "<br/>\n", htmlentities($comment_comment)) . "\n\t</div>\n</div>\n";
		} else
		{
			$page_body .= "<h1>Error!</h1><p>An unknown action was received.</p>\n";
		}
	}

	if (false === $submitting)
	{
		$rant = getRantById($skel, $rantid);
		//$skel['page_title'] = 'Posting';
		$skel['page_permalink'] = $skel['base_server'] . $skel['base_uri'] . 'p/' . $rantid;
		if (null != $skel['globalmessage'])
		{
			$page_body .= '<p class="globalmessage">' . $skel['globalmessage'] . "</p>\n";
		}
		if ((null == $rant) || (0 == $rant[0]['ispublic']))
		{
			/* Rant was not found; in the second case, it wasn't published yet, so doesn't exist yet for the public */
			$page_name = 'Rant not found';
			//$page_body .= "<h2>Sorry</h2><p>The requested rant was not found. <a href=\"index.php\">Go to the homepage</a> or start searching in <a href=\"index.php?page=archive\">the archive</a>.</p>\n";
			$page_body .= "<h2>Sorry</h2><p>The requested rant was not found. <a href=\"index.php\">Go to the homepage</a> or start searching in <a href=\"" . $skel['base_uri'] . "p/archive\">the archive</a>.</p>\n";
		} else
		{
			$page_name = strip_tags($rant[0]['title']);
			$prevNext = getNextPrevRant($skel, $rant[0]['date']);
			$skel['pageDescription'] = textSnippet($rant[0]['message'], 200);

			$prev = '';
			if (isset($prevNext['prev']['title']) && '' != $prevNext['prev']['title'])
			{
				$prev = "<a href=\"" . $skel['base_uri'] . "p/" . $prevNext['prev']['messageID'] . "\">&laquo;&nbsp;" . $prevNext['prev']['title'] . "</a>";
			} else
			{
				$prev = "<a href=\"" . $skel['base_uri'] . "\">Home</a>";
			}
			$next = '';
			if (isset($prevNext['next']['title']) && '' != $prevNext['next']['title'])
			{
				$next = "<a href=\"" . $skel['base_uri'] . "p/" . $prevNext['next']['messageID'] . "\">" . $prevNext['next']['title'] . "&nbsp;&raquo;</a>";
			} else
			{
				$next = "<a href=\"" . $skel['base_uri'] . "\">Home</a>";
			}

			$page_body .= "<div class=\"browsenav\"><span class=\"previous\">" . $prev . "</span><span class=\"next\">&nbsp;" . $next . "</span></div>\n";

			$page_body .= buildRants($skel, $rant, true);

			/* Show all comments */
			/*
			$allComments = getComments($skel, $rantid);
			if (0 < count($allComments))
			{
				$page_body .= "<h2 id=\"comments\">Comments</h2>\n";
				//$page_body .= buildComments(getComments($skel, $rantid));
				$page_body .= buildComments($allComments);
			}*/

			/* Show input fields for additional comments */
			if ($commentsenabled)
			{
				$page_body .= "<div id=\"addcomment\">\n";
				if ($commenting === true)
				{
					$page_body .= $comment_preview;
				}
				$page_body .= "<h2>Add comment</h2>\n";
				//$page_body .= "<div class=\"grouped\">\n";
				$page_body .= "<p>You need to provide a valid e-mail address to comment here, but it will not be displayed on this website. ";
				$page_body .= "HTML will be escaped, so you won't be able to add links. Post the URL instead. Line breaks will be converted to breaks.</p>\n";
				/* post to current page */
				$page_body .= "<form action=\"" . $skel['base_uri'] . "p/" . $rantid . "#addcomment\" method=\"post\">\n";
				$page_body .= "<h3>Name</h3>\n";
				$page_body .= $comment_error_name . "<p><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"150\" value=\"" . $comment_name . "\" /></p>\n";
				$page_body .= "<h3>E-mail address</h3>\n";
				$page_body .= $comment_error_email . "<p><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"150\" value=\"" . $comment_email . "\" /><br/><input type=\"checkbox\" name=\"wantnotifications\"" . $comment_notify_text . "/> Mail me when someone else comments too<br />\nYour address <em>won't</em> be shown in your comment</p>\n";
				$page_body .= "<h3>WWW</h3>\n";
				$page_body .= "<p><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\" value=\"" . $comment_url . "\" /><br />\nLeave empty if you don't want to provide a url</p>\n";
				//$page_body .= "<h2>Comment</h2><p>Be sure to <em>save your comment</em> after you've previewed it!</p>" . $comment_error_comment . "<p><textarea name=\"comment\" rows=\"8\" cols=\"80\" style=\"width: 100%\">" . $comment_comment . "</textarea></p>\n";
				$page_body .= $comment_error_comment . "<p><textarea name=\"comment\" rows=\"8\" cols=\"80\">" . $comment_comment . "</textarea></p>\n";
				$page_body .= "<h3>Are you a spammer (yes/no)?</h3>\n";
				$page_body .= $comment_error_spammerchallenge . "<p><input type=\"text\" name=\"spammer\" size=\"30\" maxlength=\"150\" value=\"" . $comment_spammer . "\" /></p>\n";
				$page_body .= '<p>Be sure to <em>save your comment</em> after you\'ve previewed it!</p>';
				$page_body .= "<p><input name=\"submitbtn\" value=\"Preview\" type=\"submit\"/>\n";
				if ($commenting === true)
				{
					$page_body .= "<input name=\"submitbtn\" value=\"Save\" type=\"submit\"/>\n";
				}
				$page_body .= "<input name=\"rantid\" value=\"" . $rantid . "\" type=\"hidden\"/></p>\n";
				$page_body .= "</form>\n";
				//$page_body .= "</div>\n";
				$page_body .= "</div>\n";
			} else
			{
				$page_body .= "<p><em>Sorry, comments for this posting are closed; likely because of spam</em></p>\n";
			}
			if (isLoggedIn())
			{
				$page_body .= "<h2>Admin</h2>\n";
				if ($commentsenabled)
				{
					$page_body .= "<p><a href=\"root.php?action=disablecommentsforpost&amp;rantid=" . $rantid . "\" class=\"button\">Disable comments for this posting</a></p>\n";
				} else
				{
					$page_body .= "<p><a href=\"root.php?action=enablecommentsforpost&amp;rantid=" . $rantid . "\" class=\"button\">[Re]enable comments for this posting</a></p>\n";
				}
				$referers = getReferers( $skel, $section_name, $page_log );
				$page_body .= "<h3>Referers</h3>\n";
				if (0 < count($referers))
				{
					$page_body .= buildReferers( $skel, $referers );
				} else
				{
					$page_body .= "<p>No referers known</p>\n";
				}
			}
		}
	}

} else if ( $subpage == 'kudos' )
{
	$skel['page_title'] = 'Kudos';
	$skel['page_permalink'] = $skel['base_server'] . $skel['base_uri'] . 'p/' . $subpage;
	$page_name = 'kudos';
	//$page_body .= "<h1>Kudos</h1>\n";
	$page_body .= "<p>A lovely link page that takes you back to the 1990's, but enables me to give some <a href=\"http://en.wikipedia.org/wiki/Kudos\">Kudos</a> to weblogs I read, people I like, news sites I crave and hobbies I love.</p>\n";
	$page_body .= buildKudos($skel);
} else if ( $subpage == 'archive' )
{
	$year = getRequestParam('year', date('Y')); /* Default to current year */
	$skel['page_title'] = 'Archive - ' . $year;
	$skel['page_permalink'] = $skel['base_server'] . $skel['base_uri'] . 'p/archive/' . $year;
	$page_name = 'archive - ' . $year;
	//$page_body .= '<h1>Archive - ' . $year . "</h1>\n";
	if (null != $skel['globalmessage'])
	{
		$page_body .= '<p class="globalmessage">' . $skel['globalmessage'] . "</p>\n";
	}
	$yearsnav = "<div class=\"mininav\">[ <span class=\"active\">year</span> ";
	/* Get all years this blog is running */
	$years = getRantYears($skel);
	for ($i = 0; $i < count($years); $i++)
	{
		if ($years[$i] == $year)
		{
			//$yearsnav .= ' | <span class="active"><a href="index.php?page=archive&amp;year=' . $years[$i] . '">' . $years[$i] . '</a></span>';
			$yearsnav .= ' | <span class="active"><a href="' . $skel['base_uri'] . 'p/archive/' . $years[$i] . '">' . $years[$i] . '</a></span>';
		} else
		{
			//$yearsnav .= ' | <a href="index.php?page=archive&amp;year=' . $years[$i] . '">' . $years[$i] . '</a>';
			$yearsnav .= ' | <a href="' . $skel['base_uri'] . 'p/archive/' . $years[$i] . '">' . $years[$i] . '</a>';
		}
	}
	$yearsnav .= " ]</div>\n";
	$page_body .= $yearsnav;
	if (!in_array($year, $years))
	{
		$page_body .= '<h2>Sorry</h2><p>No rants found for this year.</p>';
	} else
	{
		$page_body .= buildRantlist($skel, getRantsFromYear($skel, $year), false);
	}
	$page_body .= $yearsnav;
} else if ( null != $month )
{
	$month = intval($month);
	if (6 != strlen($month))
	{
		/* Not a valid month, as those are of the form yyyymm */
		$page_body .= "<h1>Browse by month</h1>\n";
		//$page_body .= "<p>Not a valid month chosen, <a href=\"index.php?page=archive\">see the archive for all entries</a>.</p>\n";
		$page_body .= "<p>Not a valid month chosen, <a href=\"" . $skel['base_uri'] . "p/archive\">see the archive for all entries</a>.</p>\n";
	} else
	{
		$year = substr($month, 0, 4);
		$month = substr($month, 4, 2);

		$page_name = getMonthName($month) . ' ' . $year;
		$page_body .= '<h1>' . getMonthName($month) . ' ' . $year . "</h1>\n";
		if (null != $skel['globalmessage'])
		{
			$page_body .= '<p class="globalmessage">' . $skel['globalmessage'] . "</p>\n";
		}

		$page_body .= buildRants($skel, getRantsForMonth($skel, $year, $month));
	}
} else if ( $subpage == 'browse' )
{
	/*** Show the archive page ***/
	$page_body .= "<h1>Browse rants</h1>";
	$offset = 0;
	$nrBack = 0;
	$nrForward = $skel['nrOfRantsPerPage'];
	$nrOfRants = getNrOfRants($skel);

	$browse_nav = "<div class=\"mininav\">[ <span class=\"active\">browse rants</span> | ";

	if (isset($_GET['offset']))
	{
		$offset = intval($_GET['offset']);
		if ( $offset > $nrOfRants )
		{
			/* Set $offset to last rant [aka first rant chronologically] */
			$offset = $nrOfRants;
		}
		$nrBack = $offset - $skel['nrOfRantsPerPage'];
		$nrForward = $offset + $skel['nrOfRantsPerPage'];
		if ($nrBack < 0)
		{
			$nrBack = 0;
		}
	} else
	{
		$nrForward = $skel['nrOfRantsPerPage'];
	}
	if ( $nrForward > $nrOfRants )
	{
		$nrForward = $offset;
	}

	/* "Previous" part of the navigation */
	if ( $offset == 0 )
	{
		$browse_nav = $browse_nav . "First page | Previous page";
	} else
	{
		$browse_nav = $browse_nav . "<a href=\"index.php?page=browse\">First page</a> | <a href=\"index.php?page=browse&amp;offset=" . $nrBack . "\">Previous page</a>";
	}
	$browse_nav = $browse_nav . " | ";
	/* "Next" part */
	if ( $nrForward == $offset )
	{
		$browse_nav = $browse_nav . "Next page | Last page";
	} else
	{
		$browse_nav = $browse_nav . "<a href=\"index.php?page=browse&amp;offset=" . $nrForward . "\">Next page</a> | <a href=\"index.php?page=browse&amp;offset=" . ((intval($nrOfRants / $skel['nrOfRantsPerPage']) * $skel['nrOfRantsPerPage'])) . "\">Last page</a>";
	}
	$browse_nav = $browse_nav . " ]</div>\n";

	/* Show the nav */
	$page_body .= $browse_nav;
	/* Show the rants */
	$page_body .= buildRants($skel, getRants($skel, $offset, $skel['nrOfRantsPerPage']));
	/* Show the nav again */
	$page_body .= $browse_nav;
} else
{
	/*** Show the homepage ***/
	//$page_body .= "<h1>" . $skel['siteName'] . " home</h1>\n";
	//$page_body .= "<h1>Home</h1>\n";
	if (null != $skel['globalmessage'])
	{
		$page_body .= '<p class="globalmessage">' . $skel['globalmessage'] . "</p>\n";
	}

	$page_body .= buildRants($skel, getRants($skel, 0, $skel['nrOfRantsPerPage']));
	//$page_body .= "<p>[ <a href=\"index.php?page=browse&amp;offset=" . $skel['nrOfRantsPerPage'] . "\">Old rants</a> ]</p>\n";
	//$page_body .= "<p><a href=\"index.php?page=archive\" class=\"button\">&laquo; Old rants</a></p>\n";
	$page_body .= "<p><a href=\"" . $skel['base_uri'] . "p/archive\" class=\"button\">&laquo; Old rants</a></p>\n";

} /* End of page-switcher */

/* Now build the page */
echo buildPage($skel, $section_name, $page_name, $page_body);
