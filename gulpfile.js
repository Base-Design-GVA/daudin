const gulp = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync');
const reload = browserSync.reload;
const concat = require('gulp-concat');
sass.compiler = require('node-sass');



gulp.task('browser-sync', function () {
  const files = ['./scss/*.scss', './*.php', './js/*.js'];
  browserSync.init(files, {
    proxy: 'http://localhost:8888/wp_daudin/',
    port: 3004,
  });
  gulp.watch('./scss/**/*.scss', gulp.series(css));
  gulp.watch('./js/*.js').on('change', browserSync.reload);
  gulp.watch('./*.php').on('change', browserSync.reload);
  gulp.watch("views/**/*.twig").on("change", browserSync.reload)
});

const css = function () {
  return gulp
    .src('./scss/main.scss')
    .pipe(sourcemaps.init())
    .pipe(
      sass({
        outputStyle: 'compressed',
      }).on('error', sass.logError),
    )
    .pipe(autoprefixer())
    .pipe(sourcemaps.write())
    .pipe(concat('main.css'))
    .pipe(gulp.dest('./css/'))
    .pipe(reload({ stream: true }));
};

const watch = function (cb) {
  gulp.watch('./scss/**/*.scss', gulp.series(css));
  gulp.watch('./js/*.js').on('change', browserSync.reload);
  gulp.watch('./*.php').on('change', browserSync.reload);
  gulp.watch('./*.twig').on('change', browserSync.reload);
  cb();
};

exports.css = css;
exports.watch = gulp.series(css, 'browser-sync');
