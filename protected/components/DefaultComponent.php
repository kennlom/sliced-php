<?php

    namespace App;


    /**
	* Class DefaultComponent
    *
    * @package App
    * @copyright   Copyright (c) 2017 Oanh, Inc. (http://www.updateflow.com)
    * @license     http://www.updateflow.com/sliced-php/license
    * @see         http://www.updateflow.com/sliced-php
    */
	class DefaultComponent extends Controller
    {
        /**
         * Execute component: /default/hello
         */
		public function executeHello()
        {
			$this->render('components/index', false);
		}
	}
