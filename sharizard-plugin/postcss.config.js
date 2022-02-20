const purgecss = require( '@fullhuman/postcss-purgecss' )( {
	content: [
		'./src/**/*.jsx',
		'./src/**/*.js',
	],
	defaultExtractor: content => content.match( /[\w-/:]+(?<!:)/g ) || [],
} );

module.exports = {
	plugins: [
		require( 'postcss-import' ),
		/**
		 * Whatever would go into `tailwind.config.js` can go right here, saving us another file.
		 *
		 * Any file size concerns get taken care of by `purgecss`, below, so we just load everything here.
		 *
		 * @link https://tailwindcss.com/docs/configuration/
		 */
		require( 'tailwindcss' )( {
			theme: {
				extend: {
					colors: {
						wpAdmin: {
							bg: '#e5e5e5', // light gray
							default: {
								primary: '#00a0d2', // blue
							},
							light: {
								primary: '#04a4cc', // blue
							},
							blue: {
								primary: '#52accc', // soft blue
							},
							coffee: {
								primary: '#59524c', // moss green
							},
							ectoplasm: {
								primary: '#523f6d', // orange
							},
							midnight: {
								primary: '#363b3f', // red
							},
							ocean: {
								primary: '#738e96', // tan
							},
							sunrise: {
								primary: '#cf4944', // mustard
							},
						},
					},
				},
			},
		} ),
		/**
		 * Everything but `postcss-import` (if we were to use it) goes after Tailwind.
		 *
		 * @link https://tailwindcss.com/docs/using-with-preprocessors/#nesting
		 */
		require( 'postcss-nested-ancestors' ),
		require( 'postcss-nested' ),
		require( 'postcss-advanced-variables' )( {
			'variables': {
				'plugin-slug': 'rankbear',
			},
		} ),
		/**
		 * Don't run during HMR, as it's extra overhead and we noticed some troubles (changes not detected).
		 *
		 * Should always be the last to run, other than autoprefixer.
		 *
		 * Tailwind has colons in class names but they never end with a colon.
		 * By default, PurgeCSS does not consider special characters such as @, :, /
		 *
		 * @link https://tailwindcss.com/docs/controlling-file-size/#removing-unused-css
		 * @link https://purgecss.com/extractors.html#default-extractor
		 * @link https://regex101.com/r/BmADqx/2 Regex testing (confirms can't end with a colon).
		 */
		...process.env.NODE_ENV === 'production'
			? [ purgecss ]
			: [],
	],
};