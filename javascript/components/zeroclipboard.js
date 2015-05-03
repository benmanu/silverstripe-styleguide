'use strict';

var ZeroClipboard = require('zeroclipboard'),
	$ = require('jquery');

$('.sg-example__copy').each(function() {
	new ZeroClipboard($(this));
});
