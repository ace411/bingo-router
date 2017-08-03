# Bingo Router

The Bingo Router is yet another Bingo micro-service, an application router that maps URL query strings, routes, onto controllers and methods. The subsequent text is documentation of the library and should help you, the reader, understand how to go about using it.

## Installation

Before you can use the bingo-router software, you should have either Git or Composer installed on your system of preference. To install the package via Composer, type the following in your preferred command line interface:

```composer require chemem/bingo-router```

To install via Git, type:

```git clone https://github.com/ace411/bingo-router.git```

## Rationale

The ethos of routing is conspicuous; mapping URL strings onto **controller** classes completes the MVC model. The bingo-router is similar to the default Router in the [Bingo Framework](https://github.com/ace411/bingo-router). The only difference between this library and the service included in the large framework is the reliance on functional programming.

## Usage

The library is a simple router which enables those who intend to use it, the ability to map URLs onto controllers and the methods defined in them. It is important to note that this mapping is done on a **one-to-one** basis.

### Adding routes to the route table

In order to use the library to effectively map URLs onto controllers, you must first create a route table. The RouteTable functor, eponymous with the function, enables you to do just that. This class essentially stores route templates against which matches will be made. A simple table could look like this:

```php
require __DIR__ . '/vendor/autoload.php';

use Chemem\Bingo\Router\RouteTable;

$table = RouteTable::add([
    '{controller}/{action}',
    '{controller}/{action}/{id:\d+}'
]);
```

The pattern matching algorithm used by the library only supports usage of the keywords ```controller``` and ```action``` to go with any arbitrary query parameters which can be assigned pattern definitions. The snippet of code above shows the ```id``` parameter with a specified digit pattern.

### Matching routes specified in URL query string

Onto the Router class, another functor whose purpose is to compare given query strings with specified routes so as to return the required controller. Fashion a script to use this tool like so:

```php
require __DIR__ . '/vendor/autoload.php';

use Chemem\Bingo\Router\Router;
use Chemem\Bingo\Router\RouteTable;

$router = Router::add($_SERVER['QUERY_STRING']) //this works if you are using a web server
    ->match($table) //the table defined in the example above
    ->dispatch(RouteTable::add([
        'namespace' => 'Your\\Controller\\Class\\Namespace\\'
    ])); //namespace and optional constructor arguments
```

#### Passing parameters to a controller

The route-resolving mechanism used by the library does not discriminately distinguish between values specified in a query string and those passed via the dispatch function. All values meant for use inside the scope of a defined controller will feature in the controller's constructor. The corollary is this: accessing custom values is done via custom index - route table parameter identifiers for URL arguments and Collection functor keys for values passed via the dispatch method.

```php
$router = Router::add('your/query/string') //could hypothetically be 'controller/index/12'
    ->match($table) //contains a parameter tagged with the index 'id'
    ->dispatch(
        RouteTable::add([
            'namespace' => 'Your\\Controller\\Class\\Namespace\\'
        ]),
        Collection::add([
            'foo' => 'bar',
            'baz' => 12
        ]) //arbitrary values passed directly to constructor
    );
```

The controller for the conveyed implementation could look like this:

```php
namespace Your\Controller\Class\Namespace;

class Controller implements Chemem\Bingo\Router\Common\RouterControllerInterface
{
    use Chemem\Bingo\Router\Common\RouterControllerTrait;

    private $values;

    public function __construct($values)
    {
        $this->values = $values;
    }

    public function index()
    {
        return isset($this->values['id']) ? $this->values['id'] : 'index';
    }

    public function get()
    {
        return $this->values;
    }
}
```

#### Creating controllers for your application

Controllers are links between Views and Models. They take input and transform it into either Models or Views. If you intend to write a Controller with the bingo-router, create a class that uses the ```RouterConstructTrait``` and implements the ```RouterControllerInterface```. The implication of the latter criterion is this: the ```index()``` and ```get()``` methods specified in the design contract have to feature in your classes. You can use the example shown above or check out the [sample controller](https://github.com/ace411/bingo-router).  


#### Reminders and other important notes

- The index method defined in the base controller interface is the default method used if a method argument is absent in a URL query string. For instance, a query string controller defaults to the logic defined in the index method in the Controller class.

- The Collection functor only accepts arrays with string indexes.
