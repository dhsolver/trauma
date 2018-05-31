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
        'colorbox': vendors + '/jquery-colorbox',
        'dataTables': vendors + '/datatables/media',
        'dataTablesBootstrap3Plugin': vendors + '/datatables-bootstrap3-plugin/media',
        'flag': vendors + '/flag-sprites/dist',
        'metisMenu': vendors + '/metisMenu/dist',
        'datatablesResponsive': vendors + '/datatables-responsive',
        'summernote': vendors + '/summernote/dist',
        'select2': vendors + '/select2/dist',
        'jqueryui':  vendors + '/jquery-ui',
        'justifiedGallery':  vendors + '/Justified-Gallery/dist/',
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
        mix.copy('resources/vendor/summernote/dist/font/**', 'public/css/font');

        // Copy images straight to public
        mix.copy('resources/vendor/jquery-colorbox/example3/images/**', 'public/css/images');
        mix.copy('resources/vendor/jquery-ui/themes/base/images/**', 'public/css/images');

        // Copy asset images to public
        mix.copy('resources/assets/images/**', 'public/images');

        // Copy flag resources
        mix.copy('resources/vendor/flag-sprites/dist/css/flag-sprites.min.css', 'public/css/flags.css');
        mix.copy('resources/vendor/flag-sprites/dist/img/flags.png', 'public/img/flags.png');

        // Merge Site CSSs.
        mix.styles([
            paths.bootstrap + '/css/bootstrap.css',
            paths.fontawesome + '/css/font-awesome.css',
            paths.colorbox + '/example3/colorbox.css',
            paths.justifiedGallery + '/css/justifiedGallery.css',
            paths.bootstrapDatepicker + '/css/bootstrap-datepicker.css'
            // '/../scss/index.css'
        ], 'public/css/site.css');

        // Merge Site scripts.
        mix.scripts([
            paths.jquery + '/jquery.js',
            paths.bootstrap + '/js/bootstrap.js',
            paths.colorbox + '/jquery.colorbox.js',
            paths.justifiedGallery + '/js/jquery.justifiedGallery.js',
            paths.bootstrapDatepicker + '/js/bootstrap-datepicker.js',
            // 'html5lightbox.js',
            'general.js',
        ], 'public/js/site.js');

        // Merge Admin CSSs.
        mix.styles([
            paths.bootstrap + '/css/bootstrap.css',
            paths.fontawesome + '/css/font-awesome.css',
            paths.colorbox + '/example3/colorbox.css',
            paths.dataTables + '/css/dataTables.bootstrap.css',
            paths.dataTablesBootstrap3Plugin + '/css/datatables-bootstrap3.css',
            paths.metisMenu + '/metisMenu.css',
            paths.summernote + '/summernote.css',
            paths.select2 + '/css/select2.css',
            paths.jqueryui + '/themes/base/minified/jquery-ui.min.css',
            paths.bootstrapDatepicker + '/css/bootstrap-datepicker.css',
        ], 'public/css/admin.css');

        // Merge Admin scripts.
        mix.scripts([
            paths.jquery + '/jquery.js',
            paths.jqueryui + '/ui/jquery-ui.js',
            paths.bootstrap + '/js/bootstrap.js',
            paths.colorbox + '/jquery.colorbox.js',
            paths.dataTables + '/js/jquery.dataTables.js',
            paths.dataTables + '/js/dataTables.bootstrap.js',
            paths.dataTablesBootstrap3Plugin + '/js/datatables-bootstrap3.js',
            paths.datatablesResponsive + '/js/dataTables.responsive.js',
            paths.metisMenu + '/metisMenu.js',
            paths.summernote + '/summernote.js',
            paths.select2 + '/js/select2.js',
            paths.bootstrapDatepicker + '/js/bootstrap-datepicker.js',
            'bootstrap-dataTables-paging.js',
            'dataTables.bootstrap.js',
            'datatables.fnReloadAjax.js',
            'general.js',
        ], 'public/js/admin.js');
    });
});
