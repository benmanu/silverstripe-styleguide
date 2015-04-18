/**
 * Tasks:
 *     gulp             (default prod task)
 *     gulp css         (dev css task)
 *     gulp js          (dev js task)
 *     gulp watch       (watcher task)
 *     gulp devwatch    (dev watcher task)
 */

// requirements
var gulp        = require('gulp');
var sass        = require('gulp-sass');
var minifyCSS   = require('gulp-minify-css');
var prefix      = require('gulp-autoprefixer');

// source paths
var base = ''; // theme root

var build = { // build folders (where will the development happen?)
	scss: base + 'scss/'
}

var dist = { // distribution folders (where should the output live?)
	css: base + 'css/'
}

// build paths
var paths = {
	css: {
		src: [
			build.scss + '*.scss'
		],
		dest: dist.css,
		watch: [
			build.scss + '*.scss',
			build.scss + '**/*.scss'
		]
	}
}

// dev css processing (unminified)
gulp.task('css', function() {

	var src = paths.css.src;
	var dest = paths.css.dest;

	// compile the css
    return gulp.src(src)
        .pipe(sass({
        	sourceComments: 'map',
        	includePaths: [
        		build.scss
        	]
        }))
        .pipe(prefix("last 1 version", "> 1%", "ie 8"))
        .pipe(gulp.dest(dest));

});

// production css processing (minified)
gulp.task('css:prod', ['css'], function() {

    var src = paths.css.dest + '*.css';
    var dest = paths.css.dest;

    // compile the css
    return gulp.src(src)
        .pipe(minifyCSS())
        .pipe(gulp.dest(dest));
});

gulp.task('watch', ['css:prod'], function() {
	gulp.watch(paths.css.watch, ['css:prod']);
});

gulp.task('default', [
	'css:prod'
]);
