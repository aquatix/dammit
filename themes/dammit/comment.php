
			<article>

				<header>
					<span class="commentnr"><?php echo $commentCounter; ?></span>
					Posted at
					<a href="<?php echo $skel['base_uri'] . 'p/' . $comment['rantId'] . '#comment' . $comment['id'];?>">
						<time datetime="<?php echo getLongDate($comment['date']); ?>" pubdate><?php echo $comment['date']; ?></time>
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

				<?php
					if ( isset($skel['isloggedin']) && true === $skel['isloggedin'] )
					{
						// delete button -> state = 0
						$commentsHTML = 'Notify ';
						if ($comments[$i]['wantnotifications'] == 0)
						{
							$commentsHTML .= 'off ';
						} else
						{
							$commentsHTML .= 'on ';
						}

						if ($comments[$i]['state'] == 0)
						{
							$commentsHTML .= '| <a href="root.php?action=enablecomment&amp;commentid=' . $comment['id'] . '&amp;rantid=' . $comment['rantId'] . '">Show comment in list</a> ';
						} else
						{
							$commentsHTML .= '| <a href="root.php?action=disablecomment&amp;commentid=' . $comment['id'] . '&amp;rantid=' . $comment['rantId'] . '">Hide comment from list</a> ';
						}
						echo '<footer>' . $commentsHTML . '</footer>';
					}
				?>

			</article>
