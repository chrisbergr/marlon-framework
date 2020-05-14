module.exports = function (grunt) {
	'use strict';

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		less: {
			style: {
				options: {
					compress: false,
					sourceMap: true
				},
				files: {
					'./assets/marlon-framework.min.css': './warehouse/less/style.less'
				}
			},
			deploy_style: {
				options: {
					compress: false,
					plugins: [
						new(require('less-plugin-clean-css'))({
							'advanced': true
						})
					],
					sourceMap: false
				},
				files: {
					'./assets/marlon-framework.min.css': './warehouse/less/index.less'
				}
			}
		},

		uglify: {
			javascript: {
				options: {
					beautify: true,
					preserveComments: true,
					wrap: 'marlon',
					sourceMap: true,
					compress: {
						drop_console: false
					},
					output: {
						quote_style: 1
					},
					mangle: false,
				},
				files: {
					'./assets/marlon-framework.min.js': [
						'.warehouse/javascripts/index.js'
					]
				}
			},
			deploy_javascript: {
				options: {
					beautify: true,
					preserveComments: false,
					wrap: 'marlon',
					sourceMap: false,
					compress: {
						drop_console: true
					},
					output: {
						quote_style: 1
					},
					mangle: true,
					omangle: {
						properties: true
					}
				},
				files: {
					'./assets/marlon-framework.min.js': [
						'.warehouse/javascripts/index.js'
					]
				}
			}
		}

	});

	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-text-replace');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('deploy', [
		'less:deploy_style',
		'uglify:deploy_javascript'
	]);

	grunt.registerTask('build', [
		'less:style',
		'uglify:javascript'
	]);

	grunt.registerTask('default', ['build']);

};
