<!doctype html>

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 

<head id="www-sitename-com" data-template-set="html5-reset">

	<meta charset="utf-8">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title><?php echo $page_name . ' | ' . $skel['siteName']; ?></title>
	
	<meta name="title" content="" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<!-- Google will often use this as its description of your page/site. Make it good. -->
	
	<meta name="google-site-verification" content="" />
	<!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
	
	<meta name="author" content="<?php echo $skel['author']; ?>" />
	<meta name="Copyright" content="Copyright <?php echo $skel['author'] . ' ' . $skel['startyear'] . "-" . date('Y'); ?> " />
	
	<!--  Mobile Viewport Fix
	j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
	-->
	<!-- Uncomment to use; use thoughtfully!
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	-->

	<link rel="shortcut icon" href="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/img/favicon.ico" />
	<!-- This is the traditional favicon.
		 - size: 16x16 or 32x32
		 - transparency is OK
		 - see wikipedia for info on browser support: http://mky.be/favicon/ -->
		 
	<link rel="apple-touch-icon" href="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/img/apple-touch-icon.png" />
	<!-- The is the icon for iOS's Web Clip.
		 - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
		 - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
		 - Transparency is not recommended (iOS will put a black BG behind the icon) -->
	
	<!-- concatenate and minify for production -->
	<link rel="stylesheet" href="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/css/reset.css" />
	<link rel="stylesheet" href="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/css/style.css" />
<!--	<link rel="stylesheet" href="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/css/style.css" />-->
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<script src="<?php echo $skel['base_uri'] . 'themes/' . $skel['theme']; ?>/js/modernizr-2.6.2.dev.js"></script>
	
	<!-- Application-specific meta tags -->
	<!-- Windows 8 -->
	<meta name="application-name" content="" /> 
	<meta name="msapplication-TileColor" content="" /> 
	<meta name="msapplication-TileImage" content="" />
	<!-- Twitter -->
	<meta name="twitter:card" content="">
	<meta name="twitter:site" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:url" content="">
	<!-- Facebook -->
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />

</head>

<body>

<div class="wrapper"><!-- not needed? up to you: http://camendesign.com/code/developpeurs_sans_frontieres -->

	<header>
		
		<h1><a href="/"><?php echo $skel['page_title']; ?></a></h1>
	
	</header>
<!--
	<header>
		
		<h1><a href="/">Page Title</a></h1>
		
		<nav>
		
			<ol>
				<li><a href="">Nav Link 1</a></li>
				<li><a href="">Nav Link 2</a></li>
				<li><a href="">Nav Link 3</a></li>
			</ol>
		
		</nav>
	
	</header>
-->