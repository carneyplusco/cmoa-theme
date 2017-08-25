var config      = require('../config')
if(!config.tasks.images) return

var browserSync = require('browser-sync')
var changed     = require('gulp-changed')
var gulp        = require('gulp')
var imagemin    = require('gulp-imagemin')
var path        = require('path')

var paths = {
  src: path.join(config.root.src, config.tasks.svg.src, '/**'),
  dest: path.join(config.root.dest, config.tasks.svg.dest)
}

var svgTask = function() {
  return gulp.src(paths.src)
    .pipe(changed(paths.dest)) // Ignore unchanged files
    // .pipe(imagemin()) // do not optimize svgs unless you want to remove aria labels
    .pipe(gulp.dest(paths.dest))
    .pipe(browserSync.stream())
}

gulp.task('svg', svgTask)
module.exports = svgTask
