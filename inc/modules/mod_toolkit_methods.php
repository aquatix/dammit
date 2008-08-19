<?php
/*
 * $Id$
 *
 * Toolkit module - methods
 * Version: 0.5.02 2008-08-19
 *
 * Copyright 2003-2008 mbscholt at aquariusoft.org
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
 * Better integer-checker
 */
function myIsInt($x)
{
	return (is_numeric($x) ? intval($x) == $x : false);
}


/*
 * Formats the given date to the human readable format used to group the rants
 */
function getNormalDate($datetime)
{
	/* Make something like "Mon 2003-12-22" from "2003-12-22 20:00:00" */
	$dateAndTime = explode(" ", $datetime);	//split date and time
	$date = explode("-", $dateAndTime[0]);	//split up the date in year, month and day
	//	return date("D Y-m-d", mktime(0,0,0,$date[1],$date[2],$date[0]));	//"Mon 2003-12-22"
	//return date("l, d F, Y", mktime(0,0,0,$date[1],$date[2],$date[0]));	//"Monday, 22 December, 2003"
	return date("l d F Y", mktime(0,0,0,$date[1],$date[2],$date[0]));	//"Monday, 22 December, 2003"
	//$date = getDate( $datetime );//$dateAndTime[0]

}


/*
 * Extracts the date out of the given date/time string
 */
function getLongDate($datetime)
{
	/* Extract the date part from something like "2003-12-22 20:00:00" */
	$dateAndTime = explode(" ", $datetime);	//split date and time
	return $dateAndTime[0];
}


/*
 * Extracts the time out of the given date/time string
 */
function getTime($datetime)
{
	/* Make something like "20:00" from "2003-12-22 20:00:00" */
	$dateAndTime = explode(" ", $datetime);	//split date and time
	$time = explode(":", $dateAndTime[1]);	//split up the date in year, month and day
	return $time[0] . ":" . $time[1];
}

/*
 * Extracts the day out of the given date/time string
 */
function getDay($datetime)
{
	$dateAndTime = explode(" ", $datetime);	//split date and time
	$dateComponents = explode("-", $dateAndTime[0]);
	return $dateComponents[2];
}

/*
 * Extracts the month out of the given date/time string
 */
function getMonth($datetime)
{
	$dateAndTime = explode(" ", $datetime);	//split date and time
	$dateComponents = explode("-", $dateAndTime[0]);
	return $dateComponents[1];
}

/*
 * Extracts the year out of the given date/time string
 */
function getYear($datetime)
{
	$dateAndTime = explode(" ", $datetime);	//split date and time
	$dateComponents = explode("-", $dateAndTime[0]);
	return $dateComponents[0];
}


/*
 * Returns <monthname> <dayofmonth>, e.g. "December 1"
 */
function getMonthDate($datetime)
{
	return getMonthName(getMonth($datetime)) . " " . getDay($datetime);
}


function getMonthName($month)
{
	$theMonth = intval($month);
	if ($theMonth > 0 && $theMonth < 13)
	{
		if (1 == $theMonth)
		{
			return "January";
		} else if (2 == $theMonth)
		{
			return "February";
		} else if (3 == $theMonth)
		{
			return "March";
		} else if (4 == $theMonth)
		{
			return "April";
		} else if (5 == $theMonth)
		{
			return "May";
		} else if (6 == $theMonth)
		{
			return "June";
		} else if (7 == $theMonth)
		{
			return "July";
		} else if (8 == $theMonth)
		{
			return "August";
		} else if (9 == $theMonth)
		{
			return "September";
		} else if (10 == $theMonth)
		{
			return "October";
		} else if (11 == $theMonth)
		{
			return "November";
		} else if (12 == $theMonth)
		{
			return "December";
		}
	} else
	{
		return "-";
	}
}


function sendEmail($from, $from_name, $to, $subject, $body)
{
	$header = 'From: "' . $from_name . '" <' . $from . ">\r\n";
	mail($to, $subject, $body, $header);
	return 1;
}


/*
 * Shorten the url [or any text?] to $maxlength with the use of elipsis [...]
 */
function shortLine( $line, $maxlength )
{
	if (strlen($line) <= $maxlength)
	{
		/* Text is already short enough */
		return $line;
	} else
	{
		$half = ($maxlength / 2) - 1;
		//$return (substr($line, $half) . "..." . substr($line, strlen($line) - $half + 1), strlen($line));
		return (substr($line, 0, $half) . "..." . substr($line, 0 - ($half - 1), $half + 1));
	}
}

?>
