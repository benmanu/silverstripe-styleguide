# silverstripe-styleguide
[![Build Status](https://travis-ci.org/benmanu/silverstripe-styleguide.svg?branch=experiments%2Fpages)](https://travis-ci.org/benmanu/silverstripe-styleguide)
Generates a styleguide for a SilverStripe theme using CSS documentation.

## Installation
	
	$ composer require benmanu/silverstripe-styleguide

## Basic Usage
Define the base css/scss folder through the site config.

	StyleGuideController:
  	  paths: 'styleguide/scss' 				// the base folder used to render kss.
  	  css_files:
    	- 'themes/default/css/screen.css' 	// any css theme files to include in the styleguide.
  	  js_files:
    	- 'themes/default/js/script.js' 	// any js theme files to include in the styleguide.

Opens up a controller route `/style-guide`.

Comments tagged with `Styleguide 1.0`, `Styleguide 2.0` etc are used to create the main navigation.
Sub-navigation sections are generated with tags of `Styleguide 1.1`, `Styleguide 1.2` etc.
Sub-navigation children are made up of section modifiers like `.btn-default`, `.btn-primary`.

## Example
You can use the styleguide module scss/css as an example using the below config.

	StyleGuideController:
  	  paths: 'styleguide/scss'
  	  css_files:
    	- 'styleguide/dist/css/screen.css'

## Kitchen Sink Example
	/*
	#Components

	All the components!

	Styleguide 1.0
	*/

	/*
	#Buttons

	Use the button classes on an <a>, <button>, or <input> element.

	Markup: 
	<a class="btn $modifierClass">Button</a>

	Template: Button

	SectionTemplate: SGColorPalette

	Deprecated:
	If there was a deprecation notice it would go here.

	Experimental:
	If there was any experimental notes they would go here.

	.btn-default - Standard button.
	.btn-default:hover - Subtle hover highlight.
	.btn-primary - Provides extra visual weight and identifies the primary action in a set of buttons.
	.btn-success - Indicates a successful or positive action.
	.btn-danger - Indicates a dangerous or potentially negative action.

	$success - The success hex code variable.
	$danger - The danger hex code variable.

	Compatible in IE6+, Firefox 2+, Safari 4+.

	Styleguide 1.1
	*/

See the KSS documentation for further details, with a couple of exceptions being the `Template:`, and `SectionTemplate:` parameters. The `Template:` parameter will render a SilverStripe template file as the example (see Fixtures below), and the `SectionTemplate:` will render a SilverStripe template file as the Section display.

All comment descriptions are treated as markdown and parsed through [parsedown](http://parsedown.org/).

## Fixtures
A yml fixture file can be created in the **(project)/styleguide/** directory called **fixture.yml**, used to populate template variables. 

All template files should be placed under the key **Template**, example:
	
	Template:
  	  Footer:
  	  	FooterContent: '<p>Here is some footer content</p>'

Alternatively you can reference other non-template values to populate relationships (has_one, has_many, many_many) and field values, example:
	
	SiteConfig:
	  main:
	  	Title: MySite Title
    Site:
  	  link1:
        Link: #link1
        Text: Link 1
      link2:
        Link: #link2
        Text: Link 2
      link3:
        Link: #link3
        Text: Link 3
	StyleGuide:
  	  main:
        Content: '<p>Here is some footer content</p>'
	
	Template:
  	  Footer:
  	  	SiteConfig: =>SiteConfig.main
  	  	FooterLinks: =>Site.link1, =>Site.link2, =>Site.link3
  	  	FooterContent: =>StyleGuide.main.Content

## Pages
A yml fixture file can be created in the **(project)/styleguide/** directory called **pages.yml**, used to create additional main and children pages. The `Template` parameter should be the same name as a SilverStripe template file in your theme.

	Documentation:
	  general:
	    Title: General
	    Template: SG_General
	  css:
	    Title: Css
	    Template: SG_Css
	  js:
	    Title: Js
	    Template: SG_Js
	  git:
	    Title: Git
	    Template: SG_Git
	  images:
	    Title: Images and icons
	    Template: SG_ImagesAndIcons

	Children:
	  child1:
	    Title: Child 1
	    Template: SGChild
	  child2:
	    Title: Child 2
	    Template: SGChild

	Page:
	  documentation:
	    Title: Documentation
	    Children: =>Documentation.general, =>Documentation.css, =>Documentation.js, =>Documentation.git, =>Documentation.images
	  performanceMonitoring:
	    Title: Performance Monitoring
	    Template: SGPerformanceMonitoring
	    Children: =>Children.child1, =>Children.child2

## Project Links
 * [KSS](http://warpspire.com/kss/)
 * [parsedown](http://parsedown.org/)
