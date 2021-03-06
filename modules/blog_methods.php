<?php
/**
 * Weblog module - methods
 *
 * Copyright 2003-2013 michiel at aquariusoft.org
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

define('CONTENT_RAWHTML', 0);
define('CONTENT_PLAINTEXT', 1);
define('CONTENT_MARKDOWN', 2);
define('ISPUBLIC_YES', 1);
define('ISPUBLIC_NO', 0);

/* The various properties of a typical rant, set here as "constant" */
$skel['rantproperties'] = 'messageid, date, user, ip, title, message, contenttype, initiated, published, ispublic, modified, modifieddate, location, commentsenabled';


/*
 * Tries to log in the user/pass combo
 */
function login( $skel, $user, $pass )
{
    /* Lets first escape this to prevent injection
     * @TODO: use mysqli or PDO prepared statements instead */
    $user = mysql_real_escape_string($user);
    $pass = mysql_real_escape_string($pass);

	/* verify user/pass combo with db */
	$query = 'SELECT smplog_user.pass, smplog_user.id FROM smplog_user WHERE smplog_user.username="' . $user . '";';
	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		if ( md5( $pass ) == $row[0] )
		{
			/* password is valid, return userid */
			return $row[1];
		}
	}
	/* no smplog_user found or password not valid */
	return -1;
}


/*
 * Check whether there is a valid session
 */
function isLoggedIn()
{
	return (isset($_SESSION['username']));
}


/*
 * Get user details for userid $id
 */
function getUser($skel, $id)
{
	$query = 'SELECT id, username, pass, surname, name, ip, restricttoip, stylesheetid, registerdate ' .
		'FROM smplog_user ' .
		'WHERE smplog_user.id=' . $id . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		$user = array();
		$user['id'] = $row[0];
		$user['username'] = $row[1];
		$user['pass'] = $row[2];
		$user['surname'] = $row[3];
		$user['name'] = $row[4];
		$user['ip'] = $row[5];
		$user['restricttoip'] = $row[6];
		$user['stylesheetid'] = $row[7];
		$user['registerdate'] = $row[8];
		return $user;
	} else
	{
		return null;
	}
}


/*
 * Get the total number of smplog_rants in DB
 */
function getNrOfRants($skel)
{
	$query = 'SELECT count(*) FROM smplog_rant;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}


/*
 * Get nr of comments for a smplog_rant entry [only where State=1, e.g. non-deleted ones]
 */
function getNrOfComments( $skel, $rantId = -1 )
{

	if ($rantId > -1)
	{
		$query = 'SELECT count(*) FROM smplog_comment WHERE smplog_comment.rantid = ' . $rantId . ' AND smplog_comment.state=1;';

		$result = mysql_query( $query, $skel['dbLink'] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			return $row[0];
		} else
		{
			return -1;
		}
	} else
	{
		/* We want the total number of comments ever posted */
		$query = 'SELECT count(*) FROM smplog_comment;';

		$result = mysql_query( $query, $skel['dbLink'] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			return $row[0];
		} else
		{
			return -1;
		}
	}
}

function getNrOfDisabledComments( $skel )
{
	$query = 'SELECT count(*) FROM smplog_comment WHERE smplog_comment.state=0;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}


/*
 * Checks whether the smplog_rant belongs to the user
 */
function isRantMine( $skel, $rantId )
{
	$query = 'SELECT user FROM smplog_rant WHERE smplog_rant.messageid=' . $rantId . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0] == $_SESSION['userid'];
	}
}


/*
 *
 */
function areCommentsEnabled($skel, $rantid)
{
	$query = 'SELECT commentsenabled FROM smplog_rant WHERE smplog_rant.messageid=' . $rantid . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0] == '1';
	}
}


/*
 * Converts the MySQL resultset of a query for multiple rants into the rant array we can work with
 */
