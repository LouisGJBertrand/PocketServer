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
            // $response["query"] = explode("?",$this->requestParsed[$keys[0]][0])[0];
            if($response["method"] == "GET"){
                $response["query"] = explode("?",$this->requestParsed[$keys[0]][0])[0];
                // print($response["query"]);
                parse_str(explode("?",$this->requestParsed[$keys[0]][0])[1],$response["params"]);
                // print_r($this->requestParsed[$keys[0]][0]);
                // var_dump($response);
            } else if($response["method"] == "POST"){
                $response["query"] = $this->requestParsed[$keys[0]][0];
                // $response["params"] = parse_str(explode("?",$this->requestParsed[$keys[0]][0])[1]);
            } else {
                $response["method"] = "GET";
                $response["query"] = "__METHODERROR__";
            }
            $response["HTTPVersion"] = $this->requestParsed[$keys[0]][1];
            return $response;
        }
    }
    