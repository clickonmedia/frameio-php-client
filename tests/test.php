<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use Frameio\FrameIOClient;

$client = new FrameIOClient('test');
echo $client->getHost();