function resultsetToRants($skel, $result, $getNrOfComments = true)
{
	$rants = array();

	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$rants[$i]['messageID'] = $row[0];
			$rants[$i]['date'] = $row[1];
			$rants[$i]['user'] = $row[2];
			$rants[$i]['username'] = null;
			$rants[$i]['ip'] = $row[3];
			$rants[$i]['title'] = $row[4];
			$rants[$i]['message'] = $row[5];
			$rants[$i]['contenttype'] = $row[6];
			$rants[$i]['initiated'] = $row[7];
			$rants[$i]['published'] = $row[8];
			$rants[$i]['ispublic'] = $row[9];
			$rants[$i]['modified'] = $row[10];
			$rants[$i]['modifiedDate'] = $row[11];
			$rants[$i]['location'] = $row[12];
			$rants[$i]['commentsenabled'] = $row[13];
			if (true === $getNrOfComments)
			{
				$rants[$i]['nrOfComments'] = getNrOfComments( $skel, $rants[$i]['messageID'] );
			} else
			{
				$rants[$i]['nrOfComments'] = -1;
			}
			if (-1 < $rants[$i]['user'])
			{
				// get user name
				$user = getUser($skel, $rants[$i]['user']);
				$rants[$i]['username'] = $user['name'] . ' ' . $user['surname'];
			}
		}
	} else
	{
		$rants = null;
	}
	return $rants;
}


/*
 * Fetches smplog_rants from DB, sorted on date/time, newest first. Starting from number $first, with a max of $number items
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
 * smplog_blogmark
 */
function getRants( $skel, $offset, $number )
{
	$query = 'SELECT ' . $skel['rantproperties'] . ' FROM smplog_rant WHERE ispublic=1 ORDER BY date DESC LIMIT ' . $offset . ', ' . $number . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	return resultsetToRants($skel, $result);
}


function getRantsForMonth( $skel, $year, $month )
{
	$query = 'SELECT ' . $skel['rantproperties'] . ' FROM smplog_rant WHERE ispublic=1 AND YEAR(date)="' . $year . '" AND MONTH(date)="' . $month . '" ORDER BY date DESC';

	$result = mysql_query( $query, $skel['dbLink'] );
	return resultsetToRants($skel, $result);
}


/* All unpublished rants */
function getUnpublishedRants( $skel, $offset, $number )
{
	$query = 'SELECT ' . $skel['rantproperties'] . ' FROM smplog_rant WHERE ispublic=0 ORDER BY date DESC LIMIT ' . $offset . ', ' . $number . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	return resultsetToRants($skel, $result);
}


/* Count the amount of rants that aren't yet published */
function getNrUnpublishedRants( $skel )
{
	$query = 'SELECT count(*) FROM smplog_rant WHERE ispublic=0;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}


/*
 * Returns smplog_rant $id
 * Maybe: Returns array of $number smplog_rants, starting with smplog_rant $id
 */
function getRantByID( $skel, $id )
{
	$rants = array();

	$query = 'SELECT ' . $skel['rantproperties'] . ' FROM smplog_rant WHERE messageid = ' . $id . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	return resultsetToRants($skel, $result);
}


/*
 * Get the post before and after the given post [identified by $rantDate]
 */
function getNextPrevRant($skel, $rantDate)
{
	$rants = array();

	$query = 'SELECT messageid, date, title FROM smplog_rant WHERE ispublic=1 AND date > "' . $rantDate . '" ORDER BY date ASC LIMIT 1;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);

		$rants['next']['messageID'] = $row[0];
		$rants['next']['date'] = $row[1];
		$rants['next']['title'] = $row[2];
	}

	$query = 'SELECT messageid, date, title FROM smplog_rant WHERE ispublic=1 AND date < "' . $rantDate . '" ORDER BY date DESC LIMIT 1;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);

		$rants['prev']['messageID'] = $row[0];
		$rants['prev']['date'] = $row[1];
		$rants['prev']['title'] = $row[2];
	}
	return $rants;
}


/*
 * Find entries which contain text $searchkey
 */
function findRants( $skel, $searchkey )
{
	/* Generate list with smplog_rants, newest first, starting with $first' item in DB, with a max of $number items */
	$rants = array();

	$query = 'SELECT ' . $skel['rantproperties'] . ' FROM smplog_rant WHERE ispublic=1 AND (message LIKE "%' . $searchkey . '%" OR title LIKE "%' . $searchkey . '%") ORDER BY date DESC;';

	$result = mysql_query( $query, $skel['dbLink'] );
	return resultsetToRants($skel, $result);
}


/*
 * Get info about rant $id. Used for notification mails
 */
