<?php

namespace IsaEken\Picpurify;

use Illuminate\Support\Str;
use IsaEken\Picpurify\Models\Media;
use IsaEken\Picpurify\Models\Moderation;
use JetBrains\PhpStorm\Pure;

class Response
{
    /**
     * @param string $response
     * @return static
     */
    public static function parse(string $response): static
    {
        $instance = (new static)->setData(json_decode($response, true));
        $instance->getStatus();
        $instance->getTime();
        $instance->getTasks();
        $instance->getModerations();
        $instance->getMedia();

        return $instance;
    }

    /**
     * @var array $data
     */
    public array $data;

    /**
     * @var bool $status
     */
    public bool $status = false;

    /**
     * @var float $time
     */
    public float $time = 0.0;

    /**
     * @var array $moderations
     */
    public array $moderations = [];

    /**
     * @var array $tasks
     */
    public array $tasks = [];

    /**
     * @var Media $media
     */
    public Media $media;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    #[Pure] public function __get(string $name)
    {
        if (array_key_exists($name, $this->getData())) {
            return $this->getData()[$name];
        }

        return $this->$name;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status = mb_strtolower($this->getData()['status']) == 'success';
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time = floatval($this->getData()['total_compute_time'] ?? 0);
    }

    /**
     * @return array
     */
    public function getModerations(): array
    {
        $moderations = [];
        foreach ($this->getData() as $key => $value) {
            if (str_ends_with($key, '_moderation')) {
                $moderation = Moderation::make((array) $value);
                $moderations[$moderation->getAttribute('name')] = $moderation;
            }
        }

        return $this->moderations = $moderations;
    }

    /**
     * @param string $moderation
     * @return Moderation|null
     */
    public function getModeration(string $moderation): Moderation|null
    {
        $_ = $this->getModerations();
        $moderation = mb_strtolower(Str::of($moderation)->explode('_')->first());
        if (array_key_exists($moderation, $_)) {
            return $_[$moderation];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getTasks(): array
    {
        return $this->tasks = explode(',', mb_strtolower($this->getData()['task_call'] ?? ''));
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this->media = new Media($this->getData()['media'] ?? []);
    }
}
