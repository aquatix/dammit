<?php
/*
 * $Id$
 * 
 * Toolkit module - HTML methods
 * Version: 0.5.03 2008-08-19
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


function plaintext2HTML($source)
{
	return str_replace("\n", "<br />\n", $source);
}
?>