function getRantsInfo( $skel, $id )
{
	/* Generate list with smplog_rants, newest first, starting with $first' item in DB, with a max of $number items */
	$rants = array();

	$query = 'SELECT messageid, date, title, modified, modifieddate, location FROM smplog_rant WHERE messageid = ' . $id . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$rants[$i]['messageID'] = $row[0];
			$rants[$i]['date'] = $row[1];
			$rants[$i]['title'] = $row[2];
			$rants[$i]['modified'] = $row[3];
			$rants[$i]['modifiedDate'] = $row[4];
			$rants[$i]['location'] = $row[5];
		}
	}
	return $rants;
}


/*
 * Return smplog_rants info from all smplog_rants from $year
 */
function getRantsFromYear( $skel, $year)
{
	/* Generate list with smplog_rants, newest first, starting with $first' item in DB, with a max of $number items */
	$rants = array();
	$query = 'SELECT messageid, date, user, ip, title, modified, modifieddate, location FROM smplog_rant WHERE ispublic=1 AND YEAR(date) = "' . $year . '" ORDER BY date DESC;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$rants[$i]['messageID'] = $row[0];
			$rants[$i]['date'] = $row[1];
			$rants[$i]['user'] = $row[2];
			$rants[$i]['ip'] = $row[3];
			$rants[$i]['title'] = $row[4];
			$rants[$i]['message'] = '';
			$rants[$i]['modified'] = $row[5];
			$rants[$i]['modifiedDate'] = $row[6];
			$rants[$i]['location'] = $row[7];
			$rants[$i]['nrOfComments'] = -1;
		}
	}
	return $rants;
}


/*
 * Return years this blog is running
 */
function getRantYears( $skel )
{
	$query = 'SELECT DISTINCT YEAR(date) FROM smplog_rant ORDER BY YEAR(date) ASC;';

	$result = mysql_query( $query, $skel['dbLink'] );

	if ( mysql_num_rows( $result ) > 0 )
	{
		$years = null;
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);
			$years[$i] = $row[0];
		}
		return $years;
	} else
	{
		return null;
	}

}


/*
 * Creates an empty rant object
 */
function newRant($skel)
{
	//$rant = new array();
	$rant['messageID'] = '-1';
	$rant['date'] = '';
	$rant['user'] = '';
	$rant['ip'] = getenv('REMOTE_ADDR');
	$rant['title'] = '';
	$rant['message'] = '';
	$rant['contenttype'] = CONTENT_RAWHTML;
	$rant['initiated'] = date('Y-m-d G:i:s', time());
	$rant['published'] = '';
	$rant['ispublic'] = ISPUBLIC_NO;
	$rant['modified'] = '';
	$rant['modifiedDate'] = '';
	$ip = getenv('REMOTE_ADDR');
	$rant['location'] = getLocation($skel, $ip);

	return $rant;
}


/*
 * Add new rant to DB
 */
function addRant( $skel, $rant )
{
	$title = escapeValue($rant['title']);
	$location = escapeValue($rant['location']);
	$message = escapeValue($rant['message']);

	$ipaddr = getenv('REMOTE_ADDR');
	$time = date('Y-m-d G:i:s', time());

	/* If we are adding a new rant, and it's public, it has just been published */
	if (1 == $rant['ispublic'])
	{
		$rant['published'] = $time;
	}

	/* Whatever... */
	if (isset($_SESSION) && isset($_SESSION['userid']))
	{
		$user_id = $_SESSION['userid'];
	} else
	{
		$user_id = 1;
	}

	$query = 'INSERT INTO smplog_rant ' .
		'SET date="' . $time . '", user="' . $user_id . '", ip="' . $rant['ip'] . '", title="'. $title .'", location="' . $location . '", message="' . $message .
		'", contenttype=' . $rant['contenttype'] .
		', initiated="' . $rant['initiated'] . '"' .
		', published="' . $rant['published'] . '"' .
		', ispublic=' . $rant['ispublic'] .
		', modified=0, modifieddate="0000-00-00 00:00:00";';

	$result = mysql_query($query, $skel['dbLink']);
	addLogAction($skel, 'addRant', mysql_insert_id($skel['dbLink']));
}


/*
 * Updates smplog_rant with id $rantid with new data
 */
