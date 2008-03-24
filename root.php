<?php
/*
 * $Id$
 *
 * Copyright 2003-2008 mbscholt at aquariusoft.org
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

/* Enable error reporting */
//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

$lastmodified = '2008-03-24';
$page_version = '0.5.10';
$dateofcreation = '2003-12-22';

$section_name = 'root';
$page_name = 'home';

include 'inc/inc_init.php';

/* Record a hit on this page to the log */
addToLog( $skel, $section_name, $page_name, $page_version );

$page_body = '';
$page_name = 'root';

if ('weeklymarks' == getRequestParam('action', '') && getenv('REMOTE_ADDR') == $skel['restricttoip'])
{
	$result = marksToRant($skel);
	if ('' == $result)
	{
		//updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
		updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
		updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
		echo 'Rant with blogmarks of this week added';
	} else
	{
		echo 'Rant with blogmarks of this week NOT added: ' . $result;
	}
	exit;
}

/* Find out which page to show */
if ((isLoggedIn() == false) && (isset($_POST['user']) && $_POST['user'] != '') && (isset($_POST['pass']) && $_POST['pass'] != ''))
{
	/* Try to log in */
	$user = getRequestParam('user', null);
	$pass = getRequestParam('pass', null);
	$userid = login($skel, $user, $pass);
	if ( $userid > 0)
	{
		/* Login successfull! */
		/* Start new session */
		session_name($skel['session_name']);
		session_start();

		$_SESSION['username'] = $user;
		$_SESSION['userid'] = $userid;
	} else
	{
		$page_body .= "<h1>Error!</h1>\n<p>Not a valid user/pass combo!</p>\n<p><a href=\"root.php\">Go back</a></p>\n<br /><br /><br /><br />\n";
		include "inc/inc_pagetemplate.php";
		exit;
	}
}

