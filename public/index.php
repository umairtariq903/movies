<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Respect\Validation\validator As v;

require_once __DIR__ . '/../vendor/autoload.php';
require 'src\Models\db.php';
require 'src\Validation\validation.php';
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);


require_once 'src\controllers\api.php';


$app->run();