function editRant( $skel, $rant )
{
	/* Get the number of times this smplog_rant's already modified */
	$timesModified = 0;
	$query = 'SELECT modified, ispublic '.
		'FROM smplog_rant ' .
		'WHERE messageid = ' . $rant['id'] . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		$timesModified = $row[0];
		$waspublished = $row[1];
	}

	/* Try to secure the input */
	$title = escapeValue($rant['title']);
	$location = escapeValue($rant['location']);
	$message = escapeValue($rant['message']);

	$ipaddr = getenv('REMOTE_ADDR');
	$time = date('Y-m-d G:i:s', time());

	/* If we are adding a new rant, and it's public, it has just been published */
	if (1 == $rant['ispublic'] && 0 == $waspublished)
	{
		$rant['published'] = $time;
	}

	/* Whatever... */
	$user_id = $_SESSION['userid'];

	/* Now go and update it */
	$query = 'UPDATE smplog_rant ' .
		'SET title="'. $title .'", location="' . $location . '", message="' . $rant .
		'", modified=' . ($timesModified + 1) . ', modifieddate=NOW() ' .
		', published="' . $rant['published'] . '"' .
		', ispublic=' . $rant['ispublic'] .
		'WHERE messageid=' . $rant['id'] . ';';

	$result = mysql_query($query, $skel['dbLink']);
	addLogAction($skel, 'editRant', $rant['id']);
}


/*
 * Fetches smplog_blogmarks from DB, sorted on date/time, newest first. Starting from number $first to $first+$number
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
function getMarks( $skel, $offset, $number )
{
	/* Generate list with smplog_blogmarks, newest first, starting with $first' item in DB, with a max of $number items */
	$marks = array();

	$query = 'SELECT id, date, user, ip, title, uri, location, message, modified, modifieddate FROM smplog_blogmark ORDER BY date DESC LIMIT ' . $offset . ', ' . $number . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$marks[$i]['id'] = $row[0];
			$marks[$i]['date'] = $row[1];
			$marks[$i]['user'] = $row[2];
			$marks[$i]['ip'] = $row[3];
			$marks[$i]['title'] = $row[4];
			$marks[$i]['uri'] = $row[5];
			$marks[$i]['location'] = $row[6];
			$marks[$i]['message'] = $row[7];
			$marks[$i]['modified'] = $row[8];
			$marks[$i]['modifiedDate'] = $row[9];
		}
	}
	return $marks;
}


/*
 * Find entries which contain text $searchkey
 */
function findMarks( $skel, $searchkey )
{
	$marks = array();

	$query = 'SELECT id, date, user, ip, title, uri, location, message, modified, modifieddate FROM smplog_blogmark WHERE message LIKE "%' . $searchkey . '%" OR title LIKE "%' . $searchkey . '%" ORDER BY date DESC;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$marks[$i]['id'] = $row[0];
			$marks[$i]['date'] = $row[1];
			$marks[$i]['user'] = $row[2];
			$marks[$i]['ip'] = $row[3];
			$marks[$i]['title'] = $row[4];
			$marks[$i]['uri'] = $row[5];
			$marks[$i]['location'] = $row[6];
			$marks[$i]['message'] = $row[7];
			$marks[$i]['modified'] = $row[8];
			$marks[$i]['modifiedDate'] = $row[9];
		}
	}
	return $marks;
}


/*
 * $marksPerMonth:
 * month of year [e.g. number]
 * number of smplog_blogmarks for that month
 */
function getMarksPerMonth( $skel, $year )
{
	$results = array();

	$query = "SELECT MONTH(smplog_blogmark.date), COUNT(*) FROM smplog_blogmark WHERE YEAR(smplog_blogmark.date) = " . $year . " GROUP BY MONTH(smplog_blogmark.date) DESC;";

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$results[$i]['month'] = $row[0];
			$results[$i]['nrofpostings'] = $row[1];
		}
	}
	return $results;
}


/*
 * Same as getMarksByMonth, but by year
 */
function getMarksPerYear( $skel )
{
	$results = array();

	$query = "SELECT YEAR(smplog_blogmark.date), COUNT(*) FROM smplog_blogmark GROUP BY YEAR(smplog_blogmark.date) DESC;";

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$results[$i]['year'] = $row[0];
			$results[$i]['nrofpostings'] = $row[1];
		}
	}
	return $results;
}


