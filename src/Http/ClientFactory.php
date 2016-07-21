<?php

namespace Rs\VersionEye\Http;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery;
use Http\Message\Authentication\QueryParam;
use Http\Message\MultipartStream\MultipartStreamBuilder;

/**
 * Factory for creating Http Client.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ClientFactory
{
    /**
     * @param string $url
     * @param string $token
     *
     * @return HttpClient
     */
    public static function create($url, $token)
    {
        $plugins = [
            new Plugin\RedirectPlugin(),
            new Plugin\RetryPlugin(['retries' => 5]),
            new Plugin\DecoderPlugin(),
            new Plugin\ErrorPlugin(),
        ];

        if ($token) {
            $plugins[] = new Plugin\AuthenticationPlugin(new QueryParam([
                'api_key' => $token,
            ]));
        }

        $client        = new PluginClient(Discovery\HttpClientDiscovery::find(), $plugins);
        $streamFactory = Discovery\StreamFactoryDiscovery::find();
        $builder       = new MultipartStreamBuilder($streamFactory);

        return new HttpPlugHttpAdapterClient($client, $url, Discovery\MessageFactoryDiscovery::find(), $builder);
    }
}
