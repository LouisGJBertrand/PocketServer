<?php

    class routes
    {

        var $routes;

        public function __construct($server)
        {
            $this->server = $server;
            $this->routes = new stdClass;
        }

        public function GET($request)
        {
  
            // var_dump($this->routes->get);
            // return 0;
            if (isset($this->routes->get[$request["query"]])) {
                $str = $this->routes->get[$request["query"]];
            } else {
                if (isset($this->routes->get["HTTPCOMMONERR404"])) {
                    $str = $this->routes->get["HTTPCOMMONERR404"];
                }else{
                    $str = new stdClass;
                    $str->contentType = "text/html";
                    $str->headers = "";
                    $str->msg = "404 Error not found<br><a href=\"/\">root</a>";
                    $str->code = 404;
                }
            }
            $response = headerConstructor::generate($str,new stdClass,$this->server,$str->code,$str->headers);
            // var_dump($response);

            return $response;

        }

        public function newRoute(string $path, $holder, $method = "get")
        {
            // var_dump($holder);
            // return 0;
            $this->routes->$method[$path] = $holder();
        }
    }
    