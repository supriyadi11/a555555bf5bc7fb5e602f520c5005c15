<?php
namespace App\Controllers;

use App\Response;

class Controller {

    protected Response $_response;

    public function __construct() {
        $this->_response = new Response;
    }

    public function setResponse(int $code, array $data)
    {
        $this->_response->code($code);
        $this->_response->data($data);
        return $this->_response;
    }

    public function response(int $code, array $data)
    {
        $this->_response->code($code);
        $this->_response->data($data);
        return $this->_response->build();
    }

}