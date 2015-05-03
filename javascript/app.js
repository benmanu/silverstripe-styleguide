var site = require('./components/site');
site.init();

var affix = require('./components/affix');
affix('#sg-subnav', { top: 71 });

var scrollspy = require('./components/scrollspy');
scrollspy('body', { target: '#sg-subnav' });

require('./components/zeroclipboard');