
			<article>

				<header>
					<span class="commentnr"><?php echo $commentCounter; ?></span>
					Posted at
					<a href="<?php echo $skel['base_uri'] . 'p/' . $comment['rantId'] . '#comment' . $comment['id'];?>">
						<time pubdate="pubdate"><?php echo $comment['date']; ?></time>
					</a>
					<?php
						$uri = $comment['name'];
						if ($comment['uri'] != '')
						{
							$uri = "<a href=\"" . $comment['uri'] . "\" rel=\"author\">" . $comment['name'] . "</a>";
						}
					?>

					 by <span class="author"><?php echo $uri; ?></span>

				</header>

				<p>
				<?php echo $comment['message']; ?>

				</p>

			</article>
