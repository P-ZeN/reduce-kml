(function() {
    'use strict';
}());
module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            options: {
                separator: '\r\n'
            },
            dist: {
                src: [
                    'node_modules/popper.js/dist/umd/popper.min.js',
                    'node_modules/jquery/jquery.js',
                    'node_modules/bootstrap/dist/bootstrap.js',
                    'js/actions.js',
                ],
                dest: '../assets/reducekmlonline.js'
            }
        },

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\r\n',
                separator: '\r\n'

            },
            dist: {
                files: {
                    '../assets/reducekmlonline.js': ['<%= concat.dist.dest %>']
                }
            }
        },

        compass: {
            dist: {
                options: {
                    sassDir: 'scss',
                    cssDir: '../assets',
                    environment: 'production',
                    outputStyle: 'compressed'
                }
            }
        },

        watch: {
            options: {
                livereload: true,
            },
            css: {
                files: ['scss/**/*.scss'],
                tasks: ['concat', 'uglify', 'compass']
            },
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat'],
                options: {
                    spawn: false,
                },
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['concat', 'uglify', 'compass', 'watch']);
};