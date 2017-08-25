var config      = require('../config')
if(!config.tasks.bower) return

var browserSync = require('browser-sync')
var changed     = require('gulp-changed')
var gulp        = require('gulp')
var path        = require('path')
var mainBowerFiles = require('main-bower-files')

var paths = {
  dest: path.join(config.root.dest, config.tasks.bower.dest)
}

var bowerTask = function() {
  return gulp.src(mainBowerFiles(), {base: config.tasks.bower.src})
    .pipe(changed(paths.dest))
    .pipe(gulp.dest(paths.dest))
}

gulp.task('bower', bowerTask)
module.exports = bowerTask