/* Return all months of year $year where smplog_blogmarks have been posted */
function getMarkMonths( $skel, $year )
{
	$results = array();

	$query = "SELECT DISTINCT MONTH(smplog_blogmark.date) FROM smplog_blogmark WHERE YEAR(smplog_blogmark.date) = " . $year . " ORDER BY smplog_blogmark.date ASC;";

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$results[$i] = $row[0];
		}
	}
	return $results;
}

/* Get all smplog_blogmarks from the requested month
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
function getMarksByMonth( $skel, $year, $month )
{
	/* Generate list with smplog_blogmarks, newest first, starting with $first' item in DB, with a max of $number items */
	$marks = array();

	$query = 'SELECT id, date, user, ip, title, uri, location, message, modified, modifieddate FROM smplog_blogmark ' .
		'WHERE YEAR(smplog_blogmark.date)=' . $year . ' AND MONTH(smplog_blogmark.date)=' . $month . ' ' .
		'ORDER BY Date DESC;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$marks[$i]['id'] = $row[0];
			$marks[$i]['date'] = $row[1];
			$marks[$i]['user'] = $row[2];
			$marks[$i]['ip'] = $row[3];
			$marks[$i]['title'] = $row[4];
			$marks[$i]['uri'] = $row[5];
			$marks[$i]['location'] = $row[6];
			$marks[$i]['message'] = $row[7];
			$marks[$i]['modified'] = $row[8];
			$marks[$i]['modifiedDate'] = $row[9];
		}
	}
	return $marks;
}


function getMarksByDateRange( $skel, $begin, $end )
{
	/* Generate list with smplog_blogmarks, newest first, starting with $first' item in DB, with a max of $number items */
	$marks = array();

	$query = 'SELECT id, date, user, ip, title, uri, location, message, modified, modifieddate FROM smplog_blogmark ' .
		'WHERE smplog_blogmark.date>="' . $begin . '" AND smplog_blogmark.date<="' . $end . '" ' .
		'ORDER BY Date DESC;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$marks[$i]['id'] = $row[0];
			$marks[$i]['date'] = $row[1];
			$marks[$i]['user'] = $row[2];
			$marks[$i]['ip'] = $row[3];
			$marks[$i]['title'] = $row[4];
			$marks[$i]['uri'] = $row[5];
			$marks[$i]['location'] = $row[6];
			$marks[$i]['message'] = $row[7];
			$marks[$i]['modified'] = $row[8];
			$marks[$i]['modifiedDate'] = $row[9];
		}
	}
	return $marks;
}


/*
 * Returns smplog_blogmark $id
 */
function getMarkByID( $skel, $id )
{
	$marks = array();

	$query = 'SELECT id, date, user, ip, title, uri, location, message, modified, modifieddate FROM smplog_blogmark WHERE id=' . $id . ';';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$marks[$i]['id'] = $row[0];
			$marks[$i]['date'] = $row[1];
			$marks[$i]['user'] = $row[2];
			$marks[$i]['ip'] = $row[3];
			$marks[$i]['title'] = $row[4];
			$marks[$i]['uri'] = $row[5];
			$marks[$i]['location'] = $row[6];
			$marks[$i]['message'] = $row[7];
			$marks[$i]['modified'] = $row[8];
			$marks[$i]['modifiedDate'] = $row[9];
		}
	}
	return $marks;
}


/*
 * Add new mark to DB
 */
function addMark( $skel, $title, $uri, $location, $description )
{
	$title = escapeValue($title);
	$uri = escapeValue($uri);
	$location = escapeValue($location);
	$description = escapeValue($description);

	$ipaddr = getenv('REMOTE_ADDR');
	$time = date('Y-m-d G:i:s', time());

	/* Whatever... */
	$user_id = $_SESSION['userid'];

	$query = 'INSERT INTO smplog_blogmark ' .
		'SET date="' . $time . '", user="' . $user_id . '", ip="' . $ipaddr . '", title="'. $title .'", uri="' . $uri . '", location="' . $location . '", message="' . $description .
		'", modified=0, modifieddate="0000-00-00 00:00:00";';

	$result = mysql_query($query, $skel['dbLink']);
}


/*
 * Updates smplog_rant with id $rantid with new data
 */
