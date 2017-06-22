# sliced-php
Sliced PHP is a super lightweight and performance oriented PHP MVC framework with routing, database orm, templating, and autoloading capabilities. The entire framework is under 15KB including sample code. Despite it's size, the framework have been used as the core for many high performance projects including CRMs, Kelly BlueBook, and Toyota Care.

## Getting Started
To start using sliced-php, upload the repository to your server and edit the configurations in `class.Config.php`. Update the path to where you saved the repository.
```
class Config
{
  public static $frameworkDir        = '/home/slicedphp';
  public static $frameworkLibDir     = '/home/slicedphp/lib/';
  public static $layoutPath          = '/home/slicedphp/protected/layouts/';
  public static $templatePath        = '/home/slicedphp/protected/views/';
  public static $partialPath         = '/home/slicedphp/protected/partials/';
}
```

## Basic Requirements
Much like other MVC framework, Sliced PHP require you to configure your server or hosting to pass all requests to `/index.php`. `/index.php` contains the framework's engine bootstrap and is the starting point for all requests.

## File and Directory Info
```
/framework
  ./class.Controller.php    :: parent controller class for actions
  ./class.DB.php            :: database class, MySQL  
  ./class.Engine.php        :: the main engine doing all the hard work

/protected
  /components
    ./DefaultComponent.php  :: optional, this is just another controller which cannot be accessed by any url.
    ./MoreComponent.php     :: You can use a component by calling: Engine::component('/ComponentName/Action');
                            :: optionally add more components as needed
  /controllers
    ./MainController.php    :: Main is the default action controller. Index will always be the default action.
    ./MoreController.php    :: You can add more action controllers to this directory. The name must end in
                            :: *Controller.php. Action controller handles url request. For example, if you were to
                            :: access www.domain.com/about, the engine will execute the AboutController.php executeIndex()
                            :: function to handle the request.
                            :: optionally add more controllers as needed
  /layouts
    ./default.php           :: default.php is the default layout for all templates. You can change the layout inside of
                            :: the controller by calling $this->setLayout('LayoutName.php');
  /models
    ./model.TableModel.php  :: Database model that inherits from the DB class. Using the model allows you to load, save,
                            :: and connect to the database with ease. You are not required to use the model.
                            :: Write your own query if you must.
  /partials
    /folder/name.php        :: Indentical to the component, a partial does not handle url request or have any action.
    ./name.php              :: If you use a piece of static code in alot of places, it's nice to have them in the
                            :: partial and call Engine::partial('FolderName/PartialName.php');
                            :: or Engine::partial('PartialName.php');
  /views
    ./default.php           :: controllers set the view and view handles the display of a url request.
    ./offline.php           :: There are 3 default views. default.php. 404.php, and offline.php
    ./404.php               :: optionally add more view as needed. Views can be set in the controller by calling
    /folder/name.php        :: $this->render('folder/name');

  /batch
    ./script.php            :: optionally, place all your batch and cron scripts here to keep it outside of the public
    
  /public                   :: public folders, your domain root should be pointed to the /public directory so people
    /css                    :: cannot access the framework and protected directory containing your code base.
    /img
    /js
    ./index.php

```

## Controller

### Creating your first index page
The `MainController` handle all requests sent to your root domain `yourdomain.com/`
```
class MainController extends Controller
{
    public function executeIndex()
    {
        // place your code here
        // ...
    
        $this
          ->setTitle('Your Website | Home')
          ->set('variable1', 'It Works!')
          ->set('variable2', 'Simple')
          ->render('default');
    }
}
```
- Setting the page title
`$this->setTitle('...');`
- Setting variables you want to be able to access in the html template
`$this->set('var1', '...');` 
- Finally, rendering the page using the template name
`$this->render('template_name');`

### Creating your 2nd and 3rd Hello and About pages
You can create more controllers to handle additional pages. Keeping the controllers seperate for each page help make your code easier to read and maintain. The `HelloController` handle all requests coming to `/hello` and `/hello/about`.
```
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
```


### Other things you can do inside the controller

*Redirecting to another page*
```
$this->redirect('/about');
```

*Forwarding to another controller to handle the request*
```
$this->forward('actionName');
```

*Rendering JSON data*
```
$this->renderJson(array(
    'test' => 123
));
```

*Setting the page title*
```
$this->setTitle('Page title');
```

*Passing variables from the controller to your template page*
```
$this->set('variable1', 'It Works!');
```

*Setting and using a different layout, default is default.php*
```
$this->setLayout('layout_name');
```
*Getting the request parameters, form fields submitted values, etc*
```
$this->getRequestParam('form_field_name');
```

# Views
When the controller calls `$this->render('template_name');`, all the variables you set using `$this->set('var','value');` will be immediately available to use in your view's html template. By rendering using `$this->render('hello/about');` the view template `/protected/views/hello/about.php` will be loaded and displayed to the user.

> /protected/views/hello/about.php
```
Welcome to Sliced PHP: {{greeting}}
```

### Other things you can do inside the view templates
*Echo or display the variable you set inside the controller*
```
Hi, your name is: <?= $variableName1 ?>
```
```
Hi, your name is: {{variableName1}}
```

*Include a partial with other commonly used html template you created*
```
<?php App\Engine::partial('header.php') ?>
```

*Include a component that has code execution before outputting a custom html view*
```
<?php App\Engine::component('customer_shopping_chart') ?>
```

