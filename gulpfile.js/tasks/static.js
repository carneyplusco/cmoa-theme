var config  = require('../config')
var browserSync  = require('browser-sync')
var gulp    = require('gulp')
var path   = require('path')

var paths = {
  src: path.join(config.root.src, config.tasks.static.src, '/*.*'),
  dest: path.join(config.root.dest, config.tasks.static.dest)
}

var staticTask = function() {
  return gulp.src(paths.src)
    .pipe(browserSync.stream({match: '**/*.{' + config.tasks.static.extensions + '}'}))
}

gulp.task('static', staticTask)
module.exports = staticTask
