var gulp = require('gulp');
var watch = require('gulp-watch');

gulp.task('watch', function() {
    return watch('**/*', ['build']);
});

gulp.task('build', function() {
    gulp.src('**/*')
        .pipe(gulp.dest('../kam-on-wp/wp-content/plugins/forum-plugin'));
});