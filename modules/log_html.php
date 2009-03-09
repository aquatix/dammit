<?php
/**
 * Log module - HTML methods
 * $Id$
 * 
 * Copyright 2005-2009 mbscholt at aquariusoft.org
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


/*
 * Build table from log info. Uses mod_toolkit for table
 */
function buildLogtable($logitems)
{
	//
}


function buildReferers( $skel, $referers )
{
	$result = "<ul>\n";
	foreach ($referers as $referer)
	{
		$result .= "\t<li><a href=\"" . $referer['uri'] . "\">" . $referer['uri'] . "</a> (" . $referer['count'] . ")</li>\n";
	}
	$result .= "</ul>\n";
	return $result;
}


function buildAllReferers( $skel, $referers )
{
	$result = "<ul>\n";
	$prev_section_page = "";
	foreach ($referers as $referer)
	{
		if ($referer['section'] . '|' . $referer['page'] != $prev_section_page)
		{
			$prev_section_page = $referer['section'] . '|' . $referer['page'];
			$result .= "</ul>\n<h2>" . $referer['section'] . ' / ' . $referer['page'] . "</h2>\n<ul>\n";
		}
		$result .= "\t<li><a href=\"" . $referer['uri'] . "\">" . shortLine($referer['uri'], 70) . "</a> (" . $referer['count'] . ")</li>\n";
	}
	$result .= "</ul>\n";
	return $result;
}

?>
