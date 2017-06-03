<?php

    namespace App;



    /**
     * Main Controller
     *
     * @package App
     * @copyright   Copyright (c) 2017 Beba, Inc. (http://www.updateflow.com)
     * @license     http://www.updateflow.com/sliced-php/license
     * @see         http://www.updateflow.com/sliced-php
     */
	class MainController extends Controller
	{
		/**
		 * Home Page
		 * @link http://www.yourdomain.com/
		 */
		public function executeIndex()
		{
            // Want to redirect? Sample redirect to /about
            // $this->redirect('/about');

            // Want to render json data?
            // $this->renderJson(array(
            //     'test' => 123,
            // ));

			$this
				->setTitle(Config::$WebsiteTitle)
				->set('variable1', 'It Works!')
                ->set('variable2', 'Simple')
                ->set('variable3', SampleClass::getGreeting()) // sample autoloaded class
				->render('default');
		}

		/**
		 * 404, missing page
		 * @link http://www.yourdomain.com/any
		 */
		public function execute404()
		{
			$this->setTitle('Page not found')->render('404');
		}

		/**
		 * Website Offline
		 * The website can be turned to offline mode for scheduled maintenance.
		 * @see /framework/config.php ($IsOffline)
		 * @link http://www.yourdomain.com/
		 */	
		public function executeOffline() {
			$this->setTitle('Currently Offline')->render('offline');
		}



	}