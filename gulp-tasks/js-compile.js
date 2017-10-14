/**
 * A .js concat and minification task.
 * @example gulp js-compile
 * @requires npm:gulp-concat
 * @requires npm:gulp-minify
 * @requires npm:merge-stream
 * @param config.js.concat {concat.src, concat.dist} Source files for concatenation
 * @param config.js.copy Source files, which will be copied to destination
 * @param config.js.distPath Destination path
 */
module.exports = function (gulp, $, config) {
    var stream = require('merge-stream')();

    gulp.task('js-compile', function () {
        if (config.js.concat != undefined) {
            config.js.concat.forEach(function (key) {
                stream.add(
                    gulp.src(key.src)
                        .pipe($.concat(key.dist, {
                            newLine: '\n;'
                        }))
                        .pipe($.minify({
                            ext: {
                                src: '-debug.js',
                                min: '.js'
                            }
                        }))
                        .pipe(gulp.dest(config.js.distPath))
                );
            });
        }

        if (config.js.copy != undefined) {
            stream.add(
                gulp.src(config.js.copy)
                    .pipe($.minify({
                        ext: {
                            min: '.min.js'
                        }
                    }))
                    .pipe(gulp.dest(config.js.distPath))
            );
        }

        return stream.isEmpty() ? null : stream;
    });
};