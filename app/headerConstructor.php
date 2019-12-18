<?php

    class headerConstructor
    {
        
        public function generate($resp, stdClass $ress, stdClass $server, int $rescode = 200, string $headers = "")
        {

            if (gettype($resp) == "null") {
                return 0;
            }

            // MESSAGE DECLARATION
            $ress->msg = $resp->msg;
            $ress->contentType = $resp->contentType;
            $ress->msglen = strlen($ress->msg);
            
            // HEADER CONSTRUCTION
            $ress->str = "";
            $ress->str .= "HTTP/1.1 ".headerConstructor::getHTTPCorrectResponseString($rescode).PHP_EOL;
            if ($headers != "") {
                $ress->str .= $headers.PHP_EOL;
            }
            $ress->str .= "Host: ".$server->name.":".$server->port.PHP_EOL;
            $ress->str .= "Date: ".date("r").PHP_EOL;
            $ress->str .= "Server: PocketServer/1.0 (BETA)".PHP_EOL;
            $ress->str .= "Content-Lenght: ".$ress->msglen.PHP_EOL;
            $ress->str .= "Connection: Closed".PHP_EOL;
            $ress->str .= "Content-Type: ".$ress->contentType."; charset=UTF-8".PHP_EOL;
            $ress->str .= PHP_EOL.PHP_EOL;
            $ress->str .= $ress->msg;
            $ress->len = strlen($ress->str);

            $ress->code = $rescode;
            $ress->q = "this value is unavailable for the moment.";

            return $ress;

        }

        public function getHTTPCorrectResponseString(int $rescode)
        {

            if (http_response_code($rescode)) {
                switch ($rescode) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        return false;
                    break;
                }

                return $rescode . ' ' . $text;

            }
            return false;
            
        }

    }
    