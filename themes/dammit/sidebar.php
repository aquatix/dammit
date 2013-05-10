	</div>
	<aside>
	
		<!--<h2>Sidebar Content</h2>-->
		
		<nav>
			<ol>
				<li><a href="<?php echo $skel['base_uri'];?>" accesskey="h" title="Home"><span class="glyph">&#8962;</span> home</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/archive" accesskey="a" title="View all post titles in the archive"><span class="glyph">&#128230;</span> archive</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>m" accesskey="m" title="View interesting links"><span class="glyph">&#128209;</span> blogmarks</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/books" accesskey="b" title="The list of books I've read or are going to consume"><span class="glyph">&#128213;</span> books</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/kudos" accesskey="k" title="View a list of sites that deserve kudos"><span class="glyph">&#128077;</span> kudos</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/about" accesskey="?" title="Information about the author"><span class="glyph">&#59170;</span> about</a></li>
			</ol>
		</nav>

		<div class="pageversion">v<?php echo $skel['page_version']; ?></div>
		
		<?php if (!isset($searchkey)) { $searchkey = ''; } ?>
		<?php /*<form style="margin-top: 1em;" action="<?php echo $skel['base_uri'];?>search" method="post"><div><input type="text" class="searchfield" name="searchkey" size="12" maxlength="250" value="<?php echo $searchkey;?>" /><input name="searchbtn" value="&#128269;" type="submit" class="searchbtn" /></div></form>*/ ?>
		<form style="margin-top: 1em;" action="<?php echo $skel['base_uri'];?>search" method="post"><div><input type="text" class="searchfield" name="searchkey" size="12" maxlength="250" value="<?php echo $searchkey;?>" /><input name="searchbtn" value="Find" type="submit" /></div></form>
		
		<h2>Distracted by</h2>
		<?php //echo buildSimpleMarks(getMarks($skel, 0, $skel['nrOfMarksInNav'])); ?>
		<?php echo buildMarksList(getMarks($skel, 0, $skel['nrOfMarksInNav'])); ?>

		<div class="content"><a href="<?php echo $skel['base_uri'];?>m">More blogmarks &raquo;</a></div>

		<div>
			<a href="<?php echo $skel['base_uri'];?>blog.rdf" title="Get my feed into your reader :)" class="glyph">&#59194;</a>
			<?php if(isset($skel['twitter_username'])) { ?>
			<a href="http://twitter.com/<?php echo $skel['twitter_username']; ?>" title="Me on Twitter" class="social">&#62217;</a>
			<?php } if(isset($skel['gplus_username'])) { ?>
			<a href="https://plus.google.com/<?php echo $skel['gplus_username']; ?>" title="Me on Google+" class="social">&#62223;</a>
			<?php } if(isset($skel['flickr_username'])) { ?>
			<a href="https://www.flickr.com/photos/<?php echo $skel['flickr_username']; ?>/" title="My pictures on Flickr" class="social">&#62211;</a>
			<?php } if(isset($skel['picasa_username'])) { ?>
			<a href="https://www.picasa<?php echo $skel['picasa_username']; ?>/" title="My pictures on Picasa" class="social">&#62277;</a>
			<?php } if(isset($skel['linkedin_username'])) { ?>
			<a href="http://linkedin.com/in/<?php echo $skel['linkedin_username']; ?>/" title="My profile on LinkedIn" class="social">&#62232;</a>
			<?php } ?>
		</div>

<?php /*
		<div class="images">
		<ul>
			<li><a href="<?php echo $skel['base_uri'];?>blog.rdf" title="Get my feed into your reader :)"><img src="/images/logos/rss_20.png" alt="RSS feed"/></a></li>
			<li><a href="<?php echo $skel['base_uri'];?>blog_comments.rdf" title="Get my feed with comments into your reader :)"><img src="/images/logos/rsscomments.gif" alt="RSS feed with comments"/></a></li>
			<li><a href="<?php echo $skel['base_uri'];?>marks.rdf" title="Get my blogmarks into your reader :)"><img src="/images/logos/rss_marks.png" alt="RSS feed - blogmarks"/></a></li>
		</ul>
*/ ?>
	</aside>
