<?php
/*
 * $Id$
 * Toolkit module - HTML methods
 * Version: 0.5.02 2008-03-17
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
