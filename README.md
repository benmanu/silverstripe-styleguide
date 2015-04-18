# silverstripe-styleguide
Generates a styleguide for a SilverStripe theme using KSS.

## Basic Usage
Define the base css/scss folder through the site config.

	StyleGuideController:
  	  css_base: 'themes/simple/css' 		// the base folder used to render kss.
  	  css_files:
    	- 'themes/simple/css/layout.css' 	// any css theme files to include in the styleguide.

Opens up a controller route `/style-guide`.

## Project Links
 * [KSS](http://warpspire.com/kss/)
 * [kss-php](https://github.com/scaninc/kss-php)

## TODO
 * Add some specificity to the bootstrap code so it doesn't interfere with theme styles.