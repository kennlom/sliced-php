<?php

	namespace App;

    spl_autoload_register(array('App\Engine', 'Autoload'));




    /**
     * Framework Engine
	 *
     * @package     App
     * @copyright   Copyright (c) 2017 Oanh, Inc. (http://www.updateflow.com)
     * @license     http://framework.updateflow.com/license
     * @see         http://framework.updateflow.com/
     */
	class Engine
	{
		public static $AutoloadCache;
        public static $SessionData;		# unserialized array
		public static $SessionStarted;	# (true | false)

		/**
         * Engine constructor
         */
		public function __construct() {
		}

        /**
		 * Autoloader
         *
         * @param $class
         */
		public static function Autoload($class)
		{
			// Remove namespace from class name
			$class = str_replace(__NAMESPACE__.'\\', '', $class);

			# Get the autoload config cache (to avoid directory lookup)
			$AutoloadCache = self::GetAutoloadCache();

			if($AutoloadCache && isset($AutoloadCache[$class]))
			{
				require($AutoloadCache[$class]);
				return true;
			}
			else
			{
                /*``
                ` There is no cache, we have to do the slow method of checking each directory
                ` for the file we need. Check for autoloading to framework related classes first
                ````````````````````````````````````````````````````````````````````````````*/;
				foreach(Config::$autoloadDirs as $directory)
				{
					if(is_file("{$directory['dir']}/{$directory['prefix']}{$class}.php")) {
						require("{$directory['dir']}/{$directory['prefix']}{$class}.php");
						return true;
						break;
					}
				}
			}
		}

		/**
         * Load configuration file
         */
		private static function LoadConfig() {
			require_once(dirname(__FILE__) .'/class.Config.php');
		}

		/**
		* Initialize
		*/
		public static function Init()
		{
			# Load Config
			self::LoadConfig();

			# Get the autoload config cache (to avoid directory lookup)
			$AutoloadCache = Engine::GetAutoloadCache();

			# Load required core controllers
			require($AutoloadCache['Controller']);
		}

        /**
		 * Get autoload cache
		 *
         * @return mixed
         */
		public static function GetAutoloadCache()
		{
			# The autoload cache is stored in /cache/autoload.php
			# The file can be generated automatically by running 'build_autoload_cache.php' in /batch
			# Make sure you specify all your autoload directories in config.php
			if(isset(self::$AutoloadCache))
				return self::$AutoloadCache;

			$autoload = require_once(Config::$frameworkDir .'/cache/autoload.php');

			self::$AutoloadCache = $autoload;
			return $autoload;
		}

        /**
		 * Render final layout
		 *
         * @param $controller
         * @param $layout
         * @param $template
         */
		public static function render($controller, $layout, $template) {
			header('Content-type: text/html; charset=UTF-8');

			if($template) $controller->pageTemplate = Config::$templatePath.$template.'.php';
			$parent = &$controller;

			if($layout)			require(Config::$layoutPath.$layout.'.php');
			elseif($template)	require(Config::$templatePath.$template.'.php');
		}
		
        /**
		 * Get file content
		 *
         * @param       $filepath
         * @param array $variables
         */
		public static function load($filepath, $variables=array()) {
			extract($variables);
			include($filepath);
		}
		
        /**
		 * Render partial
		 *
         * @param       $filepath
         * @param array $variables
         */
		public static function partial($filepath, $variables=array()) {
			self::load(Config::$partialPath.$filepath, $variables);
		}
		
        /**
		 * Render component
		 *
         * @param $path
         */
		public static function component($path)
		{
			$path	= explode('/', trim($path, '/'));
			$module	= @$path[0] ? $path[0] : 'Main';
			$action	= @$path[1] ? $path[1] : 'Index';
	
			$componentName	= self::getCleanName($module).'Component';
			$actionName		= self::getCleanName($action);

			# unset($path[0], $path[1]);
			# $vars = array_values($path);
			$vars = false;

			if(self::class_exists($componentName, true)) {
				$component = new $componentName;
				$component->setArguments($vars)->forward($actionName);
			} else {
				throw new Exception('Call to an unknown component');
			}
		}

        /**
		 * Get clean module name
		 *
         * @param $name
         * @return string
         */
		private static function getCleanName($name)
		{
			$cleanName	= '';
			$name 		= explode('_', $name);
			
			foreach($name as $part) {
				$cleanName .= ucwords(strtolower($part));
			}

			return $cleanName;
		}

        /**
		 * Check to see if class exists / loaded
         * Implemented this way for higher performance
		 *
         * @param      $class
         * @param bool $autoload
         * @return bool
         */
		public static function class_exists($class, $autoload=false)
		{
			# List of all classes already loaded
			static $_loadClasses = array();

			if(isset($_loadClasses[$class]))
				return true;

			// Let's attempt to load the class
			if($autoload === true)
				return self::Autoload($class);

			// We didn't load the class
			return false;
		}
		
        /**
         * Start session
         * @todo: possibly want to re-visit this when we want to store session data in Redis
         */
		public static function StartSession()
		{
			# Using php session for now until we decide to switch to something else
			# session_start();
			# session_write_close();

			if(! self::$SessionStarted)
			{
                session_start();

                self::$SessionData      = $_SESSION;
                self::$SessionStarted   = true;

                /*
				try
				{		
					$sessionId = $_COOKIE['@'];

					if($sessionId)
					{
						$redis = new Redis();
						$redis->pconnect('127.0.0.1', 6379);
						$data = $redis->get('@'.$sessionId);

						// Save the session data for this instance
						self::$SessionData = unserialize($data);
					}
					else
					{
						$sessionId = uniqid('', true);
						setcookie('@', $sessionId, time()+60*60*24*30, '/', '.rookiepro.com');
					}

				} catch(Exception $e){
					// handle error
				}
                */
			}
		}
		
        /**
         * Save session
         * @todo: possibly want to re-visit this when we want to store session data in Redis
         */
		public static function SaveSession()
		{
		    /*
			$sessionId = $_COOKIE['@'];

			// Only save if we have a valid session id
			if($sessionId)
			{
				$redis = new Redis();
				$redis->pconnect('127.0.0.1', 6379);
				$redis->set('@'.$sessionId, serialize(self::$SessionData));
			}
		    */
            foreach (self::$SessionData as $name => $value)
            {
                $_SESSION[$name] = $value;
		    }
		}

        /**
         * End session
         */
		public static function EndSession()
		{
		    /*
			$sessionId = $_COOKIE['@'];

			if($sessionId)
			{
				$redis = new Redis();
				$redis->pconnect('127.0.0.1', 6379);
				$redis->delete('@'.$sessionId);

				setcookie('@', '', time()-86400);
			}
		    */

			# Using php session for now until we decide to switch to something else
			session_destroy();
		}

        /**
		 * Get url requested path info
		 *
         * @return mixed
         */
		public static function GetPathInfo() {
			# Since web servers are inconsistence at providing the $_SERVER['PATH_INFO'] variable,
			# we are going to use the $_SERVER['REQUEST_URI'] variable and parse it down.
			# This method seems to be more reliable as apache / lighttpd / nginx can all produce different
			# results for PATH_INFO, or none at all.
			$uri = explode('?', $_SERVER['REQUEST_URI']); # split by ?
			return $uri[0];
		}

        /**
         * Run engine
         */
		public static function run()
		{
			//$startTime = microtime(true);
			self::Init();
			self::StartSession();


			if(Config::$IsOffline) {
				(new MainController)->forward('offline'); return;
			}

			if(Config::$DisplayWarnings == false)
				error_reporting(E_ERROR);




			ob_start();
			//header('Content-type: text/html; charset=UTF-8');

			/*``
			` Determine module and action
			`````````````````````````````````````````````````````*/
			$path	= explode('/', trim(self::GetPathInfo(), '/'));
			$module	= @$path[0] ? $path[0] : 'Main';
			$action	= @$path[1] ? $path[1] : 'Index';
	
			$controllerName = self::getCleanName($module).'Controller';
			$actionName		= self::getCleanName($action);

			unset($path[0], $path[1]);
			$vars = array_values($path);


			/*``
			` Forward to the proper controller to handle request
			`````````````````````````````````````````````````````*/
			if(self::class_exists($controllerName, true))
			{
				$class = __NAMESPACE__  . "\\$controllerName";
				(new $class)->setArguments($vars)->forward($actionName);

			} else {
				//-self::class_exists('MainController', true);
				MainController::this()->forward('404');	
			}


			/*``
			` Get the output buffer so we can minify it
			`````````````````````````````````````````````````````*/
			$html = ob_get_clean();

			# Enable gzip compression on the php side
			if(Config::$EnableGzip && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
				ob_start('ob_gzhandler');
			} else
				ob_start();


			echo self::minify($html, Config::$MinifyOutput);
			ob_end_flush();


			/*``
			` Print out page execution time
			`````````````````````````````````````````````````````*/
			// echo sprintf('%f', (microtime(true)-$startTime));
		}

        /**
		 * Minify output
         * @param      $source
         * @param bool $run
         * @return mixed|string
         */
		public static function minify($source, $run=true)
		{
			if($run == false) return $source;



			# Removing comments is only used for bandwidth and page load performance and not required
			# If removing html comments is causing problems for you, you can disable this
			$source = self::RemoveComments($source);


			# For more info check out:
			# http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
			
			// Set PCRE recursion limit to sane value = STACKSIZE / 500
			// ini_set("pcre.recursion_limit", "524"); // 256KB stack. Win32 Apache
			ini_set("pcre.recursion_limit", "16777");  // 8MB stack. *nix		
		
		    $regex = '%# Collapse whitespace everywhere but in blacklisted elements.
		        (?>             # Match all whitespans other than single space.
		          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
		        | \s{2,}        # or two or more consecutive-any-whitespace.
		        ) # Note: The remaining regex consumes no text at all...
		        (?=             # Ensure we are not in a blacklist tag.
		          [^<]*+        # Either zero or more non-"<" {normal*}
		          (?:           # Begin {(special normal*)*} construct
		            <           # or a < starting a non-blacklist tag.
		            (?!/?(?:textarea|pre|script)\b)
		            [^<]*+      # more non-"<" {normal*}
		          )*+           # Finish "unrolling-the-loop"
		          (?:           # Begin alternation group.
		            <           # Either a blacklist start tag.
		            (?>textarea|pre|script)\b
		          | \z          # or end of file.
		          )             # End alternation group.
		        )  # If we made it here, we are not in a blacklist tag.
		        %Six';
		    $source = preg_replace($regex, " ", $source);
		    
		    #if ($input === null) exit("PCRE Error! File too big.\n");
		    return $source;
		}

        /**
		 * Remove HTML comments
		 *
         * @param $html
         * @return string
         */
		public static function RemoveComments($html)
		{
			$dom = new DOMDocument;
			$dom->loadHtml($html);

			$xpath = new DOMXPath($dom);
			foreach ($xpath->query('//comment()') as $comment) {
			    $comment->parentNode->removeChild($comment);
			}

			$html 	= $xpath->query('/')->item(0);
			$output = $html instanceof DOMNode ? $dom->saveHTML($html) : '';

			return $output;
		}



	}