# silverstripe-styleguide
Generates a styleguide for a SilverStripe theme using KSS.

## Basic Usage
Define the base css/scss folder through the site config.

	StyleGuideController:
  	  css_base: 'themes/simple/css' 		// the base folder used to render kss.
  	  css_files:
    	- 'themes/simple/css/layout.css' 	// any css theme files to include in the styleguide.

Opens up a controller route `/style-guide`.

Comments tagged with `Styleguide 1.0`, `Styleguide 2.0` etc are used to create the main navigation.
Sub-navigation sections are generated with tags of `Styleguide 1.1`, `Styleguide 1.2` etc.
Sub-navigation children are made up of section modifiers like `.btn-default`, `.btn-primary`.

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

See the KSS documentation for further details, with the exception being the `Template:` parameter. This will render a SilverStripe template file as the example.

## Project Links
 * [KSS](http://warpspire.com/kss/)
 * [kss-php](https://github.com/scaninc/kss-php)

## TODO
 * Add some specificity to the bootstrap code so it doesn't interfere with theme styles.