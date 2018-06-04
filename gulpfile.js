var gulp = require('gulp');
var sass = require('gulp-sass');
var bower = require('gulp-bower');
var elixir = require('laravel-elixir');

gulp.task('bower', function() {
    return bower();
});

//sass
gulp.task('sass', function () {
    gulp.src(['resources/assets/scss/**/*.scss'])
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(gulp.dest('public/css/'));
});

//sass
gulp.task('watch-sass', function () {
    gulp.watch(['resources/assets/scss/**/*.scss'], ['sass']);
});


// Default task
gulp.task('default', function () {
    var vendors = '../../vendor/';

    var paths = {
        'jquery': vendors + '/jquery/dist',
        'bootstrap': vendors + '/bootstrap/dist',
        'bootstrapDatepicker': vendors + '/bootstrap-datepicker/dist',
        'fontawesome': vendors + '/font-awesome',
    };

    elixir.config.sourcemaps = false;

    elixir(function(mix) {

        // Run bower install
        mix.task('bower');

        // Precompile SCSS
        mix.task('sass');

        // Copy fonts straight to public
        mix.copy('resources/vendor/bootstrap/dist/fonts/**', 'public/fonts');
        mix.copy('resources/vendor/font-awesome/fonts/**', 'public/fonts');

        // Copy asset images to public
        mix.copy('resources/assets/images/**', 'public/images');

        // Merge Site CSSs.
        mix.styles([
            paths.bootstrap + '/css/bootstrap.css',
            paths.fontawesome + '/css/font-awesome.css',
            paths.bootstrapDatepicker + '/css/bootstrap-datepicker.css'
        ], 'public/css/site.css');

        // Merge Site scripts.
        mix.scripts([
            paths.jquery + '/jquery.js',
            paths.bootstrap + '/js/bootstrap.js',
            paths.bootstrapDatepicker + '/js/bootstrap-datepicker.js',
            'general.js',
        ], 'public/js/site.js');

        // Merge Admin CSSs.
        mix.styles([
            paths.bootstrap + '/css/bootstrap.css',
            paths.fontawesome + '/css/font-awesome.css',
            paths.bootstrapDatepicker + '/css/bootstrap-datepicker.css',
        ], 'public/css/admin.css');

        // Merge Admin scripts.
        mix.scripts([
            paths.jquery + '/jquery.js',
            paths.bootstrap + '/js/bootstrap.js',
            paths.bootstrapDatepicker + '/js/bootstrap-datepicker.js',
            'general.js',
        ], 'public/js/admin.js');

        mix.copy('resources/assets/js/html5lightbox', 'public/js/html5lightbox');
    });
});
