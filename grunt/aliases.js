module.exports = {
	'default': [
		'clean:assets',
		'copy:fontawesome',
		'styles',
		'scripts',
		'makepot',
		'notify:default'
	],
	'styles': [
		'sass',
		'postcss:dev',
		'rtlcss',
		'notify:styles'
	],
	'scripts': [
		'jshint',
		'concat',
		'notify:scripts'
	],
	'build': [
		'clean:dist',
		'clean:assets',
		'copy:fontawesome',
		'sass',
		'rtlcss',
		'jshint',
		'concat',
		'image',
		'makepot',
		'copy:dist',
		'postcss:build',
		'notify:build'
	],
	'server': [
		'browserSync',
		'watch'
	]
};