$root_nav = "<div class=\"mininav\">[ <span class=\"heading\">root</span> | <a href=\"root.php\">home</a> | <a href=\"root.php?action=addrant\">add rant</a> | <a href=\"root.php?action=addmark\">add blogmark</a> | <a href=\"root.php?action=logout\">logout</a>  ]</div>\n";
if (isset($_GET['action']) && isLoggedIn())
{
	$action = getRequestParam('action', null);
	if ('addrant' == $action)
	{
		/* Page for adding a new rant :) */
		$rant = newRant($skel);
		$page_body .= $root_nav;
		$page_body .= "<h1>root / add rant</h1>\n";
		//$page_body .= "<form action=\"root.php?action=addingrant\" method=\"post\">\n";
		$page_body .= "<form action=\"root.php?action=saverant\" method=\"post\">\n";
		$page_body .= buildEditRant($rant);
		$page_body .= "</form>\n";
	} else if ('saverant' == $action)
	{

		$saveKind = getRequestParam('savekind', null);
		echo "saveKind = " . $saveKind . "\n";
		$rant = newRant($skel);
		$rant['title'] = getRequestParam('title', null);
		$rant['location'] = getRequestParam('location', null);
		$rant['message'] = getRequestParam('rant', null);
		$rant['contenttype'] = getRequestParam('contenttype', CONTENT_RAWHTML);
		$rant['rantid'] = getRequestParam('rantid', -1);
		$rant['ispublic'] = getRequestParam('ispublic', ISPUBLIC_NO);
		$rant['initiated'] = getRequestParam('initiated', null);
		if (null != $rant['title'] && null != $rant['location'] && null != $rant['message'] && -1 < $rant['contenttype'])
		{
			if ('add' == $saveKind)
			{
				/* Trying to add rant to DB */
				addRant($skel, $rant);

			} else if ('edit' == $saveKind)
			{
				/* Trying to add rant to DB */
			} else if ('save' == $saveKind)
			{
				/* Only save, don't publish yet */
			}
			/* Update RSS feed[s] */
			updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= $root_nav;
			$page_body .= "<h1>root / rant added!</h1>\n";
			$page_body .= "<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid rant submitted :)</p>\n<br /><br /><br /><br />\n";
		}
	} else if ('editrant' == $action)
	{
		/* Look up the posting */
		$rantId = getRequestParam('rantid', -1);

		$isRantMine = isRantMine( $skel, $rantId );
		if ($isRantMine)
		{
			$showform = true;
			if (isset($_POST['submitting']) && $_POST['submitting'] == "true")
			{
				/* User submitted edited rant, check it now */
				if (isset($_POST['title']) && isset($_POST['location']) && isset($_POST['rant']) && isset($_POST['id']))
				{
					/* check the values, if not right, show the form again */
					//checkRant($skel, $rant);
					/* Update the rant */
					$result = editRant( $skel,  getRequestParam('title', null), getRequestParam('location', null), getRequestParam('rant', null), getRequestParam('id', -1) );
					updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
					updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
					$showform = false;
					$page_body .= $root_nav;
					$page_body .= "<h1>root / rant edited</h1>\n";
					$page_body .= "<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>\n";
					//check $result
				} else
				{
					$page_body .= $root_nav;
					$page_body .= "<h1>root / error!</h1>\n";
					$page_body .= "<p>Something went wrong while updating the posting</p>\n";
					$showform = false;
				}
			}

			if ($showform)
			{
				$postings = getRantById($skel, $rantId);
				/* Now get the actual posting. We should only have one returned, so just take the first */
				$posting = $postings[0];
				if (isset($_POST['title']) && isset($_POST['location']) && isset($_POST['rant']) && isset($_POST['id']))
				{
					$posting['title'] = $_POST['title'];
					$posting['location'] = $_POST['location'];
					$posting['message'] = $_POST['rant'];
				} else
				{
					$posting['message'] .= "\n\n<div class=\"edit\">edited at " . date("Y-m-d H:i") . "</div>\n";
				}
				$page_body .= $root_nav;
				$page_body .= "<h1>root / edit rant</h1>\n";
				$page_body .= "<form action=\"root.php?action=editrant&amp;rantid=" . $posting['messageID'] . "\" method=\"post\">\n";
				$page_body .= buildEditRant($posting);
				$page_body .= "<input type=\"hidden\" name=\"submitting\" value=\"true\" />\n";
				$page_body .= "</form>\n";
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Posting does not belong to you, so you can't edit it</p>\n";
		}
	} else if ('addmark' == $action)
	{
		$mark_title = getRequestParam('title', '');
		$mark_uri = getRequestParam('uri', '');
		/* Page for adding a new blogmark :) */
		$page_body .= $root_nav;
		$page_body .= "<h1>root / add blogmark</h1>\n";
		$page_body .= "<form action=\"root.php?action=addingmark\" method=\"post\">\n";
		$ip = getenv('REMOTE_ADDR');
		$location = getLocation($skel, $ip);
		$page_body .= "<h2>Location</h2><p><input type=\"text\" name=\"location\" value=\"" . $location . "\" size=\"30\" maxlength=\"50\" /></p>\n";
		$page_body .= '<h2>Title</h2><p><input type="text" name="title" value="' . $mark_title . "\" size=\"40\" maxlength=\"250\"/></p>\n";
		$page_body .= '<h2>URI</h2><p><input type="text" name="uri" value="' . $mark_uri . "\" size=\"40\" maxlength=\"250\"/></p>\n";
		$page_body .= "<h2>Description</h2><p><textarea name=\"description\" rows=\"30\" cols=\"80\"></textarea></p>\n";
		$page_body .= "<p><input name=\"submitbtn\" value=\"Save\" type=\"submit\"/></p>\n";
		$page_body .= "</form>\n";
	} else if ($action == "editmark")
	{
		/* Edit an existing mark */
		$page_body .= markList();
	} else if ($action == "addingmark")
	{
		/* Trying to add blogmark to DB */
		if (isset($_POST['title']) && isset($_POST['uri']) && isset($_POST['location']) && isset($_POST['description']))
		{
			//addMark($skel, $_POST['title'], $_POST['uri'], $_POST['location'], $_POST['description']);
			addMark($skel, getRequestParam('title', null), getRequestParam('uri', null), getRequestParam('location', null), getRequestParam('description', null));
			/* Update RSS feed[s] */
			updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= $root_nav;
			$page_body .= "<h1>root / blogmark added!</h1>\n";
			$page_body .= "<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
		} else
		{
			$page_body .= "<h1>Error!</h1>\n<p>Not a valid blogmark submitted :)</p>\n<br /><br /><br /><br />\n";
		}
	} else if ($action == "markstorant")
	{
		$result = marksToRant($skel);
		if ('' == $result)
		{
			updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= "<h1>root / rant with blogmarks of this week added</h1>\n<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>\n";
		} else
		{
			$page_body .= '<h1>root / rant with blogmarks of this week NOT added</h1>\n<p>' . $result . "</p><p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>\n";
		}
	} else if ($action == "disablecomment")
	{
		if (isset($_GET['commentid']) && myIsInt($_GET['commentid']))
		{
			$commentid = $_GET['commentid'];
			if (false == $commentid)
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / error!</h1>\n";
				$page_body .= "<p>Not a valid comment selected</p>\n";
			} else
			{
				$result = disableComment($skel, $commentid);
				if ($result != "")
				{
					$page_body .= $root_nav;
					$page_body .= "<h1>root / error!</h1>\n";
					$page_body .= "<p>" . $result . "</p><p>Please contact the webmaster</p>\n";
				} else
				{
					updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
					$page_body .= $root_nav;
					$page_body .= "<h1>root / remove [disable] comment</h1>\n";
					$page_body .= "<p>Comment #" . $commentid . " removed and comment feed updated</p>\n";
					$page_body .= '<p><a href="index.php?rantid=' . $commentid . "\">Go back to the posting</a> / <a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
				}
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid comment selected</p>\n";
		}
	} else if ($action == "enablecomment")
	{
		if (isset($_GET['commentid']) && myIsInt($_GET['commentid']))
		{
			$commentid = $_GET['commentid'];
			if (false == $commentid)
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / error!</h1>\n";
				$page_body .= "<p>Not a valid comment selected</p>\n";
			} else
			{
				$result = enableComment($skel, $commentid);
				if ($result != "")
				{
					$page_body .= $root_nav;
					$page_body .= "<h1>root / error!</h1>\n";
					$page_body .= "<p>" . $result . "</p><p>Please contact the webmaster</p>\n";
				} else
				{
					updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
					$page_body .= $root_nav;
					$page_body .= "<h1>root / recover [enable] comment</h1>\n";
					$page_body .= "<p>Comment #" . $commentid . " recovered and comment feed updated</p>\n";
					$page_body .= "<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
					//$page_body .= "<p><a href=\"index.php?rantid=" . $rantid . "\">Go back to the posting</a> / <a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
				}
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid comment selected</p>\n";
		}
	} else if ( $action == "disablecommentsforpost" )
	{
		$rantid = getRequestParam("rantid", -1);
		if ($rantid > 0)
		{
			$result = disableCommentsForPost($skel, $rantid);
			if ($result != "")
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / error!</h1>\n";
				$page_body .= "<p>" . $result . "</p><p>Please contact the webmaster</p>\n";
			} else
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / disable comments for posting</h1>\n";
				$page_body .= "<p>Disabled commenting for posting #" . $rantid . "</p>\n";
				$page_body .= "<p><a href=\"index.php?rantid=" . $rantid . "\">Go back to the posting</a> / <a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid posting selected</p>\n";
		}
	} else if ( $action == "enablecommentsforpost" )
	{
		$rantid = getRequestParam("rantid", -1);
		if ($rantid > 0)
		{
			$result = enableCommentsForPost($skel, $rantid);
			if ($result != "")
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / error!</h1>\n";
				$page_body .= "<p>" . $result . "</p><p>Please contact the webmaster</p>\n";
			} else
			{
				$page_body .= $root_nav;
				$page_body .= "<h1>root / [re]enable comments for posting</h1>\n";
				$page_body .= "<p>Enabled commenting for posting #" . $rantid . "</p>\n";
				$page_body .= "<p><a href=\"index.php?rantid=" . $rantid . "\">Go back to the posting</a> / <a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid posting selected</p>\n";
		}
	} else if ( $action == "generatefeeds" )
	{
		/* Generate blog.rss [and blog.atom ?] */
		$page_body .= $root_nav;
		$page_body .= "<h1>root / generating RSS feed[s]</h1>\n";
		updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
		updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
		updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
		$page_body .= "<p>Feeds refreshed.</p>\n<p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
	} else if ( $action == "viewlog" )
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / view log</h1>\n";
		$page_body .= "<p>Not implemented yet!</p><p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>\n";
	} else if ( $action == "viewreferers" )
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / view referers</h1>\n";
		$referers = getAllReferers( $skel );
		$page_body .= buildAllReferers( $skel, $referers );
	} else if ( $action == "viewcommentlog" )
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / view comments log</h1>\n";
		$page_body .= "<p>Not implemented yet!</p><p><a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>\n";
	} else if ( $action == "logout" )
	{
		/* user wants to log out */
		unset($_SESSION['username']);
		/* Destroy session vars */
		$_SESSION = array();
		session_destroy();
		$user_name = null;
		$user_pass = null;
		$page_body .= "<h1>Logged out!</h1>\n<p><a href=\"index.php\">Go back to rantbox</a></p>\n<br/><br/><br/><br/>";
	} else
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / error!</h1>\n";
		$page_body .= "<p>Not a valid action!</p>\n<br /><br /><br /><br />\n";
	}
} else if (isLoggedIn())
{
	$page_body .= $root_nav;
	$page_body .= "<h1>Got root!</h1>\n";
	$page_body .= "<h2>Rants</h2>\n";
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=addrant\">Add rant</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "<h2>Blogmarks</h2>\n";
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=addmark\">Add blogmark</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "<h2>Logs</h2>\n";
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewlog\">View log</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewreferers\">View referers</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewcommentlog\">View comments log</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "<h2>General</h2>\n";
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=generatefeeds\">Generate RSS feed[s]</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=markstorant\">Wrap up the blogmarks of the last 7 days to a new rant</a></li>\n";
	$page_body .= "\t<li><a href=\"http://www.technorati.com/developers/ping.html?name=dammIT&amp;url=http%3A%2F%2Faquariusoft.org%2F%7Embscholt%2F\">Ping Technorati that site has been updated</a></li>\n";
	$page_body .= "\t<li><a href=\"https://aquariusoft.org/~mbscholt/root.php\">If you are using unencrypted http, please go to the secured https site</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=logout\">Log out</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "<br />\n";

} else
{
	$page_body .= "<h1>Got root?</h1>\n";
	$page_body .= "<h2>Please log in</h2>\n";
	$page_body .= "<p><a href=\"https://aquariusoft.org/~mbscholt/root.php\">If you are using unencrypted http, please go to the secured https site</a></p>\n";
	$page_body .= "<div id=\"loginform\">\n";
	$page_body .= "<form action=\"root.php\" method=\"post\">\n";
	//$page_body .= "User<br/><input type=\"text\" name=\"user\" size=\"16\" maxlength=\"16\" /><br/>\n";
	//$page_body .= "Pass<br/><input type=\"password\" name=\"pass\" size=\"16\" maxlength=\"16\" /><br/>\n";
	//$page_body .= "<br/>\n";
	$page_body .= "<p><input type=\"text\" name=\"user\" size=\"16\" maxlength=\"16\" />&nbsp;<span class=\"heading\">User</span></p>\n";
	$page_body .= "<p><input type=\"password\" name=\"pass\" size=\"16\" maxlength=\"16\" />&nbsp;<span class=\"heading\">Pass</span><p>\n";
	$page_body .= "<input name=\"loginbtn\" value=\"Login\" type=\"submit\" />\n";
	$page_body .= "</form>\n";
	$page_body .= "</div>\n";
	$page_body .= "<br />\n";
}

include 'inc/inc_pagetemplate.php';
?>
