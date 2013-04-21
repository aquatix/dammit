<?php
/**
 * Log module - methods
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


function getLog($db_link, $filter_on, $offset, $nrofitems)
{
	//
}


function addToLog( $skel, $section_name, $page_name, $page_version )
{
	$ipaddr = getenv("REMOTE_ADDR");
	$time = date("Y-m-d G:i:s", time());

	/* Whatever... */
	$user_id = 0;

	$query = 'INSERT INTO smplog_log ' .
		'SET date="' . $time . '", ip="' . $ipaddr . '", client="' . getenv("HTTP_USER_AGENT") .
		'", referer="' . getenv("HTTP_REFERER") .
		'", section="' . $section_name . '", page="' . $page_name . '", pageversion="' .
		$page_version . '", userid="' . $user_id . '";';

	$result = mysql_query($query, $skel["dbLink"]);

	/* @TODO: error check and return -1 on error */

	/* All went well */
	return 1;
}


function getNumberOfViews( $skel, $section_name, $page_name )
{
	$query = 'SELECT COUNT(*) FROM smplog_log ' .
		'WHERE section="' . $section_name . '" AND page="' . $page_name . '";';
	$result = mysql_query($query, $skel['dbLink']);
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}


/* Return all section/page pairs with total number of views between $start_date and $end_date */
function getNumberOfViewsOverviewPerPage( $skel, $start_date, $end_date )
{
	$results = array();
	$query = 'SELECT section, page, COUNT(*) FROM smplog_log ' .
		'WHERE date>"' . $start_date . '" AND date<="' . $end_date . '" GROUP BY page, section;';
	$result = mysql_query($query, $skel['dbLink']);
	if ( mysql_num_rows( $result ) > 0 )
	{
		 for ($i = 0; $i < mysql_num_rows( $result ); $i++)
                {    
                        $row = mysql_fetch_row($result);

                        $results[$i]['section'] = $row[0];
                        $results[$i]['page'] = $row[1];
                        $results[$i]['views'] = $row[2];
		}
		return $results;
	} else
	{
		return null;
	}
}


/* Return all section/page pairs with total number of views between $start_date and $end_date */
function getNumberOfViewsOverviewPerDay( $skel, $start_date, $end_date )
{
	$results = array();
	$query = 'SELECT left(date, 11), COUNT(*) FROM smplog_log ' .
		'WHERE date>"' . $start_date . '" AND date<="' . $end_date . '" GROUP BY left(date, 11);';
	$result = mysql_query($query, $skel['dbLink']);
	if ( mysql_num_rows( $result ) > 0 )
	{
		 for ($i = 0; $i < mysql_num_rows( $result ); $i++)
                {    
                        $row = mysql_fetch_row($result);

                        $results[$i]['date'] = $row[0];
                        $results[$i]['views'] = $row[1];
		}
		return $results;
	} else
	{
		return null;
	}
}


function getNumberOfViewsForPage( $skel, $section_name, $page_name )
{
	$query = 'SELECT section, page, COUNT(*) FROM smplog_log ' .
		'WHERE section="' . $section_name . '" AND page="' . $page_name . '";';
	$result = mysql_query($query, $skel['dbLink']);
	if ( mysql_num_rows( $result ) > 0 )
	{
		$row = mysql_fetch_row($result);
		return $row[0];
	} else
	{
		return -1;
	}
}

function getReferers( $skel, $section_name, $page_name )
{
	$webloghref = $skel['base_server'] . $skel['base_uri'];

	$query = 'SELECT referer, COUNT(*) FROM smplog_log ' .
		'WHERE section="' . $section_name . '" AND page="' . $page_name . '" ' .
		'AND referer NOT LIKE "' . $webloghref . '%.php" ' .
		'AND referer!="" GROUP BY referer;';

	$result = mysql_query($query, $skel['dbLink']);
	$referers = array();

	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);
			$referers[$i]['uri'] = $row[0];
			$referers[$i]['count'] = $row[1];
		}
	}
	return $referers;
}


function getAllReferers( $skel )
{
	$webloghref = $skel['base_server'] . $skel['base_uri'];
	$query = 'SELECT Section, Page, Referer, COUNT(*) FROM Log ' .
		'WHERE Referer NOT LIKE "' . $webloghref . '%" ' .
		'AND Referer NOT LIKE "http://192.168.0.150/~mbscholt/%" AND Referer NOT LIKE "http://www.aquariusoft.org/~mbscholt/%" AND Referer NOT LIKE "http://aquariusoft.org/\%7E%" AND Referer NOT LIKE "http://xcalibur.aquariusoft.org/~mbscholt/%" ' .
		'AND Referer!="" ' .
		'GROUP BY Referer ORDER BY Section, Page ASC;';

	$result = mysql_query($query, $skel["dbLink"]);
	$referers = array();

	if ( mysql_num_rows( $result ) > 0 )
	{
		for ($i = 0; $i < mysql_num_rows( $result ); $i++)
		{
			$row = mysql_fetch_row($result);
			$referers[$i]["section"] = $row[0];
			$referers[$i]["page"] = $row[1];
			$referers[$i]["url"] = $row[2];
			$referers[$i]["count"] = $row[3];
		}
	}
	return $referers;
}


/* Admin log */

/*
 * $kind:
 *  - addRant
 *  - editRant
 *  - addMark
 *  - editMark
 *  - login
 *  - logout
 * 
 * $id: ID of the rant or blogmark
 */
function addLogAction($skel, $kind, $id)
{
	$userId = -1;
	if (isset($_SESSION) && isset($_SESSION['userid']))
	{
		$userId = $_SESSION['userid'];
	}

	$ipaddr = getenv('REMOTE_ADDR');
	$time = date('Y-m-d G:i:s', time());

	//@TODO: create smplog_log_admin table and code to it all this info to it
}

?>
