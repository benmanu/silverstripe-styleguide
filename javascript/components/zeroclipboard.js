'use strict';

var ZeroClipboard = require('zeroclipboard'),
	$ = require('jquery');

$('.sg-copy-button span').each(function() {
	new ZeroClipboard($(this));
});
