module.exports = {
	/**
	 * grunt-contrib-concat
	 *
	 * Concatenate files.
	 *
	 * Concatenates an array of js files set in /grunt/vars.js
	 *
	 * @link https://www.npmjs.com/package/grunt-contrib-concat
	 */
	options: {
		separator: ';\n',
		stripBanners: true,
		banner: '/*!\n' +
		' * @package <%= package.title %>\n' +
		' * @version <%= package.version %>\n' +
		' */\n'
	},
	foundation: {
		src: [
			'node_modules/what-input/dist/what-input.js',
			'node_modules/foundation-sites/dist/js/foundation.js'
		],
		dest: 'assets/js/foundation.js'
	},
	theme: {
		src: [
			'node_modules/clipboard/dist/clipboard.js',
			'src/js/_*.js'
		],
		dest: 'assets/js/theme.js'
	},
	color_scheme_control: {
		src: [
			'src/js/color-scheme-control.js'
		],
		dest: 'assets/js/color-scheme-control.js'
	},
	customize_preview: {
		src: [
			'src/js/customize-preview.js'
		],
		dest: 'assets/js/customize-preview.js'
	},
	skip: {
		src: [
			'src/js/skip-link-focus-fix.js'
		],
		dest: 'assets/js/skip-link-focus-fix.js'
	}
};
