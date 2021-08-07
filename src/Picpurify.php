<?php

namespace IsaEken\Picpurify;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Str;
use InvalidArgumentException;
use IsaEken\Picpurify\Traits\HasGuzzleClient;

/**
 * @property Image $image
 * @property string $api_key
 * @property array $tasks
 * @property string|null $origin_id
 * @property string|null $reference_id
 * @method Image getImage()
 * @method string getApiKey()
 * @method string getOriginId()
 * @method string getReferenceId()
 * @method self setImage(Image $image)
 * @method self setApiKey(string $key)
 * @method self setOriginId(string $id)
 * @method self setReferenceId(string $id)
 */
class Picpurify extends Factory
{
    const Endpoint = 'https://www.picpurify.com/analyse/1.1';

    /**
     * @var array $options
     */
    protected array $options = [
        'timeout' => 1,
    ];

    /**
     * @var array $attributes
     */
    protected array $attributes = [
        'image' => null,
        'api_key' => null,
        'tasks' => [],
        'origin_id' => null,
        'reference_id' => null,
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->fillOptions($options);
    }

    public function check(): void
    {
        if (is_null($this->getApiKey()) || ! mb_strlen($this->getApiKey()) > 0) {
            throw new InvalidArgumentException('API Key is missing.');
        }

        if (is_null($this->getTasks()) || ! is_array($this->getTasks()) || count($this->getTasks()) < 1) {
            throw new InvalidArgumentException('You need to add at least 1 task.');
        }

        if (is_null($this->getImage()) || ! $this->getImage() instanceof Image) {
            throw new InvalidArgumentException('Image variable is not valid Image class');
        }
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        $this->check();
        return [
            'API_KEY' => $this->getApiKey(),
            $this->getImage()->key() => $this->getImage()->parse(),
            'task' => $this->parseTasks(),
            'origin_id' => $this->getOriginId() ?? '',
            'reference_id' => $this->getReferenceId() ?? '',
        ];
    }

    /**
     * @return Response
     * @throws GuzzleException
     */
    public function run(): Response
    {
        $request = $this->getClient()->request('POST', static::Endpoint, [
            'form_params' => $this->parse(),
            'curl' => [
                CURLOPT_RETURNTRANSFER => true,
            ],
        ]);

        return Response::parse($request->getBody()->getContents());
    }
}
