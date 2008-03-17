<?php
/*
 * Log module - HTML methods
 * $Id$
 * Version: 0.5.01 2008-03-17
 * 2005-02-13
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
	//$referers[$section_name][$page_name][$i]["number"]
	$result = "<ul>\n";
	foreach ($referers as $referer)
	{
		$result .= "\t<li><a href=\"" . $referer['uri'] . "\">" . $referer['uri'] . "</a> (" . $referer['count'] . ")</li>\n";
		//result .= "<li>" . $referer . "</li>\n";
	}
	$result .= "</ul>\n";
	return $result;
}

function buildAllReferers( $skel, $referers )
{
	//$referers[$section_name][$page_name][$i]["number"]
	$result = "<ul>\n";
	$prev_section_page = "";
	foreach ($referers as $referer)
	{
		if ($referer['section'] . '|' . $referer['page'] != $prev_section_page)
		{
			$prev_section_page = $referer['section'] . '|' . $referer['page'];
			$result .= "</ul>\n<h2>" . $referer['section'] . ' / ' . $referer['page'] . "</h2>\n<ul>\n";
		}
		//$result .= "\t<li><a href=\"" . $referer['uri'] . "\">[" . $referer['section'] . "][" . $referer['page'] . "] " . shortLine($referer['uri'], 50) . "</a> (" . $referer['count'] . ")</li>\n";
		$result .= "\t<li><a href=\"" . $referer['uri'] . "\">" . shortLine($referer['uri'], 70) . "</a> (" . $referer['count'] . ")</li>\n";
		//result .= "<li>" . $referer . "</li>\n";
	}
	$result .= "</ul>\n";
	return $result;
}

?>
