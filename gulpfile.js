var gulp = require('gulp');
var sass = require('gulp-sass');
var bower = require('gulp-bower');
var elixir = require('laravel-elixir');

gulp.task('bower', function() {
    return bower();
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

        // Copy fonts straight to public
        mix.copy('resources/vendor/bootstrap/dist/fonts/**', 'public/fonts');
        mix.copy('resources/vendor/font-awesome/fonts/**', 'public/fonts');

        // Copy asset images to public
        mix.copy('resources/assets/images/**', 'public/images');

        // Merge Site scripts.
        mix.scripts([
            paths.jquery + '/jquery.js',
            paths.bootstrap + '/js/bootstrap.js',
            paths.bootstrapDatepicker + '/js/bootstrap-datepicker.js',
            'general.js',
        ], 'public/js/scripts.js');

        // Merge Site CSSs.
        mix.sass([
            'index.scss'
        ], 'resources/assets/css/custom.css')
        .styles([
            paths.bootstrap + '/css/bootstrap.css',
            paths.fontawesome + '/css/font-awesome.css',
            paths.bootstrapDatepicker + '/css/bootstrap-datepicker.css',
            '/resources/assets/css/custom.css'
        ], 'public/css/styles.css');

        mix.copy('resources/assets/js/html5lightbox', 'public/js/html5lightbox');
    });
});
