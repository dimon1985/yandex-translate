<?php

namespace Yandex\Translate;

/**
 * Translate
 * @author Gusakov Nikita <dev@nkt.me>
 * @link   http://api.yandex.com/translate/doc/dg/reference/translate.xml
 */
class Translator
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    /**
     * @var string
     */
    protected $key;
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @link http://api.yandex.com/key/keyslist.xml Get a free API key on this page.
     *
     * @param string              $key        The API key
     * @param ConnectionInterface $connection The connection instance
     */
    public function __construct($key, ConnectionInterface $connection = null)
    {
        $this->key = $key;
        if ($connection === null) {
            $connection = new CurlConnection();
        }
        $this->connection = $connection;
    }

    /**
     * Returns a list of translation directions supported by the service.
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', array(
            'ui' => $culture
        ));
    }

    /**
     * Detects the language of the specified text.
     * @link http://api.yandex.com/translate/doc/dg/reference/detect.xml
     *
     * @param string $text The text to detect the language for.
     *
     * @return string
     */
    public function detect($text)
    {
        $data = $this->execute('detect', array(
            'text' => $text
        ));

        return $data['en'];
    }

    /**
     * Translates the text.
     * @link http://api.yandex.com/translate/doc/dg/reference/translate.xml
     *
     * @param string $text     The text to be translated.
     * @param string $language Translation direction (for example, "en-ru" or "ru").
     * @param bool   $html     Text format, if true - html, otherwise plain.
     * @param int    $options  Translation options.
     *
     * @return array
     */
    public function translate($text, $language, $html = false, $options = 0)
    {
        $data = $this->execute('translate', array(
            'text'    => $text,
            'lang'    => $language,
            'format'  => $html ? 'html' : 'plain',
            'options' => $options
        ));

        // @TODO: handle source language detecting
        return new Translation($text, join(' ', (array)$data['text']), $data['lang']);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     *
     * @throws Exception
     * @return array
     */
    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        $url = static::BASE_URL . $uri . '?' . http_build_query($parameters);
        $result = json_decode($this->connection->execute($url));
        if (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }

        return $result;
    }
}
