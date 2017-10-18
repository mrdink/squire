module.exports = {
	/**
	 * grunt-postcss
	 *
	 * Apply several post-processors to your CSS using PostCSS
	 *
	 * @link https://www.npmjs.com/package/grunt-postcss
	 */
	options: {
		processors: [
			require( 'pixrem' )(),
			require( 'autoprefixer' )(
				{
					browsers: ['last 2 versions']
				}
			)
		]
	},
	dev: {
		map: true,
		src: ['assets/css/*.css']
	},
	build: {
		map: false,
		src: ['dist/<%= package.name %>/assets/css/*.css']
	}
};
