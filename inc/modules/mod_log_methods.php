<?php
	/*
	 * Log module - methods
	 * Version: 0.3.01 2005-02-13
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

		$query = 'INSERT INTO Log ' .
			'SET date="' . $time . '", ip="' . $ipaddr . '", client="' . getenv("HTTP_USER_AGENT") .
			'", referer="' . getenv("HTTP_REFERER") .
			'", section="' . $section_name . '", page="' . $page_name . '", pageversion="' .
		$page_version . '", userid="' . $user_id . '";';

		$result = mysql_query($query, $skel["dbLink"]);

		/* @TODO: error check and return -1 on error */

		/* All went well */
		return 1;
	}

	function getReferers( $skel, $section_name, $page_name )
	{
		$webloghref = "http://" . $skel["servername"] . $skel["baseHref"];
		//$query = 'SELECT Referer FROM Log WHERE Section="' . $section_name . '" AND Page="' . $page_name . '";';
		/*
		$query = 'SELECT DISTINCT Referer FROM Log ' .
			'WHERE Section="' . $section_name . '" AND Page="' . $page_name . '" ' .
			'AND Referer NOT LIKE "' . $webloghref . '%.php" ' .
			'AND Referer!="";';
		*/

		$query = 'SELECT referer, COUNT(*) FROM smplog_log ' .
			'WHERE section="' . $section_name . '" AND page="' . $page_name . '" ' .
			'AND referer NOT LIKE "' . $webloghref . '%.php" ' .
			'AND referer!="" GROUP BY referer;';

		$result = mysql_query($query, $skel["dbLink"]);
		$referers = array();

		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);

				//$referers[$section_name][$page_name][$i] = $row[0];
				//$referers[$section_name][$page_name][$i]["number"] = $row[1];
				$referers[$i]["uri"] = $row[0];
				$referers[$i]["count"] = $row[1];
			}
		}
		return $referers;
	}

	function getAllReferers( $skel )
	{
		//SELECT  Page, Referer, count(*) FROM Log WHERE Section="home" AND Page LIKE "posting.%" AND Referer!="" GROUP BY Referer;
		$webloghref = "http://" . $skel["servername"] . $skel["baseHref"];
/*
		$query = 'SELECT Section, Page, Referer, COUNT(*) FROM Log ' .
			'WHERE Referer NOT LIKE "' . $webloghref . '%" ' .
			'AND Referer!="" ' .
			'GROUP BY Referer ORDER BY Section, Page ASC;';
*/
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

				//$referers[$section_name][$page_name][$i] = $row[0];
				//$referers[$section_name][$page_name][$i]["number"] = $row[1];
				$referers[$i]["section"] = $row[0];
				$referers[$i]["page"] = $row[1];
				$referers[$i]["url"] = $row[2];
				$referers[$i]["count"] = $row[3];
			}
		}
		return $referers;
	}
?>
