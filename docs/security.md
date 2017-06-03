Security

Preventing XSS
We use the appropriate <a href="https://twig.sensiolabs.org/doc/2.x/filters/escape.html" target="_blank">Twig escape filter</a> for any user-input data* that is output through Twig. Note that Twig defaults to autoescape 'html' in the autoescape environment variable: https://twig.sensiolabs.org/api/2.x/Twig_Environment.html

We call It_All\BoutiqueCommerce\Utilities\protectXSS or It_All\BoutiqueCommerce\Utilities\arrayProtectRecursive for any user-input data* that is output into HTML independent of Twig.

*Note this includes database data that has been input by any user, including through the admin

CSRF
We use Slim Framework CSRF protection middleware:
https://github.com/slimphp/Slim-Csrf

Authentication
Admin pages are protected through authenticated sessions.

Authorization
Admin pages and functionality can be protected for unauthorized use based on administrative roles.