function editMark( $skel, $title, $location, $rant, $rantid )
{
	$title = escapeValue($title);
	$location = escapeValue($location);
	$rant = escapeValue($rant);

	$ipaddr = getenv("REMOTE_ADDR");
	$time = date("Y-m-d G:i:s", time());

	/* Whatever... */
	$user_id = $_SESSION['userid'];

	$query = 'UPDATE smplog_rant ' .
		'SET date="' . $time . '", user="' . $user_id . '", ip="' . $ipaddr . '", title="'. $title .'", location="' . $location . '", message="' . $rant .
		'", modified=0, modifieddate=NOW() ' .
		'WHERE messageid=' . $rantid . ';';

	$result = mysql_query($query, $skel['dbLink']);
}


/*
 * Get the total number of marks in DB
 */
function getNrOfMarks($skel)
{
	$query = 'SELECT count(*) FROM smplog_blogmark;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}



function markUri($mark)
{
	//return 'blogmarks.php?year=' . getYear($mark['date']) . '&amp;month=' . getMonth($mark['date']) . '#uri' . $mark['id'];
	return 'm/' . getYear($mark['date']) . '/' . getMonth($mark['date']) . '#uri' . $mark['id'];
}


function marksToRant($skel)
{
	$begin = date('Y-m-d G:i:s', time() - (7 * 3600 * 24));
	$end = date('Y-m-d G:i:s', time());

	$title = 'Blogmarks for ' . getNormalDate($end);
	$location = 'Server';


	$marks = getMarksByDateRange( $skel, $begin, $end );
	if (0 == count($marks))
	{
		return 'No new blogmarks this week';
	}
	$message = "<p>Interesting links of this week:</p>\n";
	$message .= buildInPostMarks($marks);

	$rant = newRant($skel);
	$rant['title'] = $title;
	$rant['location'] = $location;
	$rant['message'] = $message;
	$rant['contenttype'] = CONTENT_RAWHTML;
	$rant['ispublic'] = ISPUBLIC_YES;
	$rant['ip'] = '127.0.0.1';
	$rant['initiated'] = $end;
	addRant( $skel, $rant );
	return '';
}


/*
 * Get nr of comments for a smplog_rant entry
 */
function getAllComments( $skel, $rantId, $wantallcomments )
{
	$comments = array();

	/* Select all comments belonging to smplog_rant $rantId and sort them with the latest on top^W bottom */
	if (true == isLoggedIn() && $wantallcomments == true)
	{
		/* Get all comments */
		$query = 'SELECT id, rantid, date, ip, client, name, email, wantnotifications, uri, message, state FROM smplog_comment WHERE smplog_comment.rantid = ' . $rantId . ' ORDER BY Date ASC;';
	} else
	{
		/* Only get enabled comments */
		$query = 'SELECT id, rantid, date, ip, client, name, email, wantnotifications, uri, message, state FROM smplog_comment WHERE smplog_comment.rantid = ' . $rantId . ' AND smplog_comment.state=1 ORDER BY Date ASC;';
	}

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$comments[$i]['id'] = $row[0];
			$comments[$i]['rantId'] = $row[1];
			$comments[$i]['date'] = $row[2];
			$comments[$i]['ip'] = $row[3];
			$comments[$i]['client'] = $row[4];
			$comments[$i]['name'] = $row[5];
			$comments[$i]['email'] = $row[6];
			$comments[$i]['wantnotifications'] = $row[7];
			$comments[$i]['uri'] = $row[8];
			$comments[$i]['message'] = $row[9];
			$comments[$i]['state'] = $row[10];
		}
	}
	return $comments;
}


/*
 * Get all comments [including the disabled ones when logged in]
 */
function getComments( $skel, $rantId )
{
	return getAllComments( $skel, $rantId, true );
}


