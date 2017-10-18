module.exports = {
	/**
	 * grunt-rsync-2
	 *
	 * Copy files to a (remote) machine running an SSH daemon with 'rsync'.
	 *
	 * @link https://www.npmjs.com/package/grunt-rsync-2
	 */
	production: {
		files: 'dist/<%= package.name %>',
		options: {
			host      : "174.138.70.35",
			user      : "ellucian",
			remoteBase: "/var/www/ellucianwebservices.com/htdocs/wp-content/themes"
		}
	}
};
