<?php


namespace qwenode\yii2lightning;


use yii\redis\Connection;

/**
 * 所有Yii快捷访问方式,工具集合等
 * Class LightningHelper
 * @package qwenode\yii2lightning
 */
class LightningHelper
{
    /**
     * @param string $anchor
     * @return \yii\console\Response|\yii\web\Response
     */
    public static function refresh($anchor = '')
    {
        return \Yii::$app->response->refresh($anchor);
    }

    public static function getReturnUrl()
    {
        $referrer = self::getRequest()->getReferrer();
        if ($referrer == '') {
            $referrer = '/';
        }
        return $referrer;
    }

    /**
     * 跳转到上一页
     * @param null $errorMessage 错误消息
     * @return \yii\web\Response
     */
    public static function returnPreviousPage($errorMessage = NULL)
    {
        FlashHelper::error($errorMessage);
        return self::getResponse()->redirect(self::getReturnUrl());
    }

    /**
     * get default database connection
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return self::getApplication()->getDb();
    }

    /**
     * quick call: Yii::$app->db->cache()
     * @param callable $callable
     * @param null $duration
     * @param null $dependency
     * @return mixed
     * @throws \Throwable
     */
    public static function withDbCache(callable $callable, $duration = null, $dependency = null)
    {
        return self::getApplication()->getDb()->cache($callable, $duration, $dependency);
    }

    /**
     * get default cache connection
     * @return \yii\caching\CacheInterface
     */
    public static function getCache()
    {
        return self::getApplication()->getCache();
    }

    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    public static function getApplication()
    {
        return \Yii::$app;
    }


    /**
     * @return \yii\web\User
     */
    public static function getUser()
    {
        return \Yii::$app->getUser();
    }

    /**
     * @return int|string|null
     */
    public static function getCurrentUserID()
    {
        return static::getUser()->getId();
    }

    /**
     * @param bool $autoRenew
     * @return bool|\yii\web\IdentityInterface|null
     * @throws \Throwable
     */
    public static function getUserIdentity($autoRenew = TRUE)
    {
        return static::getUser()->getIdentity($autoRenew);
    }

    /**
     * @return \yii\console\Request|\yii\web\Request
     */
    public static function getRequest()
    {
        return \Yii::$app->getRequest();
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public static function getResponse()
    {
        return \Yii::$app->getResponse();
    }

    /**
     * @return \yii\base\Security
     */
    public static function getSecurity()
    {
        return \Yii::$app->getSecurity();
    }

    /**
     * @return \yii\web\Session
     */
    public static function getSession()
    {
        return \Yii::$app->getSession();
    }

    /**
     * @return \Redis|Connection
     */
    public static function getRedis()
    {
        return \Yii::$app->get('redis');
    }

    /**
     * 此方法会检查链接可用性并自动重连
     * @return \yii\db\Connection
     * @throws \yii\db\Exception
     */
    public static function getKeepaliveDb()
    {
        $connection = \Yii::$app->getDb();
        try {
            $connection->createCommand('select 1')->execute();
            return $connection;
        } catch (\Exception $exception) {
            $connection->close();
            $connection->open();
        }
        throw new \Exception('database reconnect fail.');
    }

    /**
     * 此方法会检查链接可用性并自动重连
     * @return \Redis|Connection
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function getKeepaliveRedis()
    {
        /**
         * @var $redis Connection
         */
        $redis = \Yii::$app->get('redis');
        try {
            //检测链接可用性
            $redis->set('KEEPALIVE', 1);
            return $redis;
        } catch (\Exception $exception) {
            //重连
            $redis->close();
            $redis->open();
        }
        throw new SocketException('redis reconnect fail.');
    }
}