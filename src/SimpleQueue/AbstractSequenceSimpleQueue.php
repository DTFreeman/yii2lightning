<?php


namespace qwenode\yii2lightning\SimpleQueue;


use qwenode\yii2lightning\UtilsHelper;

/**
 * 按先进先出规则的队列
 * Class AbstractSequenceSimpleQueue
 * @package qwenode\yii2lightning\SimpleQueue
 */
abstract class AbstractSequenceSimpleQueue extends AbstractSimpleQueue
{
    /**
     * @param $data
     * @return bool|int
     */
    public function enqueue($data): bool
    {
        if ($this->encoder instanceof EncoderInterface) {
            $data = $this->encoder->encode($data);
        }
        return UtilsHelper::getKeepaliveRedis()->lpush(static::key(), $data);
    }

    /**
     * @return array|bool|mixed|string
     */
    public function dequeue()
    {
        $data = UtilsHelper::getKeepaliveRedis()->rpop(static::key());
        if ($data === FALSE) {
            return $data;
        }
        if ($this->encoder instanceof EncoderInterface) {
            $data = $this->encoder->decode($data);
        }
        return $data;
    }

    /**
     * 队列长度
     * @return int
     */
    public function size(): int
    {
        return UtilsHelper::getKeepaliveRedis()->llen(static::key());
    }
}