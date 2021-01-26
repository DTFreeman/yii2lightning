<?php


namespace qwenode\yii2lightning;

use Yii;

/**
 * 所有Yii快捷访问方式,工具集合等
 * Class UtilsHelper
 * @package qwenode\yii2lightning
 */
class UtilsHelper
{
    /**
     * @param string $anchor
     * @return \yii\console\Response|\yii\web\Response
     */
    public static function refresh($anchor = '')
    {
        return Yii::$app->response->refresh($anchor);
    }

    /**
     * @return \yii\console\Request|\yii\web\Request
     */
    public static function getRequest()
    {
        return Yii::$app->getRequest();
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public static function getResponse()
    {
        return Yii::$app->getResponse();
    }

    /**
     * @return \yii\base\Security
     */
    public static function getSecurity()
    {
        return Yii::$app->getSecurity();
    }

    /**
     * @return \yii\web\Session
     */
    public static function getSession()
    {
        return Yii::$app->getSession();
    }

    /**
     * @return \Redis
     */
    public static function getRedis()
    {
        return Yii::$app->get('redis');
    }
}