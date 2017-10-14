/**
 * Run specified tasks, watch for changes defined in config and run defined tasks
 * @example gulp watch
 * @requires npm:colors
 * @param tasks Tasks to run before this task (e.g. 'build')
 * @param config.watch.paths Pattern of files to watch
 * @param config.watch.exec  Names of tasks to execute
 */
module.exports = function (gulp, $, config, bs, tasks) {
    var colors = require('colors');

    gulp.task('watch', tasks, function () {
        // Log file changes to console
        function logFileChange(event) {
            var fileName = require('path').relative(__dirname, event.path);
            console.log('[' + 'WATCH'.green + '] Detected ' + event.event + ' on ' + fileName.magenta + ', running tasks...');
        }

        //Iterate through watch keys
        config.watch.forEach(function (key) {
            $.watch(key.paths, function (event) {
                logFileChange(event);
                gulp.start(key.exec);
                bs.reload;
            });
        });
    });
};