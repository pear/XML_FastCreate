<?php

require_once 'XML/FastCreate.php';

$x =& XML_FastCreate::factory('Text',
    array(
        'doctype'   => XML_FASTCREATE_DOCTYPE_XHTML_1_0_STRICT,
        'quote'     => false
    )
);

$x->html(
	$x->head(
		$x->title(
			"PEAR :: The PHP Extension and Application Repository"
		), 
		$x->link(array('rel'=>"shortcut icon", 'href'=>"/gifs/favicon.ico")),
		$x->link(array('rel'=>"stylesheet", 'href'=>"/style.css")),
		$x->link(array('rel'=>"alternate", 'type'=>"application/rss+xml", 'title'=>"RSS feed", 'href'=>"http://pear.php.net/feeds/latest.rss"))
	), 
	$x->body(array('bgcolor'=>"#ffffff", 'text'=>"#000000", 'link'=>"#006600", 'alink'=>"#cccc00", 'vlink'=>"#003300"),
		$x->a(array('name'=>"TOP")
		), 
		$x->table(array('border'=>"0", 'cellspacing'=>"0", 'cellpadding'=>"0", 'width'=>"100%"),
			$x->tr(array('bgcolor'=>"#339900"),
				$x->td(array('align'=>"left", 'rowspan'=>"2", 'width'=>"120", 'colspan'=>"2", 'height'=>"1"),
					$x->a(array('href'=>"/"),
						$x->img(array('src'=>"/gifs/pearsmall.gif", 'border'=>"0", 'width'=>"104", 'height'=>"50", 'alt'=>"PEAR", 'vspace'=>"5", 'hspace'=>"5"))
					), 
					$x->br()
				), 
				$x->td(array('align'=>"right", 'valign'=>"top", 'colspan'=>"3", 'height'=>"1"),
					"&nbsp;"
				)
			), 
			$x->tr(array('bgcolor'=>"#339900"),
				$x->td(array('align'=>"right", 'valign'=>"bottom", 'colspan'=>"3", 'height'=>"1"),
					$x->a(array('href'=>"/account-request.php", 'class'=>"menuBlack"),
						"Register"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/login.php?redirect=/index.php", 'class'=>"menuBlack"),
						"Login"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/manual/", 'class'=>"menuBlack"),
						"Docs"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/packages.php", 'class'=>"menuBlack"),
						"Packages"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/support.php", 'class'=>"menuBlack"),
						"Support"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/bugs/", 'class'=>"menuBlack"),
						"Bugs"
					), 
					"&nbsp;". 
					$x->br(),
					$x->img(array('src'=>"/gifs/spacer.gif", 'width'=>"2", 'height'=>"2", 'border'=>"0", 'alt'=>"")),
					$x->br()
				)
			), 
			$x->tr(array('bgcolor'=>"#003300"),
				$x->td(array('colspan'=>"5", 'height'=>"1"),
					$x->img(array('src'=>"/gifs/spacer.gif", 'width'=>"1", 'height'=>"1", 'border'=>"0", 'alt'=>"")),
					$x->br()
				)
			), 
			$x->tr(array('bgcolor'=>"#006600"),
				$x->td(array('align'=>"right", 'valign'=>"top", 'colspan'=>"5", 'height'=>"1", 'class'=>"menuWhite"),
					$x->form(array('method'=>"post", 'action'=>"/search.php"),
						$x->small(
							"Search for"
						), 
						$x->input(array('class'=>"small", 'type'=>"text", 'name'=>"search_string", 'value'=>"", 'size'=>"20")),
						$x->small(
							"in the"
						), 
						$x->select(array('name'=>"search_in", 'class'=>"small"),
							$x->option(array('value'=>"packages"),
								"Packages"
							), 
							$x->option(array('value'=>"site"),
								"This site (using Google)"
							), 
							$x->option(array('value'=>"pear-dev"),
								"Developer mailing list"
							), 
							$x->option(array('value'=>"pear-general"),
								"General mailing list"
							), 
							$x->option(array('value'=>"pear-cvs"),
								"CVS commits mailing list"
							)
						), 
						$x->input(array('type'=>"image", 'src'=>"/gifs/small_submit_white.gif", 'alt'=>"search", 'align'=>"bottom")),
						"&nbsp;". 
						$x->br()
					)
				)
			), 
			$x->tr(array('bgcolor'=>"#003300"),
				$x->td(array('colspan'=>"5", 'height'=>"1"),
					$x->img(array('src'=>"/gifs/spacer.gif", 'width'=>"1", 'height'=>"1", 'border'=>"0", 'alt'=>"")),
					$x->br()
				)
			), 
			$x->comment(" Middle section "),
			$x->tr(array('valign'=>"top"),
				$x->td(array('colspan'=>"2", 'class'=>"sidebar_left", 'bgcolor'=>"#f0f0f0", 'width'=>"149"),
					$x->table(array('width'=>"149", 'cellpadding'=>"4", 'cellspacing'=>"0"),
						$x->tr(array('valign'=>"top"),
							$x->td(
								$x->br(),
								$x->img(array('src'=>"/gifs/box-1.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->b(
									"Home"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/news/"),
									"News"
								), 
								$x->br(),
								$x->br(),
								$x->b(
									"Documentation:"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/manual/en/about-pear.php"),
									"About&nbsp;PEAR"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/manual/index.php"),
									"Manual"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/manual/en/faq.php"),
									"FAQ"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/support.php"),
									"Support"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/group/"),
									"The&nbsp;PEAR&nbsp;Group"
								), 
								$x->br(),
								$x->br(),
								$x->b(
									"Downloads:"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/packages.php"),
									"List&nbsp;Packages"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/package-search.php"),
									"Search&nbsp;Packages"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/package-stats.php"),
									"Statistics"
								), 
								$x->br(),
								$x->br(),
								$x->b(
									"Package Proposals:"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/pepr/pepr-overview.php"),
									"Browse&nbsp;proposals"
								), 
								$x->br(),
								$x->img(array('src'=>"/gifs/box-0.gif", 'border'=>"0", 'width'=>"11", 'height'=>"7", 'alt'=>"")),
								$x->a(array('href'=>"/pepr/pepr-proposal-edit.php"),
									"New&nbsp;proposal"
								), 
								$x->br(),
								$x->br()
							)
						)
					)
				), 
				$x->td(
					$x->table(array('width'=>"100%", 'cellpadding'=>"10", 'cellspacing'=>"0"),
						$x->tr(
							$x->td(array('valign'=>"top"),
								$x->h1(
									"PEAR - PHP Extension and Application Repository"
								), 
								$x->p(
									$x->acronym(array('title'=>"PHP Extension and Application Repository"),
										"PEAR"
									), 
									"is a framework and distribution system for reusable PHP
components. More". 
									$x->b(
										"information"
									), 
									"about PEAR can be found in the". 
									$x->a(array('href'=>"/manual/en/"),
										"online manual"
									), 
									"and the". 
									$x->a(array('href'=>"/manual/en/faq.php"),
										"FAQ"
									), 
									"."
								), 
								$x->p(
									"If you are a first time user, you might be especially interested in
the manual chapter &quot;". 
									$x->a(array('href'=>"/manual/en/about-pear.php"),
										"About PEAR"
									), 
									"&quot;."
								), 
								$x->p(
									"Recent". 
									$x->b(
										"news"
									), 
									"about PEAR can be found". 
									$x->a(array('href'=>"/news/"),
										"here"
									), 
									"."
								), 
								$x->p(
									"PEAR provides the above mentioned PHP components in the form of so
called &quot;Packages&quot;. If you would like to". 
									$x->b(
										"download"
									), 
									"PEAR
packages, you can". 
									$x->a(array('href'=>"/packages.php"),
										"browse the complete list"
									), 
									"here.  Alternatively you  can  search for packages by some keywords
using the search box above. Apart from simply downloading a package,
PEAR also provides a command-line interface that can be used to
automatically". 
									$x->b(
										"install"
									), 
									"packages. The manual". 
									$x->a(array('href'=>"/manual/en/installation.cli.php"),
										"describes this procedure"
									), 
									"in detail."
								), 
								$x->p(
									"In case you need". 
									$x->b(
										"support"
									), 
									"for PEAR in general or a package
in special, we have compiled a list of the". 
									$x->a(array('href'=>"/support.php"),
										"available
support resources"
									), 
									"."
								), 
								$x->hr(),
								$x->p(
									"If you have been told by other PEAR developers to sign up for a
PEAR website account, you can use". 
									$x->a(array('href'=>"/account-request.php"),
										"this interface"
									), 
									"."
								)
							)
						)
					)
				), 
				$x->td(array('class'=>"sidebar_right", 'width'=>"149", 'bgcolor'=>"#f0f0f0"),
					$x->table(array('width'=>"149", 'cellpadding'=>"4", 'cellspacing'=>"0"),
						$x->tr(array('valign'=>"top"),
							$x->td(
								$x->h3(
									"Recent Releases"
								), 
								$x->table(
									$x->tr(
										$x->td(array('valign'=>"top", 'class'=>"compact"),
											$x->a(array('href'=>"/package/Image_Text/"),
												"Image_Text 0.5.1beta"
											), 
											$x->br(),
											$x->i(
												"2004-04-18:"
											), 
											"* Fixed bug #1207 supporting old version..."
										)
									), 
									$x->tr(
										$x->td(array('valign'=>"top", 'class'=>"compact"),
											$x->a(array('href'=>"/package/HTML_Progress/"),
												"HTML_Progress 1.2.0RC1"
											), 
											$x->br(),
											$x->i(
												"2004-04-18:"
											), 
											"* TODO
- this 1st RC is still marked as ..."
										)
									), 
									$x->tr(
										$x->td(array('valign'=>"top", 'class'=>"compact"),
											$x->a(array('href'=>"/package/XML_Parser/"),
												"XML_Parser 1.1.0beta2"
											), 
											$x->br(),
											$x->i(
												"2004-04-17:"
											), 
											"beta2:
- Fixed calling of __construct

b..."
										)
									), 
									$x->tr(
										$x->td(array('valign'=>"top", 'class'=>"compact"),
											$x->a(array('href'=>"/package/XML_Parser/"),
												"XML_Parser 1.1.0beta1"
											), 
											$x->br(),
											$x->i(
												"2004-04-17:"
											), 
											"- Fixed memory leaks parsing many docume..."
										)
									), 
									$x->tr(
										$x->td(array('valign'=>"top", 'class'=>"compact"),
											$x->a(array('href'=>"/package/Image_Color/"),
												"Image_Color 1.0.0"
											), 
											$x->br(),
											$x->i(
												"2004-04-17:"
											), 
											"+ Added color2RGB() method.

  (Contribu..."
										)
									), 
									$x->tr(
										$x->td(
											"&nbsp;"
										)
									), 
									$x->tr(
										$x->td(array('width'=>"100%", 'align'=>"right"),
											$x->a(array('href'=>"/feeds/"),
												"Syndicate this"
											)
										)
									)
								), 
								$x->br()
							)
						)
					)
				)
			), 
			$x->comment(" Lower bar "),
			$x->tr(array('bgcolor'=>"#003300"),
				$x->td(array('colspan'=>"5", 'height'=>"1"),
					$x->img(array('src'=>"/gifs/spacer.gif", 'width'=>"1", 'height'=>"1", 'border'=>"0", 'alt'=>"")),
					$x->br()
				)
			), 
			$x->tr(array('bgcolor'=>"#339900"),
				$x->td(array('align'=>"right", 'valign'=>"bottom", 'colspan'=>"5", 'height'=>"1"),
					$x->a(array('href'=>"/about/privacy.php", 'class'=>"menuBlack"),
						"PRIVACY POLICY"
					), 
					"&nbsp;|&nbsp;". 
					$x->a(array('href'=>"/credits.php", 'class'=>"menuBlack"),
						"CREDITS"
					), 
					$x->br()
				)
			), 
			$x->tr(array('bgcolor'=>"#003300"),
				$x->td(array('colspan'=>"5", 'height'=>"1"),
					$x->img(array('src'=>"/gifs/spacer.gif", 'width'=>"1", 'height'=>"1", 'border'=>"0", 'alt'=>"")),
					$x->br()
				)
			), 
			$x->tr(array('valign'=>"top", 'bgcolor'=>"#cccccc"),
				$x->td(array('colspan'=>"5", 'height'=>"1"),
					$x->table(array('border'=>"0", 'cellspacing'=>"0", 'cellpadding'=>"5", 'width'=>"100%"),
						$x->tr(
							$x->td(
								$x->small(
									$x->a(array('href'=>"/copyright.php"),
										"Copyright &copy; 2001-2004 The PHP Group"
									), 
									$x->br(),
									"All rights reserved.". 
									$x->br()
								)
							), 
							$x->td(array('align'=>"right", 'valign'=>"top"),
								$x->small(
									"Last updated: Fri Apr 09 09:04:31 2004 EDT". 
									$x->br(),
									"Bandwidth and hardware provided by:". 
									$x->a(array('href'=>"http://www.pair.com/"),
										"pair Networks"
									)
								)
							)
						)
					)
				)
			)
		)
	)
);


$x->toXML();

?>