<?php declare (strict_types=1);
    namespace MangaSekai\API\Routing;

    /**
     * Simple resolver class
     * Takes an input URI, parses it and interprets it based on the routes.php in the config folder
     *
     * @package Atatiki\API\Controllers
     * @author Alexis Maiquez Murcia <alexis.murcia@atatiki.es, almamu@almamu.com>
     */
    class Resolver
    {
        /** @var array List of routes available for the system */
        private $routesList = array ();
    
        /**
         * Initializes the resolver and loads it's configuration
         *
         * @param array $routeInformation The routes to handle
         *
         * @throws \Exception
         */
        function __construct (array $routeInformation)
        {
            foreach ($routeInformation as $route => $info)
            {
                if (array_key_exists ('controller', $info) === false)
                {
                    throw new \Exception ('Every route should have a defined controller');
                }
                
                $this->routesList [] = array (
                    'controller' => $info ['controller'],
                    'regex' => self::routeToRegExp ($route, $info ['parameters'] ?? array ()),
                    'route' => $route,
                    'parameters' => array_keys ($info ['parameters'] ?? array ()),
                    'function' => $info ['function'] ?? 'run'
                );
            }
        }
        
        private static function routeToRegExp ($path, array $parameters)
        {
            $string = preg_quote ($path);
            
            foreach ($parameters as $name => $regex)
            {
                $string = preg_replace ('/\\\\' . $name . '/', '(' . $regex . ')', $string);
            }
            
            return "@^" . $string . "$@D";
        }
    
        /**
         * Handles the given path and returns the matching controller (if any)
         *
         * @param string $path The path to resolve
         *
         * @return \Atatiki\HTTP\Request
         * @throws \Exception
         */
        function resolve (string $path)
        {
            foreach ($this->routesList as $route)
            {
                $matches = array ();
                
                if (preg_match ($route ['regex'], $path, $matches))
                {
                    if (class_exists ($route ['controller']) == false)
                    {
                        throw new \Exception ("Controller cannot be found");
                    }
                    
                    array_shift ($matches);

                    $request = \MangaSekai\HTTP\Request::makeRequestFromServerGlobal (array_combine ($route ['parameters'], $matches));
                    
                    $controller = new $route ['controller'];

                    return $controller->{$route ['function']} ($request);
                }
            }
            
            throw new \Exception ('Controller for ' . $path . ' not found');
        }
    
        /**
         * Creates a new resolver based on the routing configuration
         *
         * @return \MangaSekai\API\Routing\Resolver
         * @throws \Exception
         */
        static function makeFromConfig (): Resolver
        {
            return new self (require (getenv ('PROJECT_PATH') . 'config/routes.php'));
        }
    };
