<?php

use App\Enum\ResponseEnum;
use App\Logger;
use App\Request;
use App\Response;
use GuzzleHttp\Client;

if (!function_exists('config')) {
    function config(string $key) : array|string|null
    {
        $params = explode('.', $key);
        $file = __DIR__ . "/config/{$params[0]}.php";
        if (!file_exists($file)) return null;
        $in = include $file;
        for ($i = 1; $i < count($params); $i++) { 
            if (!isset($in[$params[$i]])) {
                throw new \Exception("The \"{$key}\" config not found!");
            }
            $in = $in[$params[$i]];
        }
        return $in;
    }
}

if (!function_exists('now')) {
    function now(DateTimeZone|string|null $tz = null)
    {
        $tz = empty($tz) ? config('app.timezone') : $tz;
        return Carbon\Carbon::now($tz);
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        dump($vars);
        die;
    }
}

if (!function_exists('abort')) {
    function abort(int $code = 400, string $message = "", ResponseEnum $buildAs = ResponseEnum::JSON)
    {
        $response = new Response();
        return $response->code($code)->data(['message' => $message])->build($buildAs);
    }
}

if (!function_exists('request')) {
    function request() : App\Request
    {
        return $GLOBALS['request'] ?? null;
    }
}

if (!function_exists('db')) {
    function db() : Illuminate\Database\Capsule\Manager
    {
        return $GLOBALS['db'] ?? null;
    }
}
if (!function_exists('getClientIp')) {
    function getClientIp(bool $isTest = false) {
        if ($isTest) return '2404:8000:1029:13af:6d07:6df:e548:4200';

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }    
}

if (!function_exists('first')) {
    function first(array $data)
    {
        return array_values($data)[0] ?? null;
    }
}

if (!function_exists('last')) {
    function last(array $data)
    {
        return array_reverse(array_values($data))[0] ?? null;
    }
}

if (!function_exists('parseRawHttpRequest')) {
    function parseRawHttpRequest(array &$a_data)
    {
      $result = [];
      // read incoming data
      $input = file_get_contents('php://input');
      
      // grab multipart boundary from content type header
      preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
      $boundary = $matches[1];
      
      // split content by boundary and get rid of last -- element
      $a_blocks = preg_split("/-+$boundary/", $input);
      array_pop($a_blocks);
          
      // loop data blocks
      foreach ($a_blocks as $id => $block)
      {
        if (empty($block))
          continue;
        
        // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
        
        // parse uploaded files
        if (strpos($block, 'application/octet-stream') !== FALSE)
        {
          // match "name", then everything after "stream" (optional) except for prepending newlines 
          preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
        }
        // parse all other fields
        else
        {
          // match "name" and optional value in between newline sequences
          preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        }
        $result[$matches[1]] = $matches[2];
      }  
      $a_data = $result;      
    }
}

if (!function_exists('logger')) {
    function logger(string $level, string $message, array $context = [])
    {
        $log = $GLOBALS['logger'] ?? null;
        $log->writeLog($level, $message, $context);
    }
}

if (!function_exists('getFuncCaller')) {
    function getFuncCaller(int $level = 0)
    {
        $stack = debug_backtrace();
        return $stack[$level+1] ?? [];
    }
}

if (!function_exists('datetimeConvertion')) {
    function datetimeConvertion(&$data)
    {
        $client = new Client(['base_uri' => 'http://ip-api.com/json/']);
        $res = $client->get(urlencode(getClientIp(true)));

        $origin_string = false;
        if (!is_array($data)) {
            $origin_string = true;
            $data = json_decode($data, true);
        }

        if ($res->getStatusCode() == 200) {
            $res = json_decode($res->getBody()->__toString(), true);            
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    datetimeConvertion($value);
                    $data[$key] = $value;
                }
                else {
                    try {
                        $datetime = now()->createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->timezone($res['timezone']);
                        if ($datetime != false) {
                            $data[$key] = $datetime->toDateTimeString();
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
        }
        if ($origin_string) {
            $data = json_encode($data);
        }
    }
}

if (!function_exists('oAuthService')) {
    function oAuthService () : SocialConnect\Auth\Service
    {
        return $GLOBALS['service'];
    }
}

if (!function_exists('response')) {
    return new Response();
}