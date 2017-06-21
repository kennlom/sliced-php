<?php

    namespace App;



    /**
     * Class HelloController
     * http://www.yourdomain.com/hello
     *
     * @package App
     * @copyright   Copyright (c) 2017 Oanh, Inc. (http://www.updateflow.com)
     * @license     http://www.updateflow.com/sliced-php/license
     * @see         http://www.updateflow.com/sliced-php
     */
    class HelloController extends Controller
    {
        /**
         * Hello Page
         * @link http://www.yourdomain.com/hello
         */
	public function executeIndex()
        {
            // Put your code here
            // ..

            $this
                ->setTitle(Config::$WebsiteTitle)
                ->set('greeting', 'Hello World!')
                ->render('hello/world');
        }

        /**
         * Simple routing for /hello/about
         * @link http://www.yourdomain.com/hello/about
         */
        public function executeAbout()
        {
            $this
                ->setTitle(Config::$WebsiteTitle)
                ->set('greeting', "We're inside /home/about!")
                ->render('hello/about');
        }
    }
