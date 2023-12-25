<?php
namespace App;

use Josantonius\Request\Request as JosantoniusRequest;

class Request {

    public array $server;
    public array $request;
    public array $params;

    public function __construct() {
        $this->server = $_SERVER;
        $this->_generateRequest();
    }

    public function has(string $key)
    {
        $reqBody = $this->request['body'];
        return in_array($key, array_keys($reqBody));
    }

    private function _generateRequest()
    {
        $this->request = [
            'path' => trim($this->server['PATH_INFO'] ?? $this->server['REDIRECT_URL'] ?? '', '/') ?? '/',
            'method' => $this->server['REQUEST_METHOD'],
            'scheme' => $this->server['REQUEST_SCHEME'] ?? 'http',
            'user_agent' => $this->server['HTTP_USER_AGENT'],
            'body' => array_merge(
                $this->get(), 
                $this->post(), 
                $this->put(), 
                $this->del(), 
                $this->files(),
            )
        ];
    }

    private function get(string $key = null)
    {
        if (str_contains($this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '', 'multipart/form-data')) {
            $req = JosantoniusRequest::get($key);
            parseRawHttpRequest($req);
            return $req;
        }
        return JosantoniusRequest::get($key);
    }

    private function files(string $key = null)
    {
        if (str_contains($this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '', 'multipart/form-data')) {
            $req = JosantoniusRequest::files($key);
            parseRawHttpRequest($req);
            return $req;
        }
        return JosantoniusRequest::files($key);
    }
    
    private function post(string $key = null)
    {
        if (!JosantoniusRequest::isPost()) return [];
        if (str_contains($this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'], 'multipart/form-data')) {
            $req = JosantoniusRequest::post($key);
            parseRawHttpRequest($req);
            return $req;
        }
        return JosantoniusRequest::post($key);
    }

    private function put(string $key = null)
    {
        if (!JosantoniusRequest::isPut()) return [];
        if (str_contains($this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'], 'multipart/form-data')) {
            $req = JosantoniusRequest::put($key);
            parseRawHttpRequest($req);
            return $req;
        }
        return JosantoniusRequest::put($key);
    }

    private function del(string $key = null)
    {
        if (!JosantoniusRequest::isDelete()) return[];
        parse_str(file_get_contents("php://input"), $_DEL);

        return $_DEL[$key] ?? $_DEL ?? null;
    }
}
