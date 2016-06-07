module.exports = function (grunt) {
    "use strict";

    var TwineV2Skin;

    var resourcesPath = 'skins/twine-v2/';

    TwineV2Skin = {
        'scss':     ['skins/twine-v2/scss/**/*.scss']
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            TwineV2SkinScss: {
                files: TwineV2Skin.scss,
                tasks: ['sass', 'cssmin']
            },
            livereload: {
                files: ['skins/twine-v2/*.css'],
                options: {
                    livereload: true
                }
            }
        },

        sass: {
            TwineV2Skin: {
                options: {
                    style: 'compressed'
                },
                files: [{
                    'skins/twine-v2/.temp/css/editor.css': 'skins/twine-v2/scss/components/editor.scss',
                    'skins/twine-v2/.temp/css/dialog.css': 'skins/twine-v2/scss/dialog/dialog.scss',
                    'skins/twine-v2/.temp/css/editor_ie.css': 'skins/twine-v2/scss/browser-specific/ie/editor_ie.scss',
                    'skins/twine-v2/.temp/css/dialog_ie.css': 'skins/twine-v2/scss/browser-specific/ie/dialog_ie.scss',
                    'skins/twine-v2/.temp/css/editor_ie8.css': 'skins/twine-v2/scss/browser-specific/ie8/editor_ie8.scss',
                    'skins/twine-v2/.temp/css/dialog_ie8.css': 'skins/twine-v2/scss/browser-specific/ie8/dialog_ie8.scss',
                    'skins/twine-v2/.temp/css/editor_ie7.css': 'skins/twine-v2/scss/browser-specific/ie7/editor_ie7.scss',
                    'skins/twine-v2/.temp/css/dialog_ie7.css': 'skins/twine-v2/scss/browser-specific/ie7/dialog_ie7.scss',
                    'skins/twine-v2/.temp/css/editor_iequirks.css': 'skins/twine-v2/scss/browser-specific/iequirks/editor_iequirks.scss',
                    'skins/twine-v2/.temp/css/dialog_iequirks.css': 'skins/twine-v2/scss/browser-specific/iequirks/dialog_iequirks.scss',
                    'skins/twine-v2/.temp/css/editor_gecko.css': 'skins/twine-v2/scss/browser-specific/gecko/editor_gecko.scss',
                    'skins/twine-v2/.temp/css/dialog_opera.css': 'skins/twine-v2/scss/browser-specific/opera/dialog_opera.scss'
                }]
            }
        },

        cssmin: {
            TwineV2Skin: {
                files: {
                    'skins/twine-v2/editor.css': [
                        'skins/twine-v2/.temp/css/editor.css'
                    ],
                    'skins/twine-v2/dialog.css': [
                        'skins/twine-v2/.temp/css/dialog.css'
                    ],
                    'skins/twine-v2/editor_ie.css': [
                        'skins/twine-v2/.temp/css/editor_ie.css'
                    ],
                    'skins/twine-v2/dialog_ie.css': [
                        'skins/twine-v2/.temp/css/dialog_ie.css'
                    ],
                    'skins/twine-v2/editor_ie8.css': [
                        'skins/twine-v2/.temp/css/editor_ie8.css'
                    ],
                    'skins/twine-v2/dialog_ie8.css': [
                        'skins/twine-v2/.temp/css/dialog_ie8.css'
                    ],
                    'skins/twine-v2/editor_ie7.css': [
                        'skins/twine-v2/.temp/css/editor_ie7.css'
                    ],
                    'skins/twine-v2/dialog_ie7.css': [
                        'skins/twine-v2/.temp/css/dialog_ie7.css'
                    ],
                    'skins/twine-v2/editor_iequirks.css': [
                        'skins/twine-v2/.temp/css/editor_iequirks.css'
                    ],
                    'skins/twine-v2/dialog_iequirks.css': [
                        'skins/twine-v2/.temp/css/dialog_iequirks.css'
                    ],
                    'skins/twine-v2/editor_gecko.css': [
                        'skins/twine-v2/.temp/css/editor_gecko.css'
                    ],
                    'skins/twine-v2/dialog_opera.css': [
                        'skins/twine-v2/.temp/css/dialog_opera.css'
                    ]
                }
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask('default', ['watch']);
    grunt.registerTask('build', ['sass', 'cssmin']);
};
