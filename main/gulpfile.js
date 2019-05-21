var gulp = require('gulp');
var exec = require('child_process').exec;
var watch = require('gulp-watch');

var fileFilter = ['**/*.js', '**/*.php', '!**/*prod.js', '**/*.scss', '**/*.css' ];

gulp.task('watch', function(cb) {
	watch(fileFilter, function() {
        exec('sh build.sh', function (err, stdout, stderr) {
            console.log(stdout);
        });
	})
});

