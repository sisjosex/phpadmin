module.exports = function(grunt) {  
	var general_options={
					banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd HH:MM:ss") %> */\n',
                    mangle: true,
					compress: {
						drop_console: true
					}
				};
	// Configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat : { js : {} },
		uglify: {
			framework:{
				options: general_options,
				files : {					
                    'js/framework.min.js' : ['js/framework.js'],
                    'lib/daterangepicker/jquery.daterangepicker.min.js' : ['lib/daterangepicker/jquery.daterangepicker.js'],
                    'lib/dropzone/dropzone.min.js' : ['lib/dropzone/dropzone.js'],
                    'lib/simplePagination/simplePagination.min.js' : ['lib/simplePagination/simplePagination.js']
				}
			}
		},
		jshint: {
			general: [
				'Gruntfile.js'
			],
			framework: [
				'js/framework.js'
			]
		},
        cssmin: {
            combine: {
                files: {
                    'css/login.min.css': ['css/login.css'],
                    'css/style.min.css': ['css/style.css'],
                    'lib/daterangepicker/daterangepicker.min.css': ['lib/daterangepicker/daterangepicker.css'],
                    'lib/dropzone/dropzone.min.css' : ['lib/dropzone/dropzone.css'],
                    'lib/simplePagination/simplePagination.min.css' : ['lib/simplePagination/simplePagination.css']
                }
            }
        }
	});

	// Load required plugins
	grunt.loadNpmTasks( 'grunt-contrib-concat' );

	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.loadNpmTasks('grunt-contrib-jshint');

    grunt.loadNpmTasks('grunt-contrib-cssmin');

	// Tasks
	grunt.registerTask('default', ['uglify','jshint','concat']);		
};