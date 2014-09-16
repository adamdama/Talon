/**
 * Created by Adam on 02/09/14.
 */
module.exports = function (grunt) {

	grunt.initConfig({
		// Read package file
		pkg: grunt.file.readJSON('package.json'),

		// Define our source and build folders
		src_path: 'source',
		public_path: 'public',
		js_src_path: '<%= src_path %>/js',
		js_build_path: '<%= public_path %>/js',
		js_plugin_path: '<%= src_path %>/js/plugins',
		css_build_path: '<%= public_path %>/css',
		scss_src_path: '<%= src_path %>/scss',
		image_path: '<%= public_path %>/img',

		// Grunt Tasks
		compass: {
			dev: {
				options: {
					config: 'config.rb',
					force: true
				}
			}
		},
		concat: {
			options: {
				separator: ';',
				banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version + "\\n" %>' +
					'* <%= grunt.template.today("yyyy-mm-dd") + "\\n" %>' +
					'* <%= pkg.homepage + "\\n" %>' +
					'* Copyright (c) <%= grunt.template.today("yyyy") %> - <%= pkg.title %> */ <%= "\\n" %>'
			},
			site: {
				src: [
					'<%= js_src_path %>/plugins/*.js',
					'<%= js_src_path %>/talon/*.js'
				],
				dest: '<%= js_build_path %>/talon.js'
			}
// ,
//			plugins: {
//				src: [
//					'<%= js_plugin_path %>/jquerypp/jquerypp.custom.js'
//				],
//				dest: '<%= js_plugin_path %>/plugins.js'
//			}
		},
		uglify: {
			js: {
				options: {
					mangle: true,
					sourceMap: '<%= js_build_path %>/talon.map.js',
					sourceMapRoot: '../../../../../../../',
					sourceMappingURL: 'talon.map.js'
				},
				files: [
					{src: '<%= concat.site.dest %>',
						dest: '<%= js_build_path %>/talon.min.js'}
				]
			}
//			,
//			plugins: {
//				options: {
//					mangle: true
//				},
//				files: [
//					{src: '<%= concat.plugins.dest %>',
//						dest: '<%= js_plugin_path %>/plugins.min.js'}
//				]
//			}
		},
		jshint: {
			// define the files to lint
			files: ['<%= js_src_path %>/talon'],
			// configure JSHint (documented at http://www.jshint.com/docs/)
			options: {
				// more options here if you want to override JSHint defaults
				globals: {
					jQuery: true,
					console: true,
					module: true
				}
			}
		},
		watch: {
			sass: {
				files: ['<%= scss_src_path %>/**', '<%= src_path %>/img/icons/**'],
				tasks: ['svg-sprites', 'replace', 'compass']
			},
			js: {
				files: ['<%= js_src_path %>/**/*.js'],
				tasks: ['jshint', 'concat', 'uglify', 'copy']
			},
			options: {
				livereload: true
			}
		},
		'svg-sprites': {
			navigation: {
				options: {
					spriteElementPath: '<%= src_path %>/img/icons/navigation',
					spritePath: '<%= image_path %>/sprites/navigation.svg',
					cssPath: '<%= scss_src_path %>/_navigation-sprite.scss',
					prefix: 'nav-icon',
					layout: 'packed',
					cssUnit: "rem",
					sizes: {
						large: 24,
						small: 2
					},
					refSize: 'large'
				}
			}
		},
		copy: {
			vendor: {
				files: [
					// includes files within path
					{
						expand: true,
						flatten: true,
						src: ['<%= js_src_path %>/vendor/**'],
						dest: '<%= js_build_path %>',
						filter: 'isFile'
					}
				]
			}
		},
		replace: {
			main: {
				src: ['<%= scss_src_path %>/_*-sprite.scss'],
				overwrite: true,                 // overwrite matched source files
				replacements: [{
					from: /\.\.\/public\//g,
					to: ''
				}]
			}
		}
	});

	// Load plugins
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-dr-svg-sprites');
	grunt.loadNpmTasks('grunt-text-replace');

	/**
	 npm install grunt
	 npm install grunt-contrib-copy --save-dev
	 npm install grunt-contrib-concat --save-dev
	 npm install grunt-contrib-uglify --save-dev
	 npm install grunt-contrib-compass --save-dev
	 npm install grunt-contrib-watch --save-dev
	 npm install grunt-dr-svg-sprites --save-dev
	 npm install grunt-text-replace --save-dev
	 */

	// Default task
	grunt.registerTask('default', 'watch');
	grunt.registerTask('compilenohint', ['svg-sprites', 'replace', 'compass', 'concat', 'uglify', 'copy']);
	grunt.registerTask('compile', ['svg-sprites', 'replace', 'compass', 'jshint', 'concat', 'uglify', 'copy']);
};