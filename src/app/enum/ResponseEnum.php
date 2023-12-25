<?php
namespace App\Enum;

enum ResponseEnum : string {
    case JSON = 'Content-type:application/json; charset=utf-8';
    case HTML = 'Content-type:text/html; charset=utf-8';
}