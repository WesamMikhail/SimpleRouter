# SimpleRouter

This repository is actually made of two simple classes found in /src. **Map** and **Node**.
Basically, you pre-define your routes to your map class and ask it to match incoming requests based on those pre-defined routes.
If you would like to use RESTful URL arguments, this library allows for that. However, Regex is not currently supported.

## Step by Step:

#### Run composer install and add this to your index.php

```
if(is_readable('vendor/autoload.php'))
    require_once 'vendor/autoload.php';
```

#### Use the library to define your routes

```
use Lorenum\SimpleRouter\Map;

$map = new Map();
$map->get("/", "indexController@indexAction");
$map->get("/article", "articleController@indexAction");
$map->add(MAP::METHOD_GET,  "/article/:",            "articleController@test");
$map->add(MAP::METHOD_GET,  "/article/:/thumb",      "articleController@thumb");
$map->add(MAP::METHOD_PUT,  "/article/:/summary",    "articleController@summaryFunc");
$map->add(MAP::METHOD_POST, "/article/:/summary",    "articleController@addsummaryAction");
```

#### Match with the incoming user request

```
$match = $map->match(MAP::METHOD_POST, "/article/15/summary");
```

**$match** will now contain

```
Array
(
    [controller] => articleController
    [action] => addsummaryAction
    [params] => Array
        (
            [0] => 15
        )

)
```

you can use this info to instanciate the controller and launch the action with the params.

NOTE: The params are included in the order they come in the url


# TODO

- Add tests