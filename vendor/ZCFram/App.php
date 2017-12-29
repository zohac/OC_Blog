<?php
namespace ZCFram;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;

/**
 * [App description]
 */
class App
{
    protected $appName;
    protected $moduleName;
    protected $params;
    protected $request;
    protected $reponse;

    public function __construct()
    {
        $this->request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
        $this->reponse = new Response();
    }

    public function run()
    {
        $uri = $this->request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === '/' && strlen($uri) > 1) {
            $this->reponse = (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        } else {
            $controller = $this->getController();
        }
    }

    /**
     * [getController description]
     * @return [instance d'une classe controller]
     */
    public function getController()
    {
        $routeFromXML = realpath(__DIR__.'/../../app/config/routes.xml');

        $router = new Router($routeFromXML);

        try {
            $router->match($this->request->getUri()->getPath());
            // On ajoute les variables de l'URL au tableau $_GET.
            $_GET = array_merge($_GET, $router->getVars());

            // On instancie le contrôleur.
            $controllerClass = 'app\\'.$router->getApp().'\\Modules\\'.$router->getModule().'\\'.$router->getModule().'Controller';

            return new $controllerClass();
        } catch (\RuntimeException $e) {
            if ($e->getCode() == Router::NO_ROUTE) {
                // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
                $this->reponse = ( new Response(404, [], $e->getMessage()));
            }
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->reponse;
    }
}
