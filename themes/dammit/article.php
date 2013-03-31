	<article>
		<header>
		<h1><a href="<?php echo $skel['base_uri'] . 'p/' . $thisRant['messageID'];?>"><?php echo $thisRant['title']; ?></a></h1>
		<p><time pubdate="pubdate"> </time></p>
		</header>
		
		<?php echo $rantsHTML; ?>


		<?php if ($thisRant['nrOfComments'] > 0 && isset($rantsComments)) { ?>
		<section>
			<h2>Comments</h2>
			<?php if (0 === $rants[$i]['commentsenabled']) { ?>
				<span class="strike" title="Commenting has been disabled for this post">
			<?php else {
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

		<footer></footer>
	</article>
