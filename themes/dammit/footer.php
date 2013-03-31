	<footer>
		
		<p><small>&copy; <?php echo $skel['startyear'] . "-" . date('Y') . " <a href=\"" . $skel['base_uri'] . "p/about\">" . $skel['author'] . "</a> under a <a href=\"" . $skel['license_uri'] . "\"><acronym title=\"Creative Commons\">CC</acronym> License</a>";?> ]</small></p>

	</footer>

</div>

<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
		 chromium.org/developers/how-tos/chrome-frame-getting-started -->
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/js/jquery-1.9.1.min.js'>\x3C/script>")</script>

<!-- this is where we put our custom functions -->
<!-- don't forget to concatenate and minify if needed -->
<script src="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/js/functions.js"></script>

<?php if(isset($skel['googleAnalyticsCode']) && false === $skel['testing']) { ?>
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $skel['googleAnalyticsCode']; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php } ?>

<?php if(isset($skel['piwikURL']) && isset($skel['piwikSiteID']) && false === $skel['testing']) { ?>
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://<?php echo $skel['piwikURL']; ?>" : "http://<?php echo $skel['piwikURL']; ?>");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 2);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://<?php echo $skel['piwikURL']; ?>piwik.php?idsite=<?php echo $skel['piwikSiteID']; ?>" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
<?php } ?>

<!-- rendered in <?php echo (microtime() - $skel['starttime'])?> sec -->
  
</body>
</html>
