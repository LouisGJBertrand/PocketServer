<?php

    // namespace PYLOTT\POCKETSERVER;

    require "PARSING/ActionPHP.php";

    class views
    {
        var $server;
        var $views;

        public function __construct($server = null)
        {
            $this->server = $server;
            $this->routes = [];
        }

        public function parse($path, $params)
        {
            
            $response = "";
            if ($this->views[$path]->filetype == "ActionPHP") {

                $fp = __DIR__.'/data/'.$this->views[$path]->filepath;
                $parser = new ActionPHP($fp, $params);
                // if ($parser->parse()) {
                //     $response = $parser->parsed;
                // }
                return $parser;

            } elseif ($this->views[$path]->filetype == "Blade") {
                # code...
            }
            return $response;

        }

        public function newView(string $path, $filepath, $filetype = "ActionPHP")
        {
            // var_dump($holder);
            // return 0;
            $this->views[$path] = new \stdClass;
            $this->views[$path]->filepath = $filepath;
            $this->views[$path]->filetype = $filetype;
        }
    }
    