<?php
	add_action('init', 'toby_remove_vc_meta', 100);
	function toby_remove_vc_meta() {
		if ( function_exists('visual_composer') )
			remove_action('wp_head', array(visual_composer(), 'addMetaData')); 
	}

	function toby_robots_metadata( $robots_meta ) {
			
		$string = "index,follow,NOODP,NOYDIR";
		
		if ( ! $robots_meta ) {
			$robots_meta = $string;
		} else {
			$metas = explode( ',', $robots_meta );

			foreach( $metas as $meta ) {
				if ( $meta == 'noindex' ) {
					$string = str_replace( 'index', $meta, $string );
				}
				if ( $meta == 'nofollow') {
					$string = str_replace( 'follow', $meta, $string );
				}
			}
			
			$robots_meta = $string;
		}
		
		return $robots_meta;
	}
	add_filter( 'wpseo_robots', 'toby_robots_metadata' );

	function toby_yoast_change_og_locale( $locale ) {
		return 'en_GB'; 
	}
	add_filter( 'wpseo_locale', 'toby_yoast_change_og_locale' );

	function toby_yoast_remove_comments_rss( $for_comments ) {
		return;
	}
	add_filter( 'post_comments_feed_link', 'toby_yoast_remove_comments_rss' );

	// ********************
	// Remove Query Strings from Revslider plugin
	// source: hhttp://wordpress.org/support/topic/some-strings-are-strong-
	// ********************

	function toby_remove_query_strings_rev( $src ){
		
		$rqs = explode( '?rev', $src );
		$rqs = explode( '?version', $rqs[0] );
		
		return $rqs[0];
	}

	add_filter( 'script_loader_src', 'toby_remove_query_strings_rev', 15, 100 );
	add_filter( 'style_loader_src', 'toby_remove_query_strings_rev', 15, 100 );

	remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
	remove_action('wp_head', 'wp_generator'); // remove wordpress version

	// remove_action('wp_head', 'feed_links', 2); // remove rss feed links (make sure you add them in yourself if youre using feedblitz or an rss service)
	// remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links

	remove_action('wp_head', 'index_rel_link'); // remove link to index page
	remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)

	remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	function remove_revslider_meta_tag() {
	  return '';
	}
	add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' );
	
	if ( class_exists( 'WPSEO_Frontend' ) ) {
		
		$yoast_instance = WPSEO_Frontend::get_instance();

		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
		remove_action( 'wp_head', array( $yoast_instance, 'head' ), 1 );

		add_action( 'wp_head', '_wp_render_title_tag', 0 );
		add_action( 'wp_head', array( $yoast_instance, 'head' ), 0 );

	}

	add_action( 'wp_head', 'toby_print_metadata', 0 );

	add_filter('autoptimize_filter_css_replacetag', 'toby_reorder_autop_html_injection', 10, 2 );
	function toby_reorder_autop_html_injection( $replaceTag, $content ) {
		$replaceTag = array( '<meta name="tobycreative" content="css" />', "before" );
		return $replaceTag;
	}

	function toby_print_metadata() {
	?>
		<meta name="revisit-after" content="7 days" />
		<meta name="rating" content="general" />
		<meta name="author" content="Toby Creative" />
		<meta name="copyright" content="A Mouse With A House © <?php echo date('Y'); ?>" />
		<meta name="classification" content="wooden toys, online toy store" />
		<meta name="distribution" content="Global" />

		<meta name="document-distribution" content="Global">
		<meta name="document-class" content="Completed" />
		<meta name="document-classification" content="wooden toys, online toy store" />
		<meta name="document-rights" content="Copyrighted Work" />
		<meta name="document-type" content="Public" />
		<meta name="document-rating" content="General" />
		<meta name="document-state" content="Dynamic" />		

		<meta name="geo.position" content="-31.92075, 115.7657013" />
		<meta name="geo.country" content="AU" />
		<meta name="geo.region" content="AU-WA" />
		<meta name="geo.placename" content="Perth" />

		<meta name="ICBM" content="-31.92075, 115.7657013" />

		<link rel="home" title="Wooden toys and so much more | Give great childhood memories" href="/" />
		<link rel="contents" title="Table of Contents" href="/sitemap/" />
		<link rel="profile" href="//gmpg.org/xfn/11" />
		<link rel="next" title="About Us" href="/about-us/" />

		<link rel="P3Pv1" href="/w3c/p3p.xml" />

		<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="194x194" href="/favicon-194x194.png">
		<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#d86661">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/mstile-144x144.png">
		<meta name="theme-color" content="#ffffff">

		<meta property="og:image" content="https://amousewithahouse.com.au/wp-content/uploads/2016/01/Logo-01-1.png" />

		<meta name="DC.title" content="Wooden toys and so much more | Give great childhood memories" />
		<meta name="dcterms.contributor" content="AMouseWithAHouse" />
		<meta name="dcterms.coverage" content="Australia" />
		<meta name="dcterms.creator" content="Toby Creative" />
		<meta name="dcterms.format" content="text/html" />
		<meta name="dcterms.identifier" content="https://tobycreative.com.au/" />
		<meta name="dcterms.publisher" content="AMouseWithAHouse" />
		<meta name="dcterms.subject" content="Family owned business. We are passionate about providing high quality wooden toys and more for kids at an affordable price." />
		<meta name="dcterms.title" content="Wooden toys and so much more | Give great childhood memories" />
		<meta name="dcterms.abstract" content="Family owned business. We are passionate about providing high quality wooden toys and more for kids at an affordable price.">
		<meta name="dcterms.description" content="Family owned business. We are passionate about providing high quality wooden toys and more for kids at an affordable price." />
		<meta name="dcterms.type" content="Text" />

		<meta name="tobycreative" content="css" />
		
		<script type='application/ld+json'> 
		{
			"@graph": [
				{
					
				  "@context": "http://www.schema.org",
				  "@type": "LocalBusiness",
				  "name": "A Mouse with A House | Wooden toys and so much more",
				  "url": "https://amousewithahouse.com.au/",
				  "logo": "https://amousewithahouse.com.au/wp-content/uploads/2016/01/logo-white-bg.jpg",
				  "image": "https://amousewithahouse.com.au/wp-content/uploads/2016/01/logo-white-bg.jpg",
				  "description": "Family owned business. We are passionate about providing high quality wooden toys and more for kids at an affordable price.",
				  "address": {
					"@type": "PostalAddress",
					"streetAddress": "5 B Langdale St",
					"addressLocality": "Wembley Downs",
					"addressRegion": "WA",
					"postalCode": "6019",
					"addressCountry": "Australia"
				  },
				  "geo": {
					"@type": "GeoCoordinates",
					"latitude": "-31.92075",
					"longitude": "115.7657013"
				  },
				  "openingHours": "Mo-Su 09:00-17:00",
				  "priceRange": "$",
				  "telephone": "0424 833 164"
				},
				{
				  "@context" : "http://schema.org",
				  "@type" : "Organization",
				  "name" : "A Mouse with A House | Wooden toys and so much more",
				  "url" : "https://amousewithahouse.com.au/",
				  "sameAs" : [
					"https://www.facebook.com/Amousewithahouse-1872643472949860/",
					"https://twitter.com/Mousewithahouse",
					"https://www.pinterest.com/amousewithahouseaus/",
					"https://www.instagram.com/amousewithahouse/",
					"https://foursquare.com/v/amouse-withahouse/599595de0f013c5c3960b9a1"
				   ],
				  "address": {
					"@type": "PostalAddress",
					"streetAddress": "5 B Langdale St",
					"addressRegion": "Wembley Downs",
					"postalCode": "6019",
					"addressCountry": "AU"
				  }
				}
			]
		}
		</script>
	<?php
		/*
		*  Print WP Admin bar CSS as part of the Critical CSS.
		*  Fixes the issue of the theme's sticky header when admin bar is present on page
		*/
		if ( is_admin_bar_showing() ) {
			?>
				<style>#wpadminbar,#wpadminbar *{font-size:13px;font-weight:400;line-height:32px}#wpadminbar *{height:auto;width:auto;margin:0;padding:0;position:static;text-shadow:none;text-transform:none;letter-spacing:normal;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;border-radius:0;box-sizing:content-box;transition:none;-webkit-font-smoothing:subpixel-antialiased;-moz-osx-font-smoothing:auto}.rtl #wpadminbar *{font-family:Tahoma,sans-serif}html:lang(he-il) .rtl #wpadminbar *{font-family:Arial,sans-serif}#wpadminbar .ab-empty-item{cursor:default;outline:0}#wpadminbar .ab-empty-item,#wpadminbar a.ab-item,#wpadminbar>#wp-toolbar span.ab-label,#wpadminbar>#wp-toolbar span.noticon{color:#eee}#wpadminbar #wp-admin-bar-my-sites a.ab-item,#wpadminbar #wp-admin-bar-site-name a.ab-item{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}#wpadminbar ul li:after,#wpadminbar ul li:before{content:normal}#wpadminbar a,#wpadminbar a img,#wpadminbar a img:hover,#wpadminbar a:hover{outline:0;border:none;text-decoration:none;background:0 0}#wpadminbar a:active,#wpadminbar a:focus,#wpadminbar div,#wpadminbar input[type=text],#wpadminbar input[type=password],#wpadminbar input[type=number],#wpadminbar input[type=search],#wpadminbar input[type=email],#wpadminbar input[type=url],#wpadminbar select,#wpadminbar textarea{box-shadow:none;outline:0}#wpadminbar{direction:ltr;color:#ccc;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;height:32px;position:fixed;top:0;left:0;width:100%;min-width:600px;z-index:99999;background:#23282d}#wpadminbar .ab-sub-wrapper,#wpadminbar ul,#wpadminbar ul li{background:0 0;clear:none;list-style:none;margin:0;padding:0;position:relative;text-indent:0;z-index:99999}#wpadminbar ul#wp-admin-bar-root-default>li{margin-right:0}#wpadminbar .quicklinks ul{text-align:left}#wpadminbar li{float:left}#wpadminbar .quicklinks .ab-top-secondary>li{float:right}#wpadminbar .quicklinks .ab-empty-item,#wpadminbar .quicklinks a,#wpadminbar .shortlink-input{height:32px;display:block;padding:0 10px;margin:0}#wpadminbar .quicklinks>ul>li>a{padding:0 8px 0 7px}#wpadminbar .menupop .ab-sub-wrapper,#wpadminbar .shortlink-input{margin:0;padding:0;box-shadow:0 3px 5px rgba(0,0,0,.2);background:#32373c;display:none;position:absolute;float:none}#wpadminbar .selected .shortlink-input,#wpadminbar li.hover>.ab-sub-wrapper,#wpadminbar.nojs li:hover>.ab-sub-wrapper{display:block}#wpadminbar.ie7 .menupop .ab-sub-wrapper,#wpadminbar.ie7 .shortlink-input{top:32px;left:0}#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper{min-width:100%}#wpadminbar .ab-top-secondary .menupop .ab-sub-wrapper{right:0;left:auto}#wpadminbar .ab-submenu{padding:6px 0}#wpadminbar .quicklinks .menupop ul li{float:none}#wpadminbar .quicklinks .menupop ul li a strong{font-weight:600}#wpadminbar .quicklinks .menupop ul li .ab-item,#wpadminbar .quicklinks .menupop ul li a strong,#wpadminbar .quicklinks .menupop.hover ul li .ab-item,#wpadminbar .shortlink-input,#wpadminbar.nojs .quicklinks .menupop:hover ul li .ab-item{line-height:26px;height:26px;white-space:nowrap;min-width:140px}#wpadminbar .shortlink-input{width:200px}#wpadminbar .menupop li.hover>.ab-sub-wrapper,#wpadminbar .menupop li:hover>.ab-sub-wrapper{margin-left:100%;margin-top:-32px}#wpadminbar .ab-top-secondary .menupop li.hover>.ab-sub-wrapper,#wpadminbar .ab-top-secondary .menupop li:hover>.ab-sub-wrapper{margin-left:0;left:inherit;right:100%}#wpadminbar .ab-top-menu>li.hover>.ab-item,#wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus,#wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item,#wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus{background:#32373c;color:#00b9eb}#wpadminbar:not(.mobile)>#wp-toolbar a:focus span.ab-label,#wpadminbar:not(.mobile)>#wp-toolbar li:hover span.ab-label,#wpadminbar>#wp-toolbar li.hover span.ab-label{color:#00b9eb}#wpadminbar .ab-icon,#wpadminbar .ab-item:before,#wpadminbar>#wp-toolbar>#wp-admin-bar-root-default .ab-icon{position:relative;float:left;font:400 20px/1 dashicons;speak:none;padding:4px 0;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background-image:none!important;margin-right:6px}#wpadminbar #adminbarsearch:before,#wpadminbar .ab-icon:before,#wpadminbar .ab-item:before{color:#a0a5aa;color:rgba(240,245,250,.6);position:relative;transition:all .1s ease-in-out}#wpadminbar .ab-label{display:inline-block;height:32px}#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop ul li a strong,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a{color:#b4b9be;color:rgba(240,245,250,.7)}#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a,#wpadminbar .quicklinks .menupop ul li a:focus,#wpadminbar .quicklinks .menupop ul li a:focus strong,#wpadminbar .quicklinks .menupop ul li a:hover,#wpadminbar .quicklinks .menupop ul li a:hover strong,#wpadminbar .quicklinks .menupop.hover ul li a:focus,#wpadminbar .quicklinks .menupop.hover ul li a:hover,#wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:focus,#wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:hover,#wpadminbar li #adminbarsearch.adminbar-focused:before,#wpadminbar li .ab-item:focus .ab-icon:before,#wpadminbar li .ab-item:focus:before,#wpadminbar li a:focus .ab-icon:before,#wpadminbar li.hover .ab-icon:before,#wpadminbar li.hover .ab-item:before,#wpadminbar li:hover #adminbarsearch:before,#wpadminbar li:hover .ab-icon:before,#wpadminbar li:hover .ab-item:before,#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover{color:#00b9eb}#wpadminbar.mobile .quicklinks .ab-icon:before,#wpadminbar.mobile .quicklinks .ab-item:before{color:#b4b9be}#wpadminbar.mobile .quicklinks .hover .ab-icon:before,#wpadminbar.mobile .quicklinks .hover .ab-item:before{color:#00b9eb}#wpadminbar .ab-top-secondary .menupop .menupop>.ab-item:before,#wpadminbar .menupop .menupop>.ab-item:before{position:absolute;font:400 17px/1 dashicons;speak:none;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}#wpadminbar .menupop .menupop>.ab-item{display:block;padding-right:2em}#wpadminbar .menupop .menupop>.ab-item:before{top:1px;right:4px;content:"\f139";color:inherit}#wpadminbar .ab-top-secondary .menupop .menupop>.ab-item{padding-left:2em;padding-right:1em}#wpadminbar .ab-top-secondary .menupop .menupop>.ab-item:before{top:1px;left:6px;content:"\f141"}#wpadminbar .quicklinks .menupop ul.ab-sub-secondary{display:block;position:relative;right:auto;margin:0;box-shadow:none}#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu{background:#464b50}#wpadminbar .quicklinks .menupop .ab-sub-secondary>li .ab-item:focus a,#wpadminbar .quicklinks .menupop .ab-sub-secondary>li>a:hover{color:#00b9eb}#wpadminbar .quicklinks a span#ab-updates{background:#eee;color:#32373c;display:inline;padding:2px 5px;font-size:10px;font-weight:600;border-radius:10px}#wpadminbar .quicklinks a:hover span#ab-updates{background:#fff;color:#000}#wpadminbar .ab-top-secondary{float:right}#wpadminbar ul li:last-child,#wpadminbar ul li:last-child .ab-item{box-shadow:none}#wp-admin-bar-my-account>ul{min-width:198px}#wp-admin-bar-my-account>.ab-item:before{content:"\f110";top:2px;float:right;margin-left:6px;margin-right:0}#wp-admin-bar-my-account.with-avatar>.ab-item:before{display:none;content:none}#wp-admin-bar-my-account.with-avatar>ul{min-width:270px}#wpadminbar.ie8 #wp-admin-bar-my-account.with-avatar .ab-item{white-space:nowrap}#wpadminbar #wp-admin-bar-user-actions>li{margin-left:16px;margin-right:16px}#wpadminbar #wp-admin-bar-user-actions.ab-submenu{padding:6px 0 12px}#wpadminbar #wp-admin-bar-my-account.with-avatar #wp-admin-bar-user-actions>li{margin-left:88px}#wpadminbar #wp-admin-bar-user-info{margin-top:6px;margin-bottom:15px;height:auto;background:0 0}#wp-admin-bar-user-info .avatar{position:absolute;left:-72px;top:4px;width:64px;height:64px}#wpadminbar #wp-admin-bar-user-info a{background:0 0;height:auto}#wpadminbar #wp-admin-bar-user-info span{background:0 0;padding:0;height:18px}#wpadminbar #wp-admin-bar-user-info .display-name,#wpadminbar #wp-admin-bar-user-info .username{display:block}#wpadminbar #wp-admin-bar-user-info .username{color:#a0a5aa;font-size:11px}#wpadminbar #wp-admin-bar-my-account.with-avatar>.ab-empty-item img,#wpadminbar #wp-admin-bar-my-account.with-avatar>a img{width:auto;height:16px;padding:0;border:1px solid #82878c;background:#eee;line-height:24px;vertical-align:middle;margin:-4px 0 0 6px;float:none;display:inline}#wpadminbar.ie8 #wp-admin-bar-my-account.with-avatar>.ab-empty-item img,#wpadminbar.ie8 #wp-admin-bar-my-account.with-avatar>a img{width:auto}#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon{width:15px;height:20px;margin-right:0;padding:6px 0 5px}#wpadminbar #wp-admin-bar-wp-logo>.ab-item{padding:0 7px}#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before{content:"\f120";top:2px}#wpadminbar .quicklinks li .blavatar{float:left;font:400 16px/1 dashicons!important;speak:none;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;color:#eee}#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a .blavatar,#wpadminbar .quicklinks li a:focus .blavatar,#wpadminbar .quicklinks li a:hover .blavatar{color:#00b9eb}#wpadminbar .quicklinks li .blavatar:before{content:"\f120";height:16px;width:16px;display:inline-block;margin:6px 8px 0 -2px}#wpadminbar #wp-admin-bar-appearance{margin-top:-12px}#wpadminbar #wp-admin-bar-my-sites>.ab-item:before,#wpadminbar #wp-admin-bar-site-name>.ab-item:before{content:"\f541";top:2px}#wpadminbar #wp-admin-bar-customize>.ab-item:before{content:"\f540";top:2px}#wpadminbar #wp-admin-bar-edit>.ab-item:before{content:"\f464";top:2px}#wpadminbar #wp-admin-bar-site-name>.ab-item:before{content:"\f226"}.wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{content:"\f102"}#wpadminbar #wp-admin-bar-comments .ab-icon{margin-right:6px}#wpadminbar #wp-admin-bar-comments .ab-icon:before{content:"\f101";top:3px}#wpadminbar #wp-admin-bar-comments .count-0{opacity:.5}#wpadminbar #wp-admin-bar-new-content .ab-icon:before{content:"\f132";top:4px}#wpadminbar #wp-admin-bar-updates .ab-icon:before{content:"\f463";top:2px}#wpadminbar.ie8 #wp-admin-bar-search{display:block;min-width:32px}#wpadminbar #wp-admin-bar-search .ab-item{padding:0;background:0 0}#wpadminbar #adminbarsearch{position:relative;height:32px;padding:0 2px;z-index:1}#wpadminbar #adminbarsearch:before{position:absolute;top:6px;left:5px;z-index:20;font:400 20px/1 dashicons!important;content:"\f179";speak:none;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}#wpadminbar>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input{display:inline-block;float:none;position:relative;z-index:30;font-size:13px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;line-height:24px;text-indent:0;height:24px;width:24px;max-width:none;padding:0 3px 0 24px;margin:0;color:#ccc;background-color:rgba(255,255,255,0);border:none;outline:0;cursor:pointer;box-shadow:none;box-sizing:border-box;transition-duration:.4s;transition-property:width,background;transition-timing-function:ease}#wpadminbar>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input:focus{z-index:10;color:#000;width:200px;background-color:rgba(255,255,255,.9);cursor:text;border:0}#wpadminbar.ie7>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input{margin-top:3px;width:120px}#wpadminbar.ie8>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input{background:url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRâ€Œâ€‹AA7)}#wpadminbar.ie8 #adminbarsearch.adminbar-focused:before{content:"\f179 "}#wpadminbar.ie8>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input:focus{background:#fff;z-index:-1}#wpadminbar #adminbarsearch .adminbar-button,.customize-support #wpadminbar .hide-if-customize,.customize-support .hide-if-customize,.customize-support .wp-core-ui .hide-if-customize,.customize-support.wp-core-ui .hide-if-customize,.no-customize-support #wpadminbar .hide-if-no-customize,.no-customize-support .hide-if-no-customize,.no-customize-support .wp-core-ui .hide-if-no-customize,.no-customize-support.wp-core-ui .hide-if-no-customize{display:none}#wpadminbar .screen-reader-text,#wpadminbar .screen-reader-text span{border:0;clip:rect(1px,1px,1px,1px);-webkit-clip-path:inset(50%);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px;word-wrap:normal!important}#wpadminbar .screen-reader-shortcut{position:absolute;top:-1000em}#wpadminbar .screen-reader-shortcut:focus{left:6px;top:7px;height:auto;width:auto;display:block;font-size:14px;font-weight:600;padding:15px 23px 14px;background:#f1f1f1;color:#0073aa;z-index:100000;line-height:normal;text-decoration:none;box-shadow:0 0 2px 2px rgba(0,0,0,.6)}* html #wpadminbar{overflow:hidden;position:absolute}* html #wpadminbar .quicklinks ul li a{float:left}* html #wpadminbar .menupop a span{background-image:none}.no-font-face #wpadminbar ul.ab-top-menu>li>a.ab-item{display:block;width:45px;text-align:center;overflow:hidden;margin:0 3px}.no-font-face #wpadminbar #wp-admin-bar-edit>.ab-item,.no-font-face #wpadminbar #wp-admin-bar-my-sites>.ab-item,.no-font-face #wpadminbar #wp-admin-bar-site-name>.ab-item{text-indent:0}.no-font-face #wpadminbar #wp-admin-bar-wp-logo>.ab-item,.no-font-face #wpadminbar .ab-icon,.no-font-face #wpadminbar .ab-icon:before,.no-font-face #wpadminbar a.ab-item:before{display:none!important}.no-font-face #wpadminbar ul.ab-top-menu>li>a>span.ab-label{display:inline}.no-font-face #wpadminbar #wp-admin-bar-menu-toggle span.ab-icon{display:inline!important}.no-font-face #wpadminbar #wp-admin-bar-menu-toggle span.ab-icon:before{content:"Menu";font:14px/45px sans-serif!important;display:inline-block!important;color:#fff}.no-font-face #wpadminbar #wp-admin-bar-site-name a.ab-item{color:#fff}@media screen and (max-width:782px){#wpadminbar ul#wp-admin-bar-root-default>li,.network-admin #wpadminbar ul#wp-admin-bar-top-secondary>li#wp-admin-bar-my-account{margin-right:0}html #wpadminbar{height:46px;min-width:300px}#wpadminbar *{font-size:14px;font-weight:400;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;line-height:32px}#wpadminbar .quicklinks .ab-empty-item,#wpadminbar .quicklinks>ul>li>a{padding:0;height:46px;line-height:46px;width:auto}#wpadminbar .ab-icon{font:40px/1 dashicons!important;margin:0;padding:0;width:52px;height:46px;text-align:center}#wpadminbar .ab-icon:before{text-align:center}#wpadminbar .ab-submenu{padding:0}#wpadminbar #wp-admin-bar-my-account a.ab-item,#wpadminbar #wp-admin-bar-my-sites a.ab-item,#wpadminbar #wp-admin-bar-site-name a.ab-item{text-overflow:clip}#wpadminbar .ab-label{display:none}#wpadminbar .menupop li.hover>.ab-sub-wrapper,#wpadminbar .menupop li:hover>.ab-sub-wrapper{margin-top:-46px}#wpadminbar #wp-admin-bar-comments .ab-icon,#wpadminbar #wp-admin-bar-my-account.with-avatar #wp-admin-bar-user-actions>li{margin:0}#wpadminbar .ab-top-menu .menupop .ab-sub-wrapper .menupop>.ab-item{padding-right:30px}#wpadminbar .menupop .menupop>.ab-item:before{top:10px;right:6px}#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper .ab-item{font-size:16px;padding:8px 16px}#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper a:empty{display:none}#wpadminbar #wp-admin-bar-wp-logo>.ab-item{padding:0}#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon{padding:0;width:52px;height:46px;text-align:center;vertical-align:top}#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before{font:28px/1 dashicons!important;top:-3px}#wpadminbar .ab-icon,#wpadminbar .ab-item:before{padding:0}#wpadminbar #wp-admin-bar-customize>.ab-item,#wpadminbar #wp-admin-bar-edit>.ab-item,#wpadminbar #wp-admin-bar-my-account>.ab-item,#wpadminbar #wp-admin-bar-my-sites>.ab-item,#wpadminbar #wp-admin-bar-site-name>.ab-item{text-indent:100%;white-space:nowrap;overflow:hidden;width:52px;padding:0;color:#a0a5aa;position:relative}#wpadminbar .ab-icon,#wpadminbar .ab-item:before,#wpadminbar>#wp-toolbar>#wp-admin-bar-root-default .ab-icon{padding:0;margin-right:0}#wpadminbar #wp-admin-bar-customize>.ab-item:before,#wpadminbar #wp-admin-bar-edit>.ab-item:before,#wpadminbar #wp-admin-bar-my-account>.ab-item:before,#wpadminbar #wp-admin-bar-my-sites>.ab-item:before,#wpadminbar #wp-admin-bar-site-name>.ab-item:before{display:block;text-indent:0;font:400 32px/1 dashicons;speak:none;top:7px;width:52px;text-align:center;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}#wpadminbar #wp-admin-bar-appearance{margin-top:0}#wpadminbar .quicklinks li .blavatar:before{display:none}#wpadminbar #wp-admin-bar-search{display:none}#wpadminbar #wp-admin-bar-new-content .ab-icon:before{top:0;line-height:53px;height:46px!important;text-align:center;width:52px;display:block}#wpadminbar #wp-admin-bar-updates{text-align:center}#wpadminbar #wp-admin-bar-updates .ab-icon:before{top:3px}#wpadminbar #wp-admin-bar-comments .ab-icon:before{display:block;font-size:34px;height:46px;line-height:47px;top:0}#wp-toolbar>ul>li,#wpadminbar #wp-admin-bar-user-actions.ab-submenu img.avatar{display:none}#wpadminbar #wp-admin-bar-my-account>a{position:relative;white-space:nowrap;text-indent:150%;width:28px;padding:0 10px;overflow:hidden}#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar>a img{position:absolute;top:13px;right:10px;width:26px;height:26px}#wpadminbar #wp-admin-bar-user-actions.ab-submenu{padding:0}#wpadminbar #wp-admin-bar-user-info .display-name{height:auto;font-size:16px;line-height:24px;color:#eee}#wpadminbar #wp-admin-bar-user-info a{padding-top:4px}#wpadminbar #wp-admin-bar-user-info .username{line-height:.8!important;margin-bottom:-2px}#wpadminbar li#wp-admin-bar-comments,#wpadminbar li#wp-admin-bar-customize,#wpadminbar li#wp-admin-bar-edit,#wpadminbar li#wp-admin-bar-menu-toggle,#wpadminbar li#wp-admin-bar-my-account,#wpadminbar li#wp-admin-bar-my-sites,#wpadminbar li#wp-admin-bar-new-content,#wpadminbar li#wp-admin-bar-site-name,#wpadminbar li#wp-admin-bar-updates,#wpadminbar li#wp-admin-bar-wp-logo{display:block}#wpadminbar li.hover ul li,#wpadminbar li:hover ul li,#wpadminbar li:hover ul li:hover ul li{display:list-item}#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper{min-width:-webkit-fit-content;min-width:-moz-fit-content;min-width:fit-content}#wpadminbar #wp-admin-bar-comments,#wpadminbar #wp-admin-bar-edit,#wpadminbar #wp-admin-bar-my-account,#wpadminbar #wp-admin-bar-my-sites,#wpadminbar #wp-admin-bar-new-content,#wpadminbar #wp-admin-bar-site-name,#wpadminbar #wp-admin-bar-updates,#wpadminbar #wp-admin-bar-wp-logo,#wpadminbar .ab-top-menu,#wpadminbar .ab-top-secondary{position:static}#wpadminbar #wp-admin-bar-my-account{float:right}#wpadminbar .ab-top-secondary .menupop .menupop>.ab-item:before{top:10px;left:0}}@media screen and (max-width:600px){#wpadminbar{position:absolute}#wp-responsive-overlay{position:fixed;top:0;left:0;width:100%;height:100%;z-index:400}#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper{width:100%;left:0}#wpadminbar .menupop .menupop>.ab-item:before{display:none}#wpadminbar #wp-admin-bar-wp-logo.menupop .ab-sub-wrapper{margin-left:0}#wpadminbar .ab-top-menu>.menupop li>.ab-sub-wrapper{margin:0;width:100%;top:auto;left:auto;position:static;box-shadow:none}#wpadminbar .ab-top-menu>.menupop li>.ab-sub-wrapper .ab-item{font-size:16px;padding:6px 15px 19px 30px}#wpadminbar li:hover ul li ul li{display:list-item}#wpadminbar li#wp-admin-bar-updates,#wpadminbar li#wp-admin-bar-wp-logo{display:none}}@media screen and (max-width:400px){#wpadminbar li#wp-admin-bar-comments{display:none}}</style>
			<?php
		}
	}
	
	add_action( 'wp_head', 'toby_print_header_scripts' );
	
	function toby_print_header_scripts() {
		?>
		<script>
		if ('serviceWorker' in window.navigator) {
			try {
					navigator.serviceWorker.getRegistrations().then(function(registrations) {
						if (registrations) {
							registrations.forEach(function(registration) {
								if (typeof registration.unregister === 'function') {

									// verify script url
									if (registration.active && registration.active.scriptURL) {
										if (!registration.active.scriptURL.match(/abtf-pwa/)) {
											return;
										}
									}

									registration.unregister();
								}
							});
						}
					});
				} catch (e) {

				}
		}
		</script>
				
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NMCLSLS');</script>
		<!-- End Google Tag Manager -->
		
		<?php	
	}
	
	add_action( 'theshopier_before_main_content', 'toby_print_before_main', 0 );
	
	function toby_print_before_main() {
		?>
			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMCLSLS"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
		<?php
	}
	
	add_action( 'wp_head', 'toby_print_footer_scripts' );
	
	function toby_print_footer_scripts() {
		if ( !isset( $_GET['iframe'] ) ) {
			?>
				<!--Start of Tawk.to Script-->
				<script type="text/javascript">
					if ( navigator.userAgent.indexOf( 'gtmetrix' ) < 0 || navigator.userAgent.indexOf( 'GTmetrix' ) < 0 ) {
					
						var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
						(function(){
						var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
						s1.async=true;
						s1.src='https://embed.tawk.to/5a548d4f4b401e45400bee0f/default';
						s1.charset='UTF-8';
						s1.setAttribute('crossorigin','*');
						s0.parentNode.insertBefore(s1,s0);
						})();
						
					}
				</script>
				<!--End of Tawk.to Script-->
				<script type="text/javascript">
					if ( navigator.userAgent.indexOf( 'gtmetrix' ) < 0 || navigator.userAgent.indexOf( 'GTmetrix' ) < 0 ) {
					
						Tawk_API = Tawk_API || {};
						window.dataLayer = window.dataLayer || [];
						
						Tawk_API.onChatStarted = function(){
							dataLayer.push({
								'event': 'chatStarted'
							});
						};

						Tawk_API.onChatEnded = function(){
							dataLayer.push({
								'event': 'chatEnded'
							});
						};
						
					}
				</script>		
			<?php
		}
	}