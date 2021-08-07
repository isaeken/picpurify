<?php

namespace IsaEken\Picpurify\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use IsaEken\Picpurify\Image;
use IsaEken\Picpurify\Picpurify;
use IsaEken\Picpurify\Tasks;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testMagics()
    {
        $instance = new Picpurify;
        $instance->setApiKey('testing');
        $instance->setOption('opt1', 'value');
        $instance->test = '123';

        $this->assertEquals('testing', $instance->getApiKey());
        $this->assertEquals('value', $instance->getOption('opt1'));
        $this->assertEquals('123', $instance->test);
    }

    public function testRemote()
    {
        $instance = (new Picpurify)
            ->setApiKey('your-api-key')
            ->setTasks([
                Tasks::PornModeration,
                Tasks::GoreModeration,
            ])
            ->setImage(Image::createFromUrl('https://example.com/example.jpg'));

        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ], file_get_contents(__DIR__ . '/data/response.json'), '1.1'),
        ]));

        $response = $instance->setOption('handler', $handler)->run();
        $this->assertTrue($response->getStatus());
        $this->assertTrue($response->getModeration(Tasks::PornModeration)->detection);
        $this->assertFalse($response->getModeration(Tasks::GoreModeration)->detection);
    }
}
