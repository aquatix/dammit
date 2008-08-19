<?php
/*
 * $Id$
 * 
 * Methods for Michiel's rantbox
 * Version: 0.5.01 2008-08-19
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


/*** Load modules ***/
include "modules/mod_toolkit_methods.php";
include "modules/mod_blog_methods.php";
include "modules/mod_log_methods.php";


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

?>
