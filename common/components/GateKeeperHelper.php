<?php
namespace common\components;

use Yii;
use \mdm\admin\components\Configs;
use yii\helpers\StringHelper;

class GateKeeperHelper extends \mdm\admin\components\Helper {
    /**
     * {@inheritdoc}
     * remove api version prefix from route, exp: /v1, /v2, /v3, etc
     */
    public static function normalizeRoute($route, $advanced = false){
        $r = parent::normalizeRoute($route, $advanced = false);
        if (!empty($r)){
            if (preg_match('/(\/v[0-9]+)(\/.*)/',$r,$matches)){
                //var_dump($matches);
                $r = $matches[2];
            }
        }
        return $r;
    }

    /**
     * Check access route for user.
     * @param string|array $route
     * @param integer|User $userId
     * @return boolean
     */
    public static function checkRoute($route, $params = [], $userId = null)
    {
        $config = Configs::instance();
        $manager = Configs::authManager();

        $r = static::normalizeRoute($route, $config->advanced);        
        if ($config->onlyRegisteredRoute && !isset(static::getRegisteredRoutes()[$r])) {
            return true;
        }

        //if ($userId === null) {
        //    $user = Yii::$app->getUser();
        //}
        //$userId = $user instanceof User ? $user->getId() : $user;
        //echo $userId;die;
        //die('here');
        
        if ($config->strict) {
            //die('strict');
            //if ($user->can($r, $params)) {
            //    return true;
            //}
            if ($manager->checkAccess($userId,$r,$params)){
                return true;
            }

            while (($pos = strrpos($r, '/')) > 0) {
                $r = substr($r, 0, $pos);
                //print_r($r);die;
                //if ($user->can($r . '/*', $params)) {
                //    return true;
                //}
                if ($manager->checkAccess($userId,$r.'/*',$params)){
                    return true;
                }
            }
            //return $user->can('/*', $params);
            return $manager->checkAccess($userId,'/*',$params);
        } else {
            $routes = static::getRoutesByUser($userId);
            if (isset($routes[$r])) {
                return true;
            }
            while (($pos = strrpos($r, '/')) > 0) {
                $r = substr($r, 0, $pos);
                if (isset($routes[$r . '/*'])) {
                    return true;
                }
            }
            return isset($routes['/*']);
        }
    }

    /**
     * check allowed routes
     * @param string $route
     * @param array $allowRoutes
     * @return bool route is allowed or not
     */
    public static function isAllowedRoute($route,$allowRoutes){
        $config = Configs::instance();
        $manager = Configs::authManager();

        $r = static::normalizeRoute($route, $config->advanced);
        foreach ($allowRoutes as $pattern) {
            if (StringHelper::matchWildcard($pattern, $r)) {
                return true;
            }
        }

        return false;
    }
}