function getLatestComments( $skel, $limit = 3, $wantallcomments )
{
	$comments = array();

	/* Select all comments belonging to smplog_rant $rantId and sort them with the latest on top^W bottom */
	if (true == isLoggedIn() && $wantallcomments == true)
	{
		/* Get all comments */
		$query = 'SELECT id, rantid, date, ip, client, name, email, wantnotifications, uri, message, state FROM smplog_comment ORDER BY Date DESC LIMIT ' . $limit . ';';
	} else
	{
		/* Only get enabled comments */
		$query = 'SELECT id, rantid, date, ip, client, name, email, wantnotifications, uri, message, state FROM smplog_comment WHERE smplog_comment.state=1 ORDER BY Date DESC LIMIT ' . $limit . ';';
	}

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$comments[$i]['id'] = $row[0];
			$comments[$i]['rantId'] = $row[1];
			$comments[$i]['date'] = $row[2];
			$comments[$i]['ip'] = $row[3];
			$comments[$i]['client'] = $row[4];
			$comments[$i]['name'] = $row[5];
			$comments[$i]['email'] = $row[6];
			$comments[$i]['wantnotifications'] = $row[7];
			$comments[$i]['uri'] = $row[8];
			$comments[$i]['message'] = $row[9];
			$comments[$i]['state'] = $row[10];
		}
	}
	return $comments;
}


/*
 * Add new comment to DB
 *
 * Should be validated before reaching this function [e.g., $email should be valid in case of $wantsNotifications
 *   and $message && $name != empty
 *
 * comment:
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
function addComment($skel, $rantId, $name, $email, $wantnotifications, $uri, $message)
{
	$unescapedMessage = $message;

	$message = htmlentities($message);

	$name = escapeValue($name);
	$email = escapeValue($email);
	if ("http://" == trim($uri))
	{
		$uri = "";
	}
	$uri = escapeValue($uri);
	$message = escapeValue($message);

	$ipaddr = getenv("REMOTE_ADDR");
	$time = date("Y-m-d G:i:s", time());

	$wantnotificationsInt = 0;
	$wantnotificationsString = "no";
	if ($wantnotifications)
	{
		$wantnotificationsInt = 1;
		$wantnotificationsString = "yes";
	}

	$query = 'INSERT INTO smplog_comment ' .
		'SET rantid=' . $rantId . ', date="' . $time . '", ip="' . $ipaddr . '", client="'. getenv("HTTP_USER_AGENT") .'", name="' . $name . '", email="' . $email . '", wantnotifications=' . $wantnotificationsInt . ', uri="' . $uri . '", message="' . $message .
		'", state=1;';

	$querysuccess = mysql_query($query, $skel['dbLink']);

	$result = "";
	if (false == $querysuccess)
	{
		//something went wrong with the query
		$result .= "Error while executing query\n";
	}

	// Get information about the current weblog entry
	$rantsInfo = getRantsInfo($skel, $rantId);

	// Now send an announcement email
	if ("" == $email)
	{
		$email = "no e-mail address provided";
	}
	// Mail configuration
	$body = "\nA comment has been added by " . $name . " [" . $email . "].\nTime of comment: " . $time . "\nPoster's uri: " . $uri . "\nPoster wants notifications: " . $wantnotificationsString . "\n\n== Posting ======\n" . $rantsInfo[0]['title'] . " [date: " . $rantsInfo[0]['date'] . "]\n" . $skel['base_server'] . $skel['base_uri'] . "p/" . $rantId . "\n\n";
	$body .= "== comment ======\n" . $unescapedMessage . "\n";

	$emailresult = sendEmail($skel['mailFrom'], $skel['mailFromName'], $skel['mailTo'], $skel['mailSubject'] . " about \"" . $rantsInfo[0]['title'] . "\"", $body);

	if (false == $emailresult)
	{
		$result .= "Error while sending notification mail to web log owner\n";
	}

	/* Now send everybody that reacted on the post and wanted a notification an e-mail */
	$body = "\nA comment has been added by " . $name . " to posting\n" . $rantsInfo[0]['title'] . " [posted " . $rantsInfo[0]['date'] . "]\n" . $skel['base_server'] . $skel['base_uri'] . "p/" . $rantId . "\nTime of comment: " . $time . "\n\n== comment ======\n" . $unescapedMessage . "\n\n== End of comment ======\nIf you receive this message and don't know why you're getting it, please check the uri provided, " . $skel['base_server'] . $skel['base_uri'] . " or e-mail to " . $skel['mainEmail'];

	/* Get all e-mail addresses that had the checkbox checked */

	$wantnotificationList = getWantNotification($skel, $rantId);
	for ($i = 0; $i < count($wantnotificationList); $i++)
	{
		/* Don't send e-mail to the person commenting now, and not to an empty address */
		if ($email != $wantnotificationList[$i] && "" != $email)
		{
			sendEmail($skel['mailFrom'], $skel['mailFromName'], $wantnotificationList[$i], $skel['mailNotificationSubject'] . " about \"" . $rantsInfo[0]['title'] . "\"", $body);
		}
	}

	return $result;
	//if nonexisting rant -> return -1 as error
}


