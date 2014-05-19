<?php


namespace Yandex\Translate;

/**
 * ConnectionInterface
 * @author Gusakov Nikita <dev@nkt.me>
 */
interface ConnectionInterface
{
    /**
     * @param string $url
     * @param string $method
     *
     * @return string
     */
    public function execute($url, $method = 'GET');
}
