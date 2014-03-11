	<article>
		<header>
			<h1><a href="<?php echo $skel['base_uri'] . 'p/' . $thisRant['messageID'];?>"><?php echo $thisRant['title']; ?></a></h1>
<?php /*			<p><?php echo $thisRant['location']; ?> | <time datetime="<?php echo getDatetimeStamp($thisRant['date']); ?>"><?php echo $thisRant['date']; ?></time></p> */ ?>
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
            <ul>
                <li><span class="glyph">&#59170;</span> <?php echo $thisRant['username']; ?></li>
                <li><span class="glyph">&#9873;</span> <?php echo $thisRant['location']; ?></li>
                <li><span class="glyph">&#128340;</span> <time datetime="<?php echo getDatetimeStamp($thisRant['date']); ?>"><?php echo $thisRant['date']; ?></time></li>

		<?php if ($thisRant['modified'] > 0) {
            echo '<li><span class="glyph">&#9998;</span>';
			// Modified at least once
			if ($rants[$i]['modified'] == 1)
			{
				echo '1 time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
			} else
			{
				echo $rants[$i]['modified'] . ' times, last time at ' . getLongDate($rants[$i]['modifiedDate']) . " " . getTime($rants[$i]['modifiedDate']);
            }
            echo '</li>';
		} ?>
            </ul>
        </footer>
	</article>
