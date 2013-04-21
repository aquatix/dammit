	<article>
		<header>
			<h1><a href="<?php echo $skel['base_uri'] . 'p/' . $thisRant['messageID'];?>"><?php echo $thisRant['title']; ?></a></h1>
			<p><?php echo $thisRant['location']; ?> | <time pubdate="pubdate"><?php echo $thisRant['date']; ?></time></p>
		</header>
		
		<?php echo $rantsHTML; ?>


		<?php if ($thisRant['nrOfComments'] > 0 && isset($rantsComments)) { ?>

		<section>
			<h2>Comments</h2>
			<?php if (0 === $rants[$i]['commentsenabled']) { ?>
				<span class="strike" title="Commenting has been disabled for this post">
			<?php } else {
				$commentCounter = 1;
				foreach($rantsComments as $comment)
				{
					$comment['message'] = str_replace("\n", "<br />\n", $comment['message']);
					include 'comment.php';
					$commentCounter++;
				}
			} ?>
		</section>
		
		<?php /*
		$commentText = 'comments';
		if ($rants[$i]['nrOfComments'] == 1)
		{
			$commentText = 'comment';
		}
		if (0 == $rants[$i]['commentsenabled'])
		{
			$rantsHTML .= '<span class="strike" title="Commenting has been disabled for this post">';
		}
		$rantsHTML .= '<a href="' . $skel['base_uri'] . 'p/' . $rants[$i]['messageID'] . '#comments">' . $rants[$i]['nrOfComments'] . ' ' . $commentText . '&nbsp;&raquo;</a>';
		if (0 == $rants[$i]['commentsenabled'])
		{
			$rantsHTML .= '</span>';
		}*/ ?>
		
		<?php } ?>

		<footer>
			<?php if ($thisRant['modified'] > 0) {
				// Modified at least once
				if ($rants[$i]['modified'] == 1)
				{
					echo 'Modified 1 time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
				} else
				{
					echo 'Modified ' . $rants[$i]['modified'] . ' times, last time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
				}
			} ?>

		</footer>
	</article>
