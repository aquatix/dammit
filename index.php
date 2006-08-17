<?php
/*
 * file: index.php
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

$lastmodified = '2006-05-28';
$page_version = '0.4.03';
$dateofcreation = '2003-12-21';

$page_name = 'home';
$page_log = 'home';
$section_name = 'home';
$subpage = '';

include 'inc/inc_init.php';

if (isset($_GET['page']))
{
	$subpage = $_GET['page'];
	$page_name = $subpage;
	$page_log = $page_name . '.' . $subpage;
}
$rantid = getRequestParam('rantid', -1);
if (-1 < $rantid)
{
	$page_log = "posting." . $rantid;
}

addToLog( $skel, $section_name, $page_log, $page_version );

$page_body = "";

/* Page-switcher */
if ( $subpage == "plan" )
{
	$lines = file($skel[".plan"]);
	for ($i = 0; $i < count($lines); $i++)
	{
		$page_body .= $lines[$i];
	}

} else if ( $subpage == "about" )
{
	$lines = file($skel["about"]);
	for ($i = 0; $i < count($lines); $i++)
	{
		$page_body .= $lines[$i];
	}

} else if ( $rantid > 0 )
{
	$commentsenabled = areCommentsEnabled($skel, $rantid);
	$commenting = false;
	$submitting = false;
	$comment_preview = "";
	$comment_error = false;
	$comment_name = "";
	$comment_error_name = "";
	$comment_email = "";
	$comment_error_email = "";
	$comment_notify = true;
	$comment_notify_text = " checked ";
	$comment_comment = "";
	$comment_error_comment = "";
	$comment_url = "http://";

	if ( $commentsenabled && isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["url"])  && isset($_POST["comment"]) && isset($_POST["submitbtn"]) )
	{
		$commenting = true;

		/* Get comment content and validate */
		$comment_name = getRequestParam("name", null);
		if ($comment_name == "")
		{
			$comment_error_name = "<p class=\"error\">Please fill in a name</p>\n";
			$comment_error = true;
		}
		$comment_email = getRequestParam("email", null);
		$comment_notify = isset($_POST["wantnotifications"]);
		if ($comment_notify == false)
		{
			$comment_notify_text = "";
		} else if ($comment_notify == true && $comment_email == "")
		{
			$comment_error_email = "<p class=\"error\">Provide an e-mail address if you want to be notified</p>\n";
			$comment_error = true;
		}
		$comment_comment = getRequestParam("comment", null);
		if ($comment_comment == "")
		{
			$comment_error_comment = "<p class=\"error\">Please enter some content in your comment</p>\n";
			$comment_error = true;
		}
		$comment_url = getRequestParam("url", null);
		$comment_rantid = getRequestParam("rantid", null);

		if ($_POST["submitbtn"] == "Save" && $comment_error == false)
		{
			$submitting = true;
			$wantnotifications = isset($_POST["wantnotifications"]);
			$result = addComment($skel, $_GET["rantid"], $comment_name, $comment_email, $wantnotifications, $comment_url, $comment_comment);
			if ($result > -1)
			{
				updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel["nrOfRantsPerPage"]));
				$page_body .= "<h1>Comment added!</h1>\n";
				$page_body .= "<p>Thank you for showing interest in my little rantbox :) <a href=\"index.php?rantid=" . $_POST["rantid"] . "&amp;view\">Go back to the posting</a></p>\n";
			} else
			{
				$page_body .= "<h1>Error</h1>\n";
				$page_body .= "<p>Something went wrong while saving comment. Please try again.</p>\n";
			}
		} else if ($_POST["submitbtn"] == "Preview" || $comment_error )
		{
			$url = "";
			if ($comment_url == "" || $comment_url == "http://")
			{
				$url = $comment_name;
			} else
			{
				$url = "<a href=\"" . $comment_url . "\">" . $comment_name . "</a>";
			}

			$comment_preview .= "<h1>Comment preview</h1><div id=\"commentpreview\"><div class=\"comment\">\n<div class=\"comment_info\"><span class=\"comment_datestamp\">Posted at yyyy-mm-dd hh:mm:ss</span>&nbsp;<span class=\"comment_name\">by " . $url . "</span></div>\n";
			$comment_preview .= "<div class=\"comment_message\">" . $message = str_replace("\n", "<br/>\n", htmlentities($comment_comment)) . "</div></div>\n";
		} else
		{
			$page_body .= "<h1>Error!</h1><p>An unknown action was received.</p>\n";
		}
	}

	if ($submitting == false)
	{
		$rant = getRantById($skel, $rantid);
		$page_body .= "<h1>" . $skel["sitename"] . " home</h1>\n";
		$prevNext = getNextPrevRant($skel, $rant[0]["date"]);

		$prev = "";
		if (isset($prevNext["prev"]["title"]) && "" != $prevNext["prev"]["title"])
		{
			$prev = "<a href=\"index.php?rantid=" . $prevNext["prev"]["messageID"] . "\">&laquo;&nbsp;" . $prevNext["prev"]["title"] . "</a>";
		} else
		{
			$prev = "<a href=\"index.php\">Home</a>";
		}
		$next = "";
		if (isset($prevNext["next"]["title"]) && "" != $prevNext["next"]["title"])
		{
			$next = "<a href=\"index.php?rantid=" . $prevNext["next"]["messageID"] . "\">" . $prevNext["next"]["title"] . "&nbsp;&raquo;</a>";
		} else
		{
			$next = "<a href=\"index.php\">Home</a>";
		}

		$page_body .= "<div class=\"browsenav\"><span class=\"previous\">" . $prev . "</span><span class=\"next\">&nbsp;" . $next . "</span></div>\n";

		$page_body .= buildRants($rant);

		/* Show all comments */
		$page_body .= "<h2 id=\"comments\">Comments</h2>\n";
		$page_body .= buildComments(getComments($skel, $rantid));

		/* Show input fields for additional comments */
		if ($commentsenabled)
		{
			$page_body .= "<div id=\"addcomment\">\n";
			if ($commenting === true)
			{
				$page_body .= $comment_preview;
			}
			$page_body .= "<h1>Add comment</h1>\n";
			$page_body .= "<p>You need to provide a valid email-address to comment here, but it will not be displayed on this website.</p>\n";
			$page_body .= "<p>HTML will be escaped, so you won't be able to add links. Post the URL instead. Line breaks will be converted to breaks.</p>\n";
			// post to current page!
			$page_body .= "<form action=\"index.php?rantid=" . $rantid . "#addcomment\" method=\"post\">\n";
			$page_body .= "<h2>Name</h2>" . $comment_error_name . "<p><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"150\" value=\"" . $comment_name . "\"/></p>\n";
			$page_body .= "<h2>E-mail</h2>" . $comment_error_email . "<p>Your address won't be shown in your comment</p><p><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"150\" value=\"" . $comment_email . "\"/><br/><input type=\"checkbox\" name=\"wantnotifications\"" . $comment_notify_text . "/> Mail me when someone else comments too</p>\n";
			$page_body .= "<h2>WWW</h2><p>Make empty if you don't want to provide a url.</p><p><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\" value=\"" . $comment_url . "\"/></p>\n";
			$page_body .= "<h2>Comment</h2><p>Be sure to <em>save your comment</em> after you've previewed it!</p>" . $comment_error_comment . "<p><textarea name=\"comment\" rows=\"8\" cols=\"80\" style=\"width: 100%\">" . $comment_comment . "</textarea></p>\n";
			$page_body .= "<p><input name=\"submitbtn\" value=\"Preview\" type=\"submit\"/>\n";
			if ($commenting === true)
			{
				$page_body .= "<input name=\"submitbtn\" value=\"Save\" type=\"submit\"/>\n";
			}
			$page_body .= "<input name=\"rantid\" value=\"" . $rantid . "\" type=\"hidden\"/></p>\n";
			$page_body .= "</form>\n";
			$page_body .= "</div>\n";
		} else
		{
			$page_body .= "<p><em>Comments for this posting are closed</em></p>\n";
		}
		if (isLoggedIn())
		{
			if ($commentsenabled)
			{
				$page_body .= "<p><a href=\"root.php?action=disablecommentsforpost&rantid=" . $rantid . "\">Disable comments for this posting</a></p>\n";
			} else
			{
				$page_body .= "<p><a href=\"root.php?action=enablecommentsforpost&rantid=" . $rantid . "\">[Re]enable comments for this posting</a></p>\n";
			}
			$page_body .= "<h1>Referers</h1>\n";
			$referers = getReferers( $skel, $section_name, $page_log );
			$page_body .= buildReferers( $skel, $referers );
		}

	}

} else if ( $subpage == "archive" )
{
	$year = date("Y"); /* Default to current year */
	if (isset($_GET["year"]) && myIsInt($_GET["year"]))
	{
		$year = $_GET["year"];
	}
	$page_body .= "<h1>Archive</h1>\n";
	$page_body .= "<h2>Archive of " . $year . "</h2>\n";
	$page_body .= "<p>All rants of the year " . $year . ", sorted on date [newest on top], grouped by month.</p>\n";
	$yearsnav = "<div class=\"mininav\">[ <span class=\"heading\">year</span> ";
	/* Get all years this blog is running */
	$years = getRantYears($skel);
	for ($i = 0; $i < count($years); $i++)
	{
		$yearsnav .= " | <a href=\"index.php?page=archive&amp;year=" . $years[$i] . "\">" . $years[$i] . "</a>";
	}
	$yearsnav .= " ]</div>\n";
	$page_body .= $yearsnav;
	$page_body .= buildRantlist(getRantsFromYear($skel, $year), false);
	$page_body .= $yearsnav;
} else if ( $subpage == "browse" )
{
	/*** Show the archive page ***/
	$page_body .= "<h1>Browse rants</h1>";
	$offset = 0;
	$nrBack = 0;
	$nrForward = $skel["nrOfRantsPerPage"];
	$nrOfRants = getNrOfRants($skel);

	$browse_nav = "<div class=\"mininav\">[ <span class=\"heading\">browse rants</span> | ";

	if (isset($_GET['offset']))
	{
		$offset = intval($_GET['offset']);
		if ( $offset > $nrOfRants )
		{
			/* Set $offset to last rant [aka first rant chronologically] */
			$offset = $nrOfRants;
		}
		$nrBack = $offset - $skel["nrOfRantsPerPage"];
		$nrForward = $offset + $skel["nrOfRantsPerPage"];
		if ($nrBack < 0)
		{
			$nrBack = 0;
		}
	} else
	{
		$nrForward = $skel["nrOfRantsPerPage"];
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
		$browse_nav = $browse_nav . "<a href=\"index.php?page=browse&amp;offset=" . $nrForward . "\">Next page</a> | <a href=\"index.php?page=browse&amp;offset=" . ((intval($nrOfRants / $skel["nrOfRantsPerPage"]) * $skel["nrOfRantsPerPage"])) . "\">Last page</a>";
	}
	$browse_nav = $browse_nav . " ]</div>\n";

	/* Show the nav */
	$page_body .= $browse_nav;
	/* Show the rants */
	$page_body .= buildRants(getRants($skel, $offset, $skel["nrOfRantsPerPage"]));
	/* Show the nav again */
	$page_body .= $browse_nav;
} else
{
	/*** Show the homepage ***/
	$page_body .= "<h1>" . $skel["sitename"] . " home</h1>\n";

	$page_body .= buildRants(getRants($skel, 0, $skel["nrOfRantsPerPage"]));
	$page_body .= "<p>[ <a href=\"index.php?page=browse&amp;offset=" . $skel["nrOfRantsPerPage"] . "\">Old rants</a> ]</p>\n";

} /* End of page-switcher */

/* Now build the page */
include "inc/inc_pagetemplate.php";
?>
