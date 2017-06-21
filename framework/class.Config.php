<?php

    namespace App;



    /**
     * Class Config
     *
     * This config file is in .php format for optimal performance especially when using a php bytecode cache.
     *
     * Important: Only turn on 'EnableGzip' if zlib.output_compression is Off in your php.ini setting.
     * With zlib.output_compression and EnableGzip on at the same time, you will be wasting your cpu
     * resources and output may not result in what is expected.
     *
     * @package App
     * @copyright   Copyright (c) 2017 Oanh, Inc. (http://www.updateflow.com)
     * @license     http://www.updateflow.com/sliced-php/license
     * @see         http://www.updateflow.com/sliced-php
     */
    class Config
    {
        // Turn on this variable to take site offline
        public static $IsOffline           = false;

        public static $EnableGzip          = false;
        public static $MinifyOutput        = false;
        public static $DisplayWarnings     = false;

        public static $frameworkDir        = '/home/slicedphp';
        public static $frameworkLibDir     = '/home/slicedphp/lib/';
        public static $layoutPath          = '/home/slicedphp/protected/layouts/';
        public static $templatePath        = '/home/slicedphp/protected/views/';
        public static $partialPath         = '/home/slicedphp/protected/partials/';

        public static $WebsiteTitle        = 'Hello World';

        // Define directories where the autoloader will scan for files
        public static $autoloadDirs = array(
            array('dir'=> '/home/slicedphp/protected/controllers/',	'prefix'=>''),
            array('dir'=> '/home/slicedphp/protected/components/', 	'prefix'=>''),
            array('dir'=> '/home/slicedphp/protected/models/', 		'prefix'=>'model.'),
            array('dir'=> '/home/slicedphp/protected/classes/', 	'prefix'=>'class.'),
            array('dir'=> '/home/slicedphp/framework/', 		    'prefix'=>'class.')
        );



    }
