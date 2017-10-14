/*jslint node: true */
"use strict";

var gulp = require('gulp');
var readConfig = require('read-config');
var config = readConfig('./gulp-config.json', { override: true });
var $ = require('gulp-load-plugins')();
var browsersync = require('browser-sync').create();
var argv = require('yargs').argv;

// SASS Compilation
require(config.tasksPath + '/sass-compile')(gulp, $, config, browsersync);

// JS Compilation
require(config.tasksPath + '/js-compile')(gulp, $, config);

// Watch tasks
require(config.tasksPath + '/watch')(gulp, $, config, browsersync, []);

gulp.task('sync', ['build'], function () {
    var localConfig = readConfig('./gulp-config.local.json', { override: true });

    browsersync.init({
        // Proxy address
        proxy: localConfig.localPath,
        minify: true,
        ghostMode: {
            clicks: true,
            forms: true,
            scroll: false
        },
        open: false
    });
    gulp.start('watch');
});

gulp.task('bs-reload', function () {
    return browsersync.reload();
});

gulp.task('build', function () {
    gulp.start('sass-compile');
    gulp.start('js-compile');
});

// Default Task Triggers Build and Watch
gulp.task('default', ['build'], function () {
    gulp.start('watch');
});