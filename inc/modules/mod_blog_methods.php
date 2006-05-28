<?php
	/*
	 * Blog module - methods
	 * Version: 0.3.14 2005-09-21
	 */


	/*
	 * Tries to log in the user/pass combo
	 */
	function login( $skel, $user, $pass )
	{
		/* verify user/pass combo with db */
		//return -1;
		$query = 'SELECT User.Pass, User.Id FROM User WHERE User.Username="' . $user . '";';
		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			if ( md5( $pass ) == $row[0] )
			{
				/* password is valid */
				/*
				$_SESSION['username'] = $user;
				$_SESSION['userid'] = $row[1];
				return 1;
				*/

				/* Return userid */
				return $row[1];
			}
		}
		/* no user found or password not valid */
		return -1;
	}


	/*
	 * Check whether there is a valid session
	 */
	function isLoggedIn()
	{
	//Echo "woei|".$_SESSION['username']."|";
		return (isset($_SESSION['username']));
		//return session_is_registered('username');
	}


	/*
	 * Get the total number of rants in DB
	 */
	function getNrOfRants($skel)
	{
		$query = 'SELECT count(*) FROM Rant;';

		$result = mysql_query( $query, $skel["dbLink"] );
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
	 * Get nr of comments for a rant entry [only where State=1, e.g. non-deleted ones]
	 */
	function getNrOfComments( $skel, $rantId )
	{
		$query = 'SELECT count(*) FROM Comment WHERE Comment.RantId = ' . $rantId . ' AND Comment.State=1;';

		$result = mysql_query( $query, $skel["dbLink"] );
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
	 * Checks whether the rant belongs to the user
	 */
	function isRantMine( $skel, $rantId )
	{
		$query = "SELECT User FROM Rant WHERE Rant.MessageId=" . $rantId . ";";

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			return $row[0] == $_SESSION['userid'];
		}
	}


	/*
	 * Fetches rants from DB, sorted on date/time, newest first. Starting from number $first to $first+$number
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
	 * blogmark
	 */
	function getRants( $skel, $offset, $number )
	{
		/* Generate list with rants, newest first, starting with $first' item in DB, with a max of $number items */
		$rants = array();
		
		$query = 'SELECT MessageId, Date, User, Ip, Title, Message, Modified, ModifiedDate, Location, Blogmark FROM Rant ORDER BY Date DESC LIMIT ' . $offset . ', ' . $number . ';';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["user"] = $row[2];
				$rants[$i]["ip"] = $row[3];
				$rants[$i]["title"] = $row[4];
				$rants[$i]["message"] = $row[5];
				$rants[$i]["modified"] = $row[6];
				$rants[$i]["modifiedDate"] = $row[7];
				$rants[$i]["location"] = $row[8];
				$rants[$i]["blogmark"] = $row[9];
				$rants[$i]["nrOfComments"] = getNrOfComments( $skel, $rants[$i]["messageID"] );
			}
		}
		return $rants;
	}


	/*
	 * Returns rant $id
	 * Maybe: Returns array of $number rants, starting with rant $id
	 */
	function getRantByID( $skel, $id )
	{
		$rants = array();
		
		$query = 'SELECT MessageId, Date, User, Ip, Title, Message, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE MessageId = ' . $id . ';';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["user"] = $row[2];
				$rants[$i]["ip"] = $row[3];
				$rants[$i]["title"] = $row[4];
				$rants[$i]["message"] = $row[5];
				$rants[$i]["modified"] = $row[6];
				$rants[$i]["modifiedDate"] = $row[7];
				$rants[$i]["location"] = $row[8];
				$rants[$i]["blogmark"] = $row[9];
				$rants[$i]["nrOfComments"] = getNrOfComments( $skel, $rants[$i]["messageID"] );
			}
		}
		return $rants;
	}


	/*
	 * Get the post before and after the given post [identified by $rantDate]
	 */
	function getNextPrevRant($skel, $rantDate)
	{
		$rants = array();
		
		$query = 'SELECT MessageId, Date, Title FROM Rant WHERE Date > "' . $rantDate . '" ORDER BY Date ASC LIMIT 1;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			
			$rants["next"]["messageID"] = $row[0];
			$rants["next"]["date"] = $row[1];
			$rants["next"]["title"] = $row[2];
		}

		$query = 'SELECT MessageId, Date, Title FROM Rant WHERE Date < "' . $rantDate . '" ORDER BY Date DESC LIMIT 1;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			
			$rants["prev"]["messageID"] = $row[0];
			$rants["prev"]["date"] = $row[1];
			$rants["prev"]["title"] = $row[2];
		}
		return $rants;
	}


	/*
	 * Find entries which contain text $searchkey
	 */
	function findRants( $skel, $searchkey )
	{
		/* Generate list with rants, newest first, starting with $first' item in DB, with a max of $number items */
		$rants = array();
		
		$query = 'SELECT MessageId, Date, User, Ip, Title, Message, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE Message LIKE "%' . $searchkey . '%" OR Title LIKE "%' . $searchkey . '%" ORDER BY Date DESC;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["user"] = $row[2];
				$rants[$i]["ip"] = $row[3];
				$rants[$i]["title"] = $row[4];
				$rants[$i]["message"] = $row[5];
				$rants[$i]["modified"] = $row[6];
				$rants[$i]["modifiedDate"] = $row[7];
				$rants[$i]["location"] = $row[8];
				$rants[$i]["blogmark"] = $row[9];
				$rants[$i]["nrOfComments"] = getNrOfComments( $skel, $rants[$i]["messageID"] );
			}
		}
		return $rants;
	}


	/*
	 * Select all rants from $date_from to $date_to, with $date_xx in the form: yyyy-mm-dd [MySQL date]
	 * Don't add the message body.
	 */
	function getRantsInfo( $skel, $id )
	{
		/* Generate list with rants, newest first, starting with $first' item in DB, with a max of $number items */
		$rants = array();

		//$query = 'SELECT MessageId, Date, User, Ip, Title, Modified, ModifiedDate, Location, Blogmark FROM Rant ORDER BY Date DESC WHERE Date > "' . $date_from . '" AND Date < "' . $date_to . '";';
		$query = 'SELECT MessageId, Date, Title, Modified, ModifiedDate, Location FROM Rant WHERE MessageId = ' . $id . ';';
		//$query = 'SELECT MessageId, Date, User, Ip, Title, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE LEFT(Date, 4) = "' . "2004" . '" ORDER BY Date DESC;';

//	echo $query;

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["title"] = $row[2];
				$rants[$i]["modified"] = $row[3];
				$rants[$i]["modifiedDate"] = $row[4];
				$rants[$i]["location"] = $row[5];
			}
		}
		return $rants;
	}


	/*
	 * Return rants info from all rants from $year
	 */
	function getRantsFromYear( $skel, $year )
	{
		//return getRantsInfo( $skel, (($year - 1) . "-12-31"), (($year + 1) . "-01-01") );
		/* Generate list with rants, newest first, starting with $first' item in DB, with a max of $number items */
		$rants = array();

		//$query = 'SELECT MessageId, Date, User, Ip, Title, Modified, ModifiedDate, Location, Blogmark FROM Rant ORDER BY Date DESC WHERE Date > "' . $date_from . '" AND Date < "' . $date_to . '";';
		$query = 'SELECT MessageId, Date, User, Ip, Title, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE YEAR(Date) = "' . $year . '" ORDER BY Date DESC;';
		//$query = 'SELECT MessageId, Date, User, Ip, Title, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE LEFT(Date, 4) = "' . "2004" . '" ORDER BY Date DESC;';

	//echo $query;

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["user"] = $row[2];
				$rants[$i]["ip"] = $row[3];
				$rants[$i]["title"] = $row[4];
				$rants[$i]["message"] = "";
				$rants[$i]["modified"] = $row[5];
				$rants[$i]["modifiedDate"] = $row[6];
				$rants[$i]["location"] = $row[7];
				$rants[$i]["blogmark"] = $row[8];
				$rants[$i]["nrOfComments"] = -1;
			}
		}
		return $rants;
	}


	/*
	 * Returns a list with short information of all rants. Used for editing
	 */
	function getRantlist( $skel )
	{
		$rants = array();
		
		$query = 'SELECT MessageId, Date, User, Title, Modified, ModifiedDate FROM Rant;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$rants[$i]["messageID"] = $row[0];
				$rants[$i]["date"] = $row[1];
				$rants[$i]["user"] = $row[2];
				$rants[$i]["title"] = $row[3];
				$rants[$i]["modified"] = $row[4];
				$rants[$i]["modifiedDate"] = $row[5];
			}
		}
		return $rants;
	}


	/*
	 * Return years this blog is running
	 */
	function getRantYears( $skel )
	{
		$query = 'SELECT DISTINCT YEAR(Date) FROM Rant ORDER BY YEAR(Date) ASC;';

		$result = mysql_query( $query, $skel["dbLink"] );

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
	function newRant()
	{
		//$rant = new array();
		$rant["messageID"] = "-1";
		$rant["date"] = "";
		$rant["user"] = "";
		$rant["ip"] = "";
		$rant["title"] = "";
		$rant["message"] = "";
		$rant["modified"] = "";
		$rant["modifiedDate"] = "";
		$rant["location"] = "";
		$rant["blogmark"] = "";

		return $rant;
	}


	/*
	 * Add new rant to DB
	 */
	function addRant( $skel, $title, $location, $rant )
	{
		   // Stripslashes
		      if (get_magic_quotes_gpc()) {
		             $value = stripslashes($value);
			        }
		$title = escapeValue($title);
		$location = escapeValue($location);
		$rant = escapeValue($rant);
		
		$ipaddr = getenv("REMOTE_ADDR");
		$time = date("Y-m-d G:i:s", time());

		/* Whatever... */
		$user_id = $_SESSION['userid'];

		$query = 'INSERT INTO Rant ' .
			'SET Date="' . $time . '", User="' . $user_id . '", Ip="' . $ipaddr . '", Title="'. $title .'", Location="' . $location . '", Message="' . $rant .
			'", Modified=0, ModifiedDate="0000-00-00 00:00:00";';

		$result = mysql_query($query, $skel["dbLink"]);
	}


	/*
	 * Updates rant with id $rantid with new data
	 */
	function editRant( $skel, $title, $location, $rant, $rantid )
	{

		/* Get the number of times this rant's already modified */
		$timesModified = 0;
		$query = 'SELECT Modified '.
			'FROM Rant ' .
			'WHERE MessageId = ' . $rantid . ';';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_row($result);
			$timesModified = $row[0];
		}

		/* Try to secure the input */
		$title = escapeValue($title);
		$location = escapeValue($location);
		$rant = escapeValue($rant);
		
		$ipaddr = getenv("REMOTE_ADDR");
		$time = date("Y-m-d G:i:s", time());

		/* Whatever... */
		$user_id = $_SESSION['userid'];

		/* Now go and update it */
		$query = 'UPDATE Rant ' .
			'SET Title="'. $title .'", Location="' . $location . '", Message="' . $rant .
			'", Modified=' . ($timesModified + 1) . ', ModifiedDate=NOW() ' .
			'WHERE MessageId=' . $rantid . ';';

		$result = mysql_query($query, $skel["dbLink"]);
	}


	/*
	 * Fetches blogmarks from DB, sorted on date/time, newest first. Starting from number $first to $first+$number
	 * $marks:
	 * id
	 * date
	 * user
	 * ip
	 * title
	 * url
	 * location
	 * message
	 * modified
	 * modifiedDate
	 */
	function getMarks( $skel, $offset, $number )
	{
		/* Generate list with blogmarks, newest first, starting with $first' item in DB, with a max of $number items */
		$marks = array();
		
		$query = 'SELECT Id, Date, User, Ip, Title, Url, Location, Message, Modified, ModifiedDate FROM Blogmark ORDER BY Date DESC LIMIT ' . $offset . ', ' . $number . ';';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$marks[$i]["id"] = $row[0];
				$marks[$i]["date"] = $row[1];
				$marks[$i]["user"] = $row[2];
				$marks[$i]["ip"] = $row[3];
				$marks[$i]["title"] = $row[4];
				$marks[$i]["url"] = $row[5];
				$marks[$i]["location"] = $row[6];
				$marks[$i]["message"] = $row[7];
				$marks[$i]["modified"] = $row[8];
				$marks[$i]["modifiedDate"] = $row[9];
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
		
		$query = 'SELECT Id, Date, User, Ip, Title, Url, Location, Message, Modified, ModifiedDate FROM Blogmark WHERE Message LIKE "%' . $searchkey . '%" OR Title LIKE "%' . $searchkey . '%" ORDER BY Date DESC;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$marks[$i]["id"] = $row[0];
				$marks[$i]["date"] = $row[1];
				$marks[$i]["user"] = $row[2];
				$marks[$i]["ip"] = $row[3];
				$marks[$i]["title"] = $row[4];
				$marks[$i]["url"] = $row[5];
				$marks[$i]["location"] = $row[6];
				$marks[$i]["message"] = $row[7];
				$marks[$i]["modified"] = $row[8];
				$marks[$i]["modifiedDate"] = $row[9];
			}
		}
		return $marks;
	}


	/*
	 * $marksPerMonth:
	 * month of year [e.g. number]
	 * number of blogmarks for that month
	 */
	function getMarksPerMonth( $skel, $year )
	{
		$results = array();

		$query = "SELECT MONTH(Blogmark.Date), COUNT(*) FROM Blogmark WHERE YEAR(Blogmark.Date) = " . $year . " GROUP BY MONTH(Blogmark.Date) DESC;";

		// SELECT COUNT(Id) From Blogmark WHERE MONTH(Blogmark.Date)=11 AND YEAR(Blogmark.Date) = 2004;
		// SELECT DISTINCT MONTH(Blogmark.Date) AS M FROM Blogmark WHERE YEAR(Blogmark.Date) = 2004 ORDER BY MONTH(Blogmark.Date) ASC;
		
		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);

				$results[$i]["month"] = $row[0];
				$results[$i]["nrofpostings"] = $row[1];
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

		$query = "SELECT YEAR(Blogmark.Date), COUNT(*) FROM Blogmark GROUP BY YEAR(Blogmark.Date) DESC;";

		// SELECT COUNT(Id) From Blogmark WHERE MONTH(Blogmark.Date)=11 AND YEAR(Blogmark.Date) = 2004;
		// SELECT DISTINCT MONTH(Blogmark.Date) AS M FROM Blogmark WHERE YEAR(Blogmark.Date) = 2004 ORDER BY MONTH(Blogmark.Date) ASC;
		
		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);

				$results[$i]["year"] = $row[0];
				$results[$i]["nrofpostings"] = $row[1];
			}
		}
		return $results;
	}


	/* Return all months of year $year where blogmarks have been posted */
	function getMarkMonths( $skel, $year )
	{
		$results = array();

		$query = "SELECT DISTINCT MONTH(Blogmark.Date) FROM Blogmark WHERE YEAR(Blogmark.Date) = " . $year . " ORDER BY Blogmark.Date ASC;";

		// SELECT COUNT(Id) From Blogmark WHERE MONTH(Blogmark.Date)=11 AND YEAR(Blogmark.Date) = 2004;
		// SELECT DISTINCT MONTH(Blogmark.Date) AS M FROM Blogmark WHERE YEAR(Blogmark.Date) = 2004 ORDER BY MONTH(Blogmark.Date) ASC;
		
		$result = mysql_query( $query, $skel["dbLink"] );
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

	/* Get all blogmarks from the requested month
	 * $marks:
	 * id
	 * date
	 * user
	 * ip
	 * title
	 * url
	 * location
	 * message
	 * modified
	 * modifiedDate
	 */
	function getMarksByMonth( $skel, $year, $month )
	{
		/* Generate list with blogmarks, newest first, starting with $first' item in DB, with a max of $number items */
		$marks = array();
		
		$query = 'SELECT Id, Date, User, Ip, Title, Url, Location, Message, Modified, ModifiedDate FROM Blogmark ' .
			'WHERE YEAR(Blogmark.Date)=' . $year . ' AND MONTH(Blogmark.Date)=' . $month . ' ' .
			'ORDER BY Date DESC;';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$marks[$i]["id"] = $row[0];
				$marks[$i]["date"] = $row[1];
				$marks[$i]["user"] = $row[2];
				$marks[$i]["ip"] = $row[3];
				$marks[$i]["title"] = $row[4];
				$marks[$i]["url"] = $row[5];
				$marks[$i]["location"] = $row[6];
				$marks[$i]["message"] = $row[7];
				$marks[$i]["modified"] = $row[8];
				$marks[$i]["modifiedDate"] = $row[9];
			}
		}
		return $marks;
	}

	/*
	 * Returns blogmark $id
	 */
	function getMarkByID( $skel, $id )
	{
		$marks = array();
		
		//$query = 'SELECT MessageId, Date, User, Ip, Title, Message, Modified, ModifiedDate, Location, Blogmark FROM Rant ORDER BY Date DESC LIMIT ' . $number . ';';
		//$query = 'SELECT MessageId, Date, User, Ip, Title, Message, Modified, ModifiedDate, Location, Blogmark FROM Rant WHERE MessageId = ' . $id . ';';
		$query = 'SELECT Id, Date, User, Ip, Title, Url, Location, Message, Modified, ModifiedDate FROM Blogmark WHERE Id=' . $id . ';';

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);
				
				$marks[$i]["id"] = $row[0];
				$marks[$i]["date"] = $row[1];
				$marks[$i]["user"] = $row[2];
				$marks[$i]["ip"] = $row[3];
				$marks[$i]["title"] = $row[4];
				$marks[$i]["url"] = $row[5];
				$marks[$i]["location"] = $row[6];
				$marks[$i]["message"] = $row[7];
				$marks[$i]["modified"] = $row[8];
				$marks[$i]["modifiedDate"] = $row[9];
			}
		}
		return $marks;
	}


	function getNrOfMarksPerMonth( $skel, $year )
	{
		//
	}


	/*
	 * Add new mark to DB
	 */
	function addMark( $skel, $title, $url, $location, $description )
	{
		$title = escapeValue($title);
		$url = escapeValue($url);
		$location = escapeValue($location);
		$description = escapeValue($description);
		
		$ipaddr = getenv("REMOTE_ADDR");
		$time = date("Y-m-d G:i:s", time());

		/* Whatever... */
		$user_id = $_SESSION['userid'];

		$query = 'INSERT INTO Blogmark ' .
			'SET Date="' . $time . '", User="' . $user_id . '", Ip="' . $ipaddr . '", Title="'. $title .'", Url="' . $url . '", Location="' . $location . '", Message="' . $description .
			'", Modified=0, ModifiedDate="0000-00-00 00:00:00";';

		$result = mysql_query($query, $skel["dbLink"]);
	}


	/*
	 * Updates rant with id $rantid with new data
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

	/*
		$currentTime = now();

		$query = 'UPDATE Rant ' .
			'SET Date="' . $time . '", User="' . $user_id . '", Ip="' . $ipaddr . '", Title="'. $title .'", Location="' . $location . '", Message="' . $rant .
			'", Modified=0, ModifiedDate="' . $currentTime . '" ' .
			'WHERE MessageId=' . $rantid . ';';
	*/
		$query = 'UPDATE Rant ' .
			'SET Date="' . $time . '", User="' . $user_id . '", Ip="' . $ipaddr . '", Title="'. $title .'", Location="' . $location . '", Message="' . $rant .
			'", Modified=0, ModifiedDate=NOW() ' .
			'WHERE MessageId=' . $rantid . ';';

		$result = mysql_query($query, $skel["dbLink"]);
	}


	/*
	 * Get the total number of marks in DB
	 */
	function getNrOfMarks($skel)
	{
		$query = 'SELECT count(*) FROM Blogmark;';

		$result = mysql_query( $query, $skel["dbLink"] );
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
	 * Returns a list with short information of all blogmarks. Used for editing
	 */
	function marklist()
	{


	}


	function markUrl($mark)
	{
		return "blogmarks.php?year=" . getYear($mark["date"]) . "&amp;month=" . getMonth($mark["date"]) . "#url" . $mark["id"];
	}


	/*
	 * Fetches blogmarks from DB, sorted on date/time, newest first. Starting from number $first to $first+$number
	 * $rants:
	 * id
	 * date
	 * user
	 * ip


	/*
	 * Looks up mark $markId in DB
	 */
	/*
	function getMark( $dblink, $markId )
	{
		// look it up in db
		return buildMark( $markInfo );
	}
	*/


	/*
	 * Get nr of comments for a rant entry
	 */
	function getAllComments( $skel, $rantId, $wantallcomments )
	{
		$comments = array();

		/* Select all comments belonging to rant $rantId and sort them with the latest on top^W bottom */
		if (true == isLoggedIn() && $wantallcomments == true)
		{
			/* Get all comments */
			$query = 'SELECT Id, RantId, Date, Ip, Client, Name, Email, wantnotifications, Url, Message, State FROM Comment WHERE Comment.RantId = ' . $rantId . ' ORDER BY Date ASC;';
		} else
		{
			/* Only get enabled comments */
			$query = 'SELECT Id, RantId, Date, Ip, Client, Name, Email, wantnotifications, Url, Message, State FROM Comment WHERE Comment.RantId = ' . $rantId . ' AND Comment.State=1 ORDER BY Date ASC;';
		}

		$result = mysql_query( $query, $skel["dbLink"] );
		if ( mysql_num_rows( $result ) > 0 )
		{
			for ($i = 0; $i < mysql_num_rows( $result ); $i++)
			{
				$row = mysql_fetch_row($result);

				$comments[$i]["id"] = $row[0];
				$comments[$i]["rantId"] = $row[1];
				$comments[$i]["date"] = $row[2];
				$comments[$i]["ip"] = $row[3];
				$comments[$i]["client"] = $row[4];
				$comments[$i]["name"] = $row[5];
				$comments[$i]["email"] = $row[6];
				$comments[$i]["wantnotifications"] = $row[7];
				$comments[$i]["url"] = $row[8];
				$comments[$i]["message"] = $row[9];
				$comments[$i]["state"] = $row[10];
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
	url
	message
	 */
	function addComment($skel, $rantId, $name, $email, $wantnotifications, $url, $message)
	{
		$unescapedMessage = $message;

		$message = htmlentities($message);

		$name = escapeValue($name);
		$email = escapeValue($email);
		if ("http://" == trim($url))
		{
			$url = "";
		}
		$url = escapeValue($url);
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

		$query = 'INSERT INTO Comment ' .
			'SET RantId=' . $rantId . ', Date="' . $time . '", Ip="' . $ipaddr . '", Client="'. getenv("HTTP_USER_AGENT") .'", Name="' . $name . '", Email="' . $email . '", wantnotifications=' . $wantnotificationsInt . ', Url="' . $url . '", Message="' . $message .
			'", State=1;';

		$querysuccess = mysql_query($query, $skel["dbLink"]);

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
		//$body = "\nA comment has been added by " . $name . " [" . $email . "].\nPoster's url: " . $url . "\nPoster wants notifications: " . $wantnotificationsString . "\n\n== Posting ======\n" . $skel["baseHref"] . "index.php?rantid=" . $rantId . "\n" . $skel["baseHref"] . "index.php?rantid=" . $rantId . "&action=remove\n\n";
		//$body = "\nA comment has been added by " . $name . " [" . $email . "].\nPoster's url: " . $url . "\nPoster wants notifications: " . $wantnotificationsString . "\n\n== Posting ======\n" . $skel["baseHref"] . "index.php?rantid=" . $rantId . "\n\n";
		$body = "\nA comment has been added by " . $name . " [" . $email . "].\nTime of comment: " . $time . "\nPoster's url: " . $url . "\nPoster wants notifications: " . $wantnotificationsString . "\n\n== Posting ======\n" . $rantsInfo[0]["title"] . " [date: " . $rantsInfo[0]["date"] . "]\nhttp://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rantId . "\n\n";
		$body .= "== Comment ======\n" . $unescapedMessage . "\n";

		//$emailresult = sendEmail($skel, $skel["mailFrom"], $skel["mailFromName"], $skel["mailTo"], $skel["mailSubject"] . " about \"" . $rantsInfo[0]["title"] . "\" [date: " . $rantsInfo[0]["date"] . "]", $body);
		$emailresult = sendEmail($skel, $skel["mailFrom"], $skel["mailFromName"], $skel["mailTo"], $skel["mailSubject"] . " about \"" . $rantsInfo[0]["title"] . "\"", $body);

		if (false == $emailresult)
		{
			$result .= "Error while sending notification mail to web log owner\n";
		}

		/* Now send everybody that reacted on the post and wanted a notification an e-mail */
		$body = "\nA comment has been added by " . $name . " to posting\n" . $rantsInfo[0]["title"] . " [posted " . $rantsInfo[0]["date"] . "]\nhttp://" . $skel["servername"] . $skel["baseHref"] . "index.php?rantid=" . $rantId . "\nTime of comment: " . $time . "\n\n== Comment ======\n" . $unescapedMessage . "\n\n== End of comment ======\nIf you receive this message and don't know why you're getting it, please check the url provided, http://" . $skel["servername"] . $skel["baseHref"] . " or e-mail to " . $skel["mainEmail"];

		/* Get all e-mail addresses that had the checkbox checked */

		$wantnotificationList = getWantNotification($skel, $rantId);
		for ($i = 0; $i < count($wantnotificationList); $i++)
		{
			/* Don't send e-mail to the person commenting now, and not to an empty address */
			if ($email != $wantnotificationList[$i] && "" != $email)
			{
				//sendEmail($skel, $skel["mailFrom"], $skel["mailFromName"], $wantnotificationList[$i], $skel["mailNotificationSubject"] . " about \"" . $rantsInfo[0]["title"] . "\" [posted " . $rantsInfo[0]["date"] . "]", $body);
				sendEmail($skel, $skel["mailFrom"], $skel["mailFromName"], $wantnotificationList[$i], $skel["mailNotificationSubject"] . " about \"" . $rantsInfo[0]["title"] . "\"", $body);
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
		$query = "UPDATE Comment " .
			"SET State=0 WHERE Id=" . $commentid . ";";

		$querysuccess = mysql_query($query, $skel["dbLink"]);

		$result = "";
		if (false == $querysuccess)
		{
			//something went wrong with the query
			$result .= "Error while executing query\n";
		}
		return $result;
	}


	/*
	 * Enables ['recovers'] a comment so it's shown again
	 */
	function enableComment($skel, $commentid)
	{
		$query = "UPDATE Comment " .
			"SET State=1 WHERE Id=" . $commentid . ";";

		$querysuccess = mysql_query($query, $skel["dbLink"]);

		$result = "";
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

		/* Select all comments belonging to rant $rantId and sort them with the latest on top^W bottom */
		$query = 'SELECT DISTINCT Email FROM Comment WHERE Comment.RantId = ' . $rantId . ' AND Comment.wantnotifications=1 AND Comment.State=1;';

		$result = mysql_query( $query, $skel["dbLink"] );
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

?>
