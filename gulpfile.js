'use strict';

const gulp = require( 'gulp' );
const sass = require( 'gulp-sass' )(require( 'sass' ) );

gulp.task( 'css-admin', () => {
	return gulp.src( 'assets/scss/admin/styles.scss' )
		.pipe( sass( { outputStyle: 'compressed' } ).on( 'error', sass.logError) )
		.pipe( gulp.dest( 'assets/css/admin/') );
});

gulp.task( 'admin:styles:watch', () => {
	return gulp.watch( 'assets/scss/admin/styles.scss', gulp.series( ...[ 'css-admin' ] ) );
} );