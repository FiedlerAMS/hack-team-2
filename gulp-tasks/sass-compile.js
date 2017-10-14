/**
 * A .scss compilation and minification task.
 * @example gulp sass-compile
 * @requires npm:gulp-sass
 * @requires npm:gulp-plumber
 * @requires npm:gulp-postcss
 * @requires npm:gulp-sourcemaps
 * @requires npm:gulp-notify
 * @requires npm:gulp-wait2
 * @requires npm:autoprefixer
 * @requires npm:cssnano
 * @param config.css.srcFiles Source files
 * @param config.css.distPath Destination path
 */
module.exports = function (gulp, $, config, bs) {
    var autoprefixer = require('autoprefixer');
    var cssnano = require('cssnano');

    gulp.task('sass-compile', function () {
        //Postcss processors
        var processors = [
            autoprefixer({ browsers: config.browserList }),
            cssnano
        ];

        return gulp.src(config.css.srcFiles)
            .pipe($.plumber())
            .pipe($.sourcemaps.init())
            .pipe($.wait2(1000))
            .pipe($.sass({
                includePaths: config.css.watchPaths,
                outputStyle: 'compressed'
            }))
            .on('error', $.notify.onError({
                message: "<%= error.message %>",
                title: "Sass Error"
            }))
            .pipe($.postcss(processors))
            .pipe($.sourcemaps.write('.'))
            .pipe(gulp.dest(config.css.distPath))
            .pipe(bs.stream());
    });
};