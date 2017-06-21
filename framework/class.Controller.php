<?php

    namespace App;



    /**
     * Class Controller
     *
     * @package App
     * @copyright   Copyright (c) 2017 Oanh, Inc. (http://www.updateflow.com)
     * @license     http://www.updateflow.com/sliced-php/license
     * @see         http://www.updateflow.com/sliced-php
     */
	class Controller
	{
		private $_layout	= 'default';
		private $_template	= 'default';
		private $_title		= '';
		private $_arguments	= array();
		private $_vars      = array();



        /**
         * Create and return new object instance
         *
         * @return static
         */
		public static function this() {
			return new static;
		}		

		public function setLayout($in=false) 		{ $this->_layout = $in; 	return $this; }		    # Set layout
		public function setTemplate($in=false)		{ $this->_template = $in; 	return $this; }		    # Set template
		public function setTitle($in='') 			{ $this->_title = $in; 		return $this; }		    # Set page title
		public function setArguments($in=array()) 	{ $this->_arguments = $in; 	return $this; }		    # Set request arguments
		public function getTitle() 					{ return $this->_title; } 						    # Get page title
		public function getRequestParam($name) 		{ return @$_REQUEST[$name]; } 					    # Get request parameter
        public function set($name, $value) 	        { $this->_vars[$name] = $value; return $this; }		# Set object variables
		public function get($name) 					{ return @$this->_vars[$name]; } 					# Get object variables
        public function getVariables()              { return $this->_vars; }

		public function setSession($name, $value)	{ Engine::$SessionData[$name] = $value; }
		public function removeSession($name)		{ unset($_SESSION[$name]); }

        /**
         * Get session value
         *
         * @param $name
         * @return mixed
         */
        public function getSession($name)
        {
            Engine::StartSession();
            return Engine::$SessionData[$name];
        }

        /**
         * Get request argument passed into the controller. /module/action/argument1/argument2/etc...
         *
         * @param int $i
         * @return mixed|null
         */
		public function getArgument($i=0) {
			return isset($this->_arguments[$i]) ? $this->_arguments[$i] : NULL;
		}
	
        /**
         * Forward to another action to handle the request
         *
         * @param       $action
         * @param array $args
         */
		public function forward($action, $args=array())
        {
			$actionName = "execute{$action}";

			if(method_exists($this, $actionName)) {
				$this->$actionName($args);
			}
			else
			{
				Engine::class_exists('MainController', true);

				// forward to 404 handler
				(new MainController)->execute404();
			}
		}
	
        /**
         * Redirect to another page
         *
         * @param $url
         */
		public function redirect($url) {
			header("Location: $url"); exit;
		}

        /**
         * Render view
         *
         * @param string $view
         * @param bool   $withLayout
         */
		public function render($view='', $withLayout=true) {
			Engine::render($this, $withLayout ? $this->_layout : false, $view ? $view : $this->_template);
		}

        /**
         * Render JSON
         *
         * @param $data
         */
		public function renderJSON($data) {
			header('Content-Type: application/json');
			echo json_encode($data);
		}



	}
