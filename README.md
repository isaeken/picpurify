# Picpurify API

> Work in progress

## Usage

```php
$picpurify = new \IsaEken\Picpurify\Picpurify;
$picpurify
    ->setApiKey('api-key')
    ->setTasks([
        \IsaEken\Picpurify\Tasks::DrugModeration,
        \IsaEken\Picpurify\Tasks::GoreModeration,
        \IsaEken\Picpurify\Tasks::PornModeration,
    ])
    ->setImage(\IsaEken\Picpurify\Image::createFromUrl('https://example.com/example.jpg'));

$response = $picpurify->run();

$response->getStatus();
$response->getTime();
$response->getMedia();
$response->getModerations();
$response->getModeration(\IsaEken\Picpurify\Tasks::DrugModeration)->detection;
$response->getData();
```

## License

MIT
