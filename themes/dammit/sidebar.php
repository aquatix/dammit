	</div>
	<aside>
	
		<!--<h2>Sidebar Content</h2>-->
		
		<nav>
			<ol>
				<li><a href="<?php echo $skel['base_uri'];?>" accesskey="h" title="Home">home</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/archive" accesskey="a" title="View all post titles in the archive">archive</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>m" accesskey="m" title="View interesting links">blogmarks</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/books" accesskey="b" title="View interesting links">books</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/kudos" accesskey="k" title="View a list of sites that deserve kudos">kudos</a></li>
				<li><a href="<?php echo $skel['base_uri'];?>p/about" accesskey="?" title="Information about the author">about</a></li>
			</ol>
		</nav>

		<div class="pageversion">v<?php echo $skel['page_version']; ?></div>
		
		<?php if (!isset($searchkey)) { $searchkey = ''; } ?>
		<form style="margin-top: 1em;" action="<?php echo $skel['base_uri'];?>search" method="post"><div><input type="text" class="searchfield" name="searchkey" size="12" maxlength="250" value="<?php echo $searchkey;?>" /><input name="searchbtn" value="Find" type="submit" /></div></form>
		
		<h2>distracted by</h2>
		<?php //echo buildSimpleMarks(getMarks($skel, 0, $skel["nrOfMarksInNav"])); ?>
		<?php echo buildMarksList(getMarks($skel, 0, $skel["nrOfMarksInNav"])); ?>

		<div class="images">
		<ul>
			<li><a href="<?php echo $skel['base_uri'];?>blog.rdf" title="Get my feed into your reader :)"><img src="/images/logos/rss_20.png" alt="RSS feed"/></a></li>
			<li><a href="<?php echo $skel['base_uri'];?>blog_comments.rdf" title="Get my feed with comments into your reader :)"><img src="/images/logos/rsscomments.gif" alt="RSS feed with comments"/></a></li>
			<li><a href="<?php echo $skel['base_uri'];?>marks.rdf" title="Get my blogmarks into your reader :)"><img src="/images/logos/rss_marks.png" alt="RSS feed - blogmarks"/></a></li>
		</ul>
	</aside>
