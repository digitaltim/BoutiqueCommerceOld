Architecture
	Uses PHP7, Slim 3 microframework, CSS Grid, PostgreSQL. Features of each will be described in detail.
	Use declare(strict_types=1); in all files that we generate (ie not vendor files)
	Namespaces use directory path conventions i.e. "It_All\BoutiqueCommerce\..."
	Uses <a href="https://github.com/slimphp/Slim-Csrf">Slim Framework CSRF Protection</a>.
	Bootstrap Process:
	   Loads index.php which calls init.php. The result of the array_merge creates a $config array. Chosen config items are then stored into Slim's container settings. A lot happens just in these two files upon startup.
	
    Base HTML Template /ui/views/base.twig
    Generally based on https://twig.sensiolabs.org/doc/2.x/tags/extends.html
    For meta http-equiv="X-UA-Compatible" content="IE=edge, see http://stackoverflow.com/questions/6771258/what-does-meta-http-equiv-x-ua-compatible-content-ie-edge-do
    
    The robots meta tag only needs to be output in admin pages: http://www.robotstxt.org/meta.html
    
    NOTE: every front end that needs it's content to be accessed by search engines page MUST send the variable pageType => 'public' to Twig in the render call. Note also this should not be done by the cart, checkout, and customer account pages.
    