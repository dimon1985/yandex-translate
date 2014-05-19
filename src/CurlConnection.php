<?php


namespace Yandex\Translate;

/**
 * CurlConnection
 * @author Gusakov Nikita <dev@nkt.me>
 */
class CurlConnection implements ConnectionInterface
{
    /**
     * @var resource
     */
    protected $handler;

    public function __construct()
    {
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($url, $method = 'GET')
    {
        curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->handler, CURLOPT_URL, $url);

        return curl_exec($this->handler);
    }
}
