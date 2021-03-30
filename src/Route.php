<?php


namespace WebHoanHao\SimpleRouter;


class Route
{
    private static $routes = [];

    public static function add($route, $controller, $method, $name)
    {
        self::$routes[] = Array($name,trim($route),$controller,$method,[]);
    }

    private static function resolve($pathString)
    {
        $matched = false;
        $route = null;
        foreach (self::$routes as $item) {
            if (!empty($item[1]) && strpos($pathString, $item[1]) === 0) {
                $matched = true;
                $route = $item;
                break;
            }
        }

        if (!$matched) {
            throw new \Exception('Could not match route.');
        }

        if (trim($route[1]) !== '/') {
            $paramStr = str_replace($route[1], '', $pathString);
        } else {
            $paramStr = $pathString;
        }
        $params = explode('/', trim($paramStr, '/'));
        $params = array_filter($params);

        $route[4] = $params;
        return $route;
    }

    public static function run()
    {
        $request = $_SERVER['REQUEST_URI'];
        try {
            $match = self::resolve($request);
            if (is_array($match) && count($match)>0) {
                list($name,$route,$controller,$method,$params) = $match;
                try {
                    $reflectedMethod = new \ReflectionMethod($controller, $method);
                    if ($reflectedMethod->isPublic() && (!$reflectedMethod->isAbstract())) {
                        if ($reflectedMethod->isStatic()) {
                            forward_static_call_array(array($controller, $method), $params);
                        } else {
                            if (is_string($controller)) {
                                $controller = new $controller();
                            }
                            call_user_func_array(array($controller, $method), $params);
                        }
                    }
                } catch (\ReflectionException $reflectionException) {
                    echo $reflectionException->getMessage();
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function url($routeName, $params = [], $basePath = '')
    {
        if (trim($routeName) === '') {
            return self::baseUrl();
        }
        $url = self::baseUrl($basePath);
        foreach (self::$routes as $route) {
            if (is_array($route) && $route[0] === $routeName) {
                $url .= '/'.trim($route[1],'/');
                break;
            }
        }
        if (is_array($params) && count($params)>0) {
            $url .= '/'.implode('/',$params);
        }
        return $url;
    }

    private static function baseUrl($basePath = '') {
        $pageUrl = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $pageUrl .= "s";
        }
        $pageUrl .= "://";
        $pageUrl .= $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_PORT"] !== '80') {
            $pageUrl .= ":" . $_SERVER["SERVER_PORT"];
        }
        if (trim($basePath, '/') !== '') {
            $pageUrl .= trim($basePath, '/');
        }
        return $pageUrl;
    }
}
