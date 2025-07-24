const gulp = require("gulp")
const sass = require('gulp-sass')
const autoprefixer = require("gulp-autoprefixer")
const plumber = require("gulp-plumber")
const sourcemaps = require('gulp-sourcemaps')
const browserSync = require('browser-sync')
const bs = require("browser-sync").create()

 function _browserSync () {
  bs.init({
    proxy: "daudin-779.local:10022",
    ghostMode: false,
    open: false,
    notify: false
  })
}

 function _sass () {
  return gulp.src('./scss/main.scss')
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass()) // Converts Sass to CSS with gulp-sass
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(autoprefixer({ browsers: ["last 2 versions"] }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./css'))
    .pipe(browserSync.reload({
      stream: true
    }))
}


function _watch () {
  gulp.watch('./scss/**/*.scss', sass)
  gulp.watch('./scss/**/*.scss').on("change", bs.reload)
  gulp.watch("*.php").on("change", bs.reload)
  gulp.watch("*.php").on("change", bs.reload)
  gulp.watch("*.js").on("change", bs.reload)
  gulp.watch("views/**/*.twig").on("change", bs.reload)
}

exports.watch = gulp.series(_watch, _sass, _browserSync)
