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
		image_path: '<%= public_path %>/images',

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
					'<%= js_src_path %>/*.js'
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
			files: ['<%= js_src_path %>'],
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
				files: ['<%= scss_src_path %>/**'],
				tasks: ['compass']
			},
			js: {
				files: ['<%= src_path %>/**/*.js'],
				tasks: ['jshint', 'concat', 'uglify']
			},
			options: {
				livereload: true
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

	// Default task
	grunt.registerTask('default', 'watch');
	grunt.registerTask('compilenohint', ['compass', 'concat', 'uglify']);
	grunt.registerTask('compile', ['compass', 'jshint', 'concat', 'uglify']);
};