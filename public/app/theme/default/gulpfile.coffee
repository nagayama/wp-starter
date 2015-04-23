gulp        = require('gulp')
$           = require('gulp-load-plugins')()
argv        = require('minimist')(process.argv.slice(2))
browserSync = require('browser-sync')
runSequence = require('run-sequence')

devUrl = 'http://wp.dev/'
require('dotenv').config(path: "../../../../.env")

glob =
  dist    : "dist/**/*"
  images  : "assets/images/**/*"
  coffee  : "assets/scripts/**/*.coffee"
  scripts : "assets/scripts/**/*.js"
  sass    : "assets/styles/**/*.scss"
  html    : ['*.php', 'views/**/*.twig']

dist =
  manifest : "dist/manifest.json"
  images   : "dist/images"
  coffee   : "dist/scripts"
  scripts  : "dist/scripts"
  sass     : "dist/styles"

devEnv = process.env.WP_ENV || 'development'
devEnv = 'production' if argv.production
isProduction = true if devEnv == "production"

gulp.task "coffeelint", ->
  conf =
    indentation:
      value: 2
      level: 'error'
  gulp
    .src   glob.coffee
    .pipe  $.coffeelint opt: conf
    .pipe  $.coffeelint.reporter()

gulp.task "coffee", ["coffeelint"], ->
  gulp
    .src  glob.coffee
    .pipe $.changed dist.coffee
    .pipe $.coffee().on('error', $.util.log)
    .pipe $.if isProduction, $.uglify()
    .pipe $.if isProduction, $.rev()
    .pipe gulp.dest dist.coffee

    .pipe $.if isProduction, $.rev.manifest dist.manifest, merge: true
    .pipe $.if isProduction, gulp.dest "."

gulp.task 'images', ->
  gulp
    .src  glob.images
    .pipe $.changed dist.images
    .pipe $.imagemin
      progressive: true
      interlaced: true
    .pipe gulp.dest dist.images

gulp.task 'sass', ->
  gulp
    .src glob.sass
    .pipe $.changed dist.sass
    .pipe $.if !isProduction, $.sourcemaps.init()
    .pipe $.sass()
    .pipe $.autoprefixer 'last 1 version', 'ie 9'
    .pipe $.minifyCss()
    .pipe $.if isProduction, $.rev()
    .pipe $.if !isProduction, $.sourcemaps.write '.'
    .pipe gulp.dest dist.sass

    .pipe $.if isProduction, $.rev.manifest dist.manifest, merge: true
    .pipe $.if isProduction, gulp.dest "."

gulp.task 'watch', ['build'], ->
  browserSync
    files: [glob.dist, glob.html]
    proxy: devUrl
    snippetOptions:
      whitelist: ['/wp-admin/admin-ajax.php'],
      blacklist: ['/wp-admin/**']
  gulp.watch [glob.sass],   ['sass']
  gulp.watch [glob.coffee], ['coffee']
  gulp.watch [glob.images], ['images']

gulp.task 'clean', require('del').bind(null, [glob.dist])

gulp.task 'build', (cb) ->
  runSequence 'clean', ['sass', 'coffee', 'images'], cb

gulp.task 'default', ->
  gulp.start 'build'

