'use strict';

var $ = require('jQuery');

module.exports = {
	init: function() {
		this.exampleToggle();
	},

	exampleToggle: function() {
		$('.sg-example__toggle').on('click', function() {
			var parent = $(this).parent().parent();
			parent.find('.sg-code').toggleClass('sg-code--active');
		});
	}
};