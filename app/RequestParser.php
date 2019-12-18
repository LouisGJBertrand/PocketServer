<?php

    class RequestParser
    {
        public function __construct($query) {
            $this->query = $query;
            $this->request = explode("\r\n",$this->query);
            foreach ($this->request as $value) {
                $parsing = explode(" ",$value,2);
                $this->requestParsed[$parsing[0]] = explode(" ",$parsing[1]);
                // print $parsing[0].PHP_EOL.PHP_EOL;
            }
            
        }

        public function request()
        {
            $keys = array_keys($this->requestParsed);
            $response["method"] = $keys[0];
            $response["query"] = $this->requestParsed[$keys[0]][0];
            $response["HTTPVersion"] = $this->requestParsed[$keys[0]][1];
            return $response;
        }
    }
    