/*
 * Disables ['deletes'] a comment from being viewed
 */
function disableComment($skel, $commentid)
{
	$query = "UPDATE smplog_comment " .
		"SET state=0 WHERE id=" . $commentid . ";";

	$querysuccess = mysql_query($query, $skel['dbLink']);

	$result = "";
	if (false == $querysuccess)
	{
		$result .= "Error while executing query\n";
	}
	return $result;
}


/*
 * Enables ['recovers'] a comment so it's shown again
 */
function enableComment($skel, $commentid)
{
	$query = "UPDATE smplog_comment " .
		"SET state=1 WHERE id=" . $commentid . ";";

	$querysuccess = mysql_query($query, $skel['dbLink']);

	$result = "";
	if (false == $querysuccess)
	{
		//something went wrong with the query
		$result .= "Error while executing query\n";
	}
	return $result;
}

/*
 * Disables commenting for post $rantid
 */
function disableCommentsForPost($skel, $rantid)
{
	$query = "UPDATE smplog_rant " .
		"SET commentsenabled=0 WHERE messageid=" . $rantid . ";";

	$querysuccess = mysql_query($query, $skel['dbLink']);

	$result = '';
	if (false == $querysuccess)
	{
		//something went wrong with the query
		$result .= "Error while executing query\n";
	}
	return $result;
}


/*
 * [Re]enables commenting for post $rantid
 */
function enableCommentsForPost($skel, $rantid)
{
	$query = "UPDATE smplog_rant " .
		"SET commentsenabled=1 WHERE messageid=" . $rantid . ";";

	$querysuccess = mysql_query($query, $skel['dbLink']);

	$result = '';
	if (false == $querysuccess)
	{
		//something went wrong with the query
		$result .= "Error while executing query\n";
	}
	return $result;
}


/*
 *
 */
function getWantNotification($skel, $rantId)
{
	$addresses = array();

	/* Select all comments belonging to smplog_rant $rantId and sort them with the latest on top^W bottom */
	$query = 'SELECT DISTINCT email FROM smplog_comment WHERE smplog_comment.rantid = ' . $rantId . ' AND smplog_comment.wantnotifications=1 AND smplog_comment.state=1;';

	$result = mysql_query( $query, $skel['dbLink'] );
	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);

			$addresses[$i] = $row[0];
		}
	}
	return $addresses;
}


/*
 * Formats normal datetime sting $datetime to an xml datetime format, a.o. used for RSS
 */
function getRSSDateTime($datetime)
{
	/* Make something like "2003-12-22T20:00:00+01:00" from "2003-12-22 20:00:00" */
	$dateAndTime = explode(" ", $datetime);	//split date and time
	return $dateAndTime[0] . "T" . $dateAndTime[1] . "+01:00";
}

function getLocation($skel, $ip)
{
	$parts = explode('.', $ip);
	$keys = array_keys($skel['locations']);
	for ($i = 0; $i < count($keys); $i++)
	{
		$locparts = explode('.', $keys[$i]);
		$match = matchValues($locparts[0], $parts[0]) &&
			matchValues($locparts[1], $parts[1]) && 
			matchValues($locparts[2], $parts[2]) && 
			matchValues($locparts[3], $parts[3]);
		if ($match)
		{
			return($skel['locations'][$keys[$i]]);
		}
	}
}

function matchValues($val1, $val2)
{
	return ('*' == $val1 || '*' == $val2 || $val1 == $val2);
}


function escapeValue($value)
{
	// Stripslashes
	if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	return mysql_real_escape_string($value);
}


function getRequestParam($paramname, $default)
{
	if (isset($_REQUEST[$paramname]))
	{
		if (myIsInt($default))
		{
			return intval($_REQUEST[$paramname]);
		} else
		{
			if (get_magic_quotes_gpc())
			{
				return stripslashes($_REQUEST[$paramname]);
			}
			return $_REQUEST[$paramname];
		}
	} else
	{
		return $default;
	}
}
