<?php
/**
 * Toolkit module - HTML methods
 * $Id$
 * 
 * Copyright 2003-2009 mbscholt at aquariusoft.org
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
 * General function for generating a table
 */
function generateTable($widths, $headers, $values)
{
	$theTable = "<table width=\"100%\">\n";
	$theTable = $theTable . "<tr>"; 

	$table_column_width = "";
	$rowcounter = 0;

	foreach ( $headers as $table_cell )
	{       
		if ($widths != null)
		{
			$table_column_width = " width=\"".$widths[$rowcounter]."\"";
		}

		$theTable = $theTable . "<th class=\"tdHeader\"".$table_column_width.">$table_cell</th>";
		$rowcounter++;
	}       

	$theTable = $theTable . "</tr>\n\t<tr>";

	$count = count( $headers );
	$rowcounter = 0;
	$table_odd = "1";

	foreach ( $values as $table_value )
	{
		if ($rowcounter == $count) 
		{
			$theTable = $theTable . "</tr>\n<tr>";
			$rowcounter = 0;
			if ($table_odd == "1") 
			{
				$table_odd = "0";
			} else
			{
				$table_odd = "1";
			}
		}
		if ($table_odd == "1") 
		{
			$table_class = "tdOdd";
		} else
		{
			$table_class = "tdEven";
		}

		$theTable = $theTable . "<td class=\"$table_class\" valign=\"top\">$table_value</td>";
		$rowcounter++;
	}
	$theTable = $theTable . ("</tr>\n</table>\n");
	return $theTable;
}


/* Take array and turn into html table */
function toTable($data)
{
//print_r($data);
	$keys = $data[0];
	$keys = array_flip($keys);
	$result = "<table>\n\t<tr>";
	foreach($keys as $key)
	{
		$result .= '<th>' . $key . '</th>';
	}
	$result .= "</tr>\n";
	$counter = 0;
	foreach($data as $item)
	{
		$counter++;
		if ($counter % 2 == 1)
		{
			$result .= "\t<tr>";
		} else
		{
			$result .= "\t<tr class=\"odd\">";
		}
		foreach($item as $value)
		{
			$result .= '<td>' . $value . '</td>';
		}
		$result .= "</tr>\n";
	}
	$result .= "</table>\n";
	return $result;
}


/* Take array and turn into html table */
function toPlainTable($data)
{
	$keys = $data[0];
	$keys = array_flip($keys);
	$result = '';
	foreach($keys as $key)
	{
		$result .= $key . ' ';
	}
	$result .= "\n";
	foreach($data as $item)
	{
		foreach($item as $value)
		{
			$result .= $value . ' ';
		}
		$result .= "\n";
	}
	return $result;
}


function plaintext2HTML($source)
{
	return str_replace("\n", "<br />\n", $source);
}
?>
