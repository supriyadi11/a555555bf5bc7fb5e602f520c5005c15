<?php
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/Bootstrap.php';

$response = Router::run(request());
header($response['header']);
http_response_code($response['httpCode']);
echo $response['content'];

exit(0);