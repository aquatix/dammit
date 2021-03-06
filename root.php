<?php
/**
 * The admin module for the weblog
 *
 * Copyright 2003-2014 michiel at aquariusoft.org
 * Please refer to the COPYRIGHT file distributed with this source distribution.
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

/* Enable error reporting */
//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

$skel['lastmodified'] = '2014-08-10';
$skel['page_version'] = '0.8.03';
$skel['dateofcreation'] = '2003-12-22';

$section_name = 'root';
$page_name = 'home';

include 'modules/init.php';

/* Record a hit on this page to the log */
addToLog( $skel, $section_name, $page_name, $skel['page_version'] );

$page_body = '';
$page_name = 'root';

session_name($skel['session_name']);
session_start();

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
/*		session_name($skel['session_name']);
		if (!isset($_SESSION))
		{
			session_start();
		}
*/
		$_SESSION['username'] = $user;
		$_SESSION['userid'] = $userid;
	} else
	{
		$page_body .= "<h1>Error!</h1>\n<p>Not a valid user/pass combo!</p>\n<p><a href=\"root.php\" class=\"button\">&laquo; Back</a></p>\n<br /><br /><br /><br />\n";
		echo buildPage($skel, $section_name, $page_name, $page_body);
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
		//echo "saveKind = " . $saveKind . "\n";
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
			if (-1 == $rant['rantid'])
			{
				/* Trying to add rant to DB */
				addRant($skel, $rant);

			} else
			{
				/* Only save, it was already added */
				editRant($skel, $rant);
			}
			/* Update RSS feed[s] */
			updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= $root_nav;
			$page_body .= "<h1>root / rant added!</h1>\n";
			$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
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
					$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>\n";
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
			$page_body .= "<p>Posting does not belong to you, so you can't edit it.</p><p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n";
		}
	} else if ('listunpublished' == $action)
	{
		$offset = 0;
		$number = 100;
		$unpublished_rants = getUnpublishedRants( $skel, $offset, $number );
		$page_body .= $root_nav;
		if (null == $unpublished_rants)
		{
			$page_body .= "<h1>root / sorry</h1>\n<p>No unpublished rants where found.</p><p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n";
		} else
		{
			$page_body .= buildRants($unpublished_rants);
		}
	} else if ('listcomments' == $action)
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / comments</h1>\n";
		$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n";
		$latestComments = getLatestComments($skel, 50, true);
		//$page_body .= "<h3>Latest</h3>\n";
		$page_body .= "<ul>\n";
		$page_body .= buildCommentsList($latestComments);
		$page_body .= "</ul>\n";
		$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n";
    } else if ('addcomment' == $action)
    {
        $page_body .= $root_nav;
        $page_body .= "<h1>root / add comment from other source</h1>\n";
		$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n";

        $showform = true;
        if (isset($_POST['submitting']) && $_POST['submitting'] == "true")
        {
            /* User submitted edited rant, check it now */
            if (isset($_POST['title']) && isset($_POST['location']) && isset($_POST['rant']) && isset($_POST['id']))
            {
                /* Save/add */
            } else
            {
                $page_body .= $root_nav;
                $page_body .= "<h1>root / error!</h1>\n";
                $page_body .= "<p>Something went wrong while adding the comment</p>\n";
                $showform = false;
            }
        }

        if ($showform)
        {
            //name, email, datetime, commentbody
            $page_body .= "<form action=\"root.php?action=addcomment\" method=\"post\">\n";
            $page_body .= "<p><input name=\"submitbtn\" value=\"Save\" type=\"submit\"/></p>\n";
            $page_body .= "</form>\n";
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
			addMark($skel, getRequestParam('title', null), getRequestParam('uri', null), getRequestParam('location', null), getRequestParam('description', null));
			/* Update RSS feed[s] */
			updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= $root_nav;
			$page_body .= "<h1>root / blogmark added!</h1>\n";
			$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
		} else
		{
			$page_body .= "<h1>Error!</h1>\n<p>Not a valid blogmark submitted :)</p>\n<br /><br /><br /><br />\n";
		}
	} else if ($action == "markstorant")
	{
		$result = marksToRant($skel);
		if ('' == $result)
		{
			updateWeblogFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			updateWeblogCommentsFeed($skel, getRants($skel, 0, $skel['nrOfItemsInFeed']));
			//updateWebmarksFeed($skel, getMarks($skel, 0, $skel['nrOfItemsInFeed']));
			$page_body .= "<h1>root / rant with blogmarks of this week added</h1>\n<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>\n";
		} else
		{
			$page_body .= '<h1>root / rant with blogmarks of this week NOT added</h1>\n<p>' . $result . "</p><p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>\n";
		}
	} else if ($action == "disablecomment")
	{
		$commentid = getRequestParam('commentid', -1);
		if ($commentid > 1)
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
				$commentRantID = getRequestParam('rantid', -1);
				$page_body .= $root_nav;
				$page_body .= "<h1>root / remove [disable] comment</h1>\n";
				$page_body .= "<p>Comment #" . $commentid . " removed and comment feed updated</p>\n";
				if ($commentRantID > 0)
				{
					$page_body .= "<p><a href=\"root.php?action=disablecommentsforpost&rantid=" . $commentRantID . "\">Disable comments for the parent posting</a></p>\n";
					$page_body .= "<p><a href=\"index.php?rantid=" . $commentRantID . "\">Go back to the posting</a> / <a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
				}
				//$page_body .= '<p><a href="index.php?rantid=' . getRantForComment($skel, $commentid) . "\">Go back to the posting</a> / <a href=\"root.php\">Go back to Root</a></p>\n<br/><br/><br/><br/>";
			}
		} else
		{
			$page_body .= $root_nav;
			$page_body .= "<h1>root / error!</h1>\n";
			$page_body .= "<p>Not a valid comment selected</p>\n";
		}
	} else if ($action == "enablecomment")
	{
		$commentid = getRequestParam('commentid', -1);
		if ($commentid > 1)
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
				$commentRantID = getRequestParam('rantid', -1);
				$page_body .= $root_nav;
				$page_body .= "<h1>root / recover [enable] comment</h1>\n";
				$page_body .= "<p>Comment #" . $commentid . " recovered and comment feed updated</p>\n";
				$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
				if ($commentRantID > 0)
				{
					$page_body .= "<p><a href=\"index.php?rantid=" . $commentRantID . "\">Go back to the posting</a> / <a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
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
				$page_body .= "<p><a href=\"index.php?rantid=" . $rantid . "\" class=\"button\">&laquo; Back to the posting</a> <a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
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
				$page_body .= "<p><a href=\"index.php?rantid=" . $rantid . "\" class=\"button\">&laquo; Back to the posting</a> <a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
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
		$page_body .= "<p>Feeds refreshed.</p>\n<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>";
	} else if ( $action == "viewlog" )
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / view log</h1>\n";
		$page_body .= "<h2>Views per day of last year</h2>\n";
		$start_date = "2007-12-31";
		$end_date = "2008-12-31";
		//print_r(getNumberOfViewsOverviewPerPage( $skel, $start_date, $end_date ));
		$page_body .= toTable(getNumberOfViewsOverviewPerDay( $skel, $start_date, $end_date ));
		$page_body .= "<p><a href=\"root.php?action=getlogperday\">Download this overview</a></p>\n";
		$page_body .= "<p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>\n";
	} else if ( $action == 'getlogperday' )
	{
		$start_date = "2007-12-31";
		$end_date = "2008-12-31";
		echo toPlainTable(getNumberOfViewsOverviewPerDay( $skel, $start_date, $end_date ));
		exit;
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
		$page_body .= "<p>Not implemented yet!</p><p><a href=\"root.php\" class=\"button\">Root &raquo;</a></p>\n<br/><br/><br/><br/>\n";
	} else if ( $action == "logout" )
	{
		/* user wants to log out */
		unset($_SESSION['username']);
		/* Destroy session vars */
		$_SESSION = array();
		session_destroy();
		$user_name = null;
		$user_pass = null;
		$page_body .= "<h1>Logged out!</h1>\n<p><a href=\"index.php\" class=\"button\">Go back to rantbox &raquo;</a></p>\n<br/><br/><br/><br/>";
	} else
	{
		$page_body .= $root_nav;
		$page_body .= "<h1>root / error!</h1>\n";
		$page_body .= "<p>Not a valid action!</p>\n<p><a href=\"index.php\" class=\"button\">Go back to rantbox &raquo;</a></p>\n<br /><br /><br /><br />\n";
	}
} else if (isLoggedIn())
{
	$page_body .= $root_nav;
	$page_body .= "<h1>Got root!</h1>\n";
	$page_body .= "<div class=\"rootblock\">\n";
	$page_body .= "<h2>Rants</h2>\n";
/*
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=addrant\">Add rant</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=listunpublished\">Unpublished rants</a></li>\n";
*/
	$page_body .= "\t<p><a href=\"root.php?action=addrant\" class=\"button\">Add rant</a>\n";
	$page_body .= "\t  <a href=\"root.php?action=listunpublished\" class=\"button\">Unpublished rants</a></p>\n";

	$nrRantsWritten = getNrOfRants($skel);
	$page_body .= "\t<p>Rants written: " . $nrRantsWritten . "</p>\n";
	$page_body .= "\t<h3>Drafts</h3>\n";
	$offset = 0;
	$number = 3;
	$unpublished_rants = getUnpublishedRants( $skel, $offset, $number );
	$page_body .= "\t\t<ul>\n";
	for ($i = 0; $i < count($unpublished_rants); $i++)
	{
		$page_body .= "\t\t\t<li><span class=\"note\">" . $unpublished_rants[$i]['initiated'] . " -</span> <a href=\"root.php?action=editrant&amp;rantid=" . $unpublished_rants[$i]['messageID'] . "\">" .  $unpublished_rants[$i]['title'] . "</a></li>\n";
	}
	$nrUnpublishedRants = max(getNrUnpublishedRants($skel) - 3, 0);
	$rantsMultiple = '';
	if ($nrUnpublishedRants != 1) { $rantsMultiple = 's'; }
	$page_body .= "\t\t\t<li>" . $nrUnpublishedRants . " additional unpublished rant" . $rantsMultiple . "</li>\n";
	$page_body .= "\t\t</ul>\n";
	//$page_body .= "</ul>\n";
	$page_body .= "</div>\n";
	$page_body .= "<div class=\"rootblock\">\n";
	$page_body .= "<h2>Blogmarks</h2>\n";
	$page_body .= "\t<p><a href=\"root.php?action=addmark\" class=\"button\">Add blogmark</a></p>\n";
	$latestComments = getLatestComments($skel, 3, true);
	$page_body .= "</div>\n";
	$page_body .= "<div class=\"rootblock\">\n";
	$page_body .= "<h2>Comments</h2>\n";
	$page_body .= "\t<p><a href=\"root.php?action=addcomment\" class=\"button\">Add comment manually</a> (for example to import from other source)</p>\n";
	$nrCommentsTotal = getNrOfComments($skel);
	$nrCommentsDisabled = getNrOfDisabledComments($skel);
	$page_body .= "<p>Total comments: " . $nrCommentsTotal . " of which " . $nrCommentsDisabled . " are disabled (netting " . ($nrCommentsTotal - $nrCommentsDisabled) . ", averaging " . number_format(($nrCommentsTotal - $nrCommentsDisabled) / $nrRantsWritten, 2) . " per rant)</p>\n";
	$page_body .= "<h3>Latest</h3>\n";
	$page_body .= "<ul>\n";
	$page_body .= buildCommentsList($latestComments);
	$page_body .= "\t<li><a href=\"root.php?action=listcomments\">Show 50 latest comments</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "</div>\n";
	//$page_body .= "<div class=\"column_left\">\n";
	$page_body .= "<div class=\"rootblock\">\n";
	$page_body .= "<h2>Logs</h2>\n";
	$page_body .= "<ul>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewlog\">View log</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewreferers\">View referers</a></li>\n";
	$page_body .= "\t<li><a href=\"root.php?action=viewcommentlog\">View comments log</a></li>\n";
	$page_body .= "</ul>\n";
	$page_body .= "</div>\n";
	//$page_body .= "</div>\n";
	//$page_body .= "<div class=\"column_right\">\n";
	$page_body .= "<div class=\"rootblock\">\n";
	//$page_body .= "<h2>General</h2>\n";
	//$page_body .= "\t<p><a href=\"root.php?action=logout\" class=\"button\">Log out</a></p>\n";
	$page_body .= "\t<p><a href=\"root.php?action=generatefeeds\" class=\"button\" title=\"Generate RSS feed[s]\">Generate RSS</a>";
	$page_body .= "     <a href=\"root.php?action=markstorant\" class=\"button\" title=\"Wrap up the blogmarks of the last 7 days to a new rant\">Blogmarks post</a>";
	$page_body .= "     <a href=\"http://www.technorati.com/developers/ping.html?name=dammIT&amp;url=http%3A%2F%2Faquariusoft.org%2F%7Embscholt%2F\" class=\"button\" title=\"Ping Technorati that site has been updated\">Ping</a>";
	$page_body .= "</p>\n";
	$page_body .= "</div>\n";
	//$page_body .= "</div>\n";
	//$page_body .= "<br style=\"clear: both;\" />\n";
	$page_body .= "<br />\n";

} else
{
	$page_body .= "<h1>Got root?</h1>\n";
	$page_body .= "<h2>Please log in</h2>\n";
	//$page_body .= "<p><a href=\"https://aquariusoft.org/~mbscholt/root.php\">If you are using unencrypted http, please go to the secured https site</a></p>\n";
	$page_body .= "<div id=\"loginform\">\n";
	$page_body .= "<form action=\"root.php\" method=\"post\">\n";
	//$page_body .= "User<br/><input type=\"text\" name=\"user\" size=\"16\" maxlength=\"16\" /><br/>\n";
	//$page_body .= "Pass<br/><input type=\"password\" name=\"pass\" size=\"16\" maxlength=\"16\" /><br/>\n";
	//$page_body .= "<br/>\n";
	$page_body .= "<h3>User</h3>\n";
	$page_body .= "<p><input type=\"text\" name=\"user\" size=\"16\" /></p>\n";
	$page_body .= "<h3>Pass</h3>\n";
	$page_body .= "<p><input type=\"password\" name=\"pass\" size=\"16\" /><p>\n";
	$page_body .= "<input name=\"loginbtn\" value=\"Login\" type=\"submit\" />\n";
	$page_body .= "</form>\n";
	$page_body .= "</div>\n";
	$page_body .= "<br />\n";
}

echo buildPage($skel, $section_name, $page_name, $page_body);
