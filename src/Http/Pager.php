<?php

namespace Rs\VersionEye\Http;

/**
 * ResultPager.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Pager implements \Iterator
{
    private $offset  = 0;
    private $current = 1;
    private $max     = 0;
    private $result  = [];

    /**
     * @var HttpClient
     */
    private $client;
    private $key;
    private $method;
    private $url;
    private $params = [];

    /**
     * @param array      $result
     * @param string     $key
     * @param HttpClient $client
     * @param string     $method
     * @param string     $url
     * @param array      $params
     */
    public function __construct(array $result, $key, HttpClient $client, $method, $url, array $params = [])
    {
        $this->current = $result['paging']['current_page'];
        $this->max     = $result['paging']['total_entries'];
        $this->result  = $result[$key];

        $this->key    = $key;
        $this->client = $client;
        $this->method = $method;
        $this->url    = $url;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->result[$this->offset];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if (!isset($this->result[$this->offset]) && $this->offset < $this->max) {
            ++$this->current;
            $url    = preg_replace('/page=[0-9]+/', 'page=' . $this->current, $this->url);
            $result = $this->client->request($this->method, $url, $this->params);

            $this->result = array_merge($this->result, $result[$this->key]);

            return true;
        }

        return $this->offset < $this->max;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
    }
}
