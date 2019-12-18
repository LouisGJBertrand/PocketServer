<?php

    /*
    |--------------------------------------------------------------------------
    | PocketServer
    |--------------------------------------------------------------------------
    |
    | Pocket Server is a tool developped using Key PHP Framework that helps you
    | create your own TCP Server. It's completely customizable. You can make
    | your own derivation by changing the different classes that comes within the
    | bundle.
    |
    | This bundle is under MIT License
    | Please refer to the git in order to access to full details.
    |
    */



    use STDio\stdio;

    require "RequestParser.php";
    require "routes.php";
    require "headerConstructor.php";

    /**
     * 
     * Main Class of KeyPHP Project nammed PocketServer
     * 
     * @author 
     * 
     */
    class main extends KeyPHPKernel\KEYPHPAPPLICATION
    {

        var $socket;
        var $server;

        public function onStart()
        {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
            $this->server = new stdClass;
            $this->server->name = '127.0.0.1';
            $this->server->port = 8086;
            $a = 0;
            $max = 5;
            while(!socket_bind($this->socket, $this->server->name, $this->server->port)){
                if ($a == $max) {
                    print "server starting error. $max attempt limit reach.".PHP_EOL;
                    return false;
                }else {
                    print "server starting error, retry in 5secs".PHP_EOL;
                    sleep(5);
                    $a++;
                }
            };

            // ROUTE DECLARATION
            $this->server->connections = 0;
            $this->routes = new routes($this->server);
            $this->routes->newRoute("/",function () {
                $ress = new stdClass;
                $ress->msg = "";
                $ress->contentType = "text/html";
                $ress->code = 301;
                $ress->headers = "Location: /home";
                return $ress;
            });
            $this->routes->newRoute("/home",function (){
                $ress = new stdClass;
                $ress->msg = "<h1>Hello, World!</h1>";
                $ress->code = 200;
                $ress->contentType = "text/html";
                $ress->headers = "";
                return $ress;
            });
            $this->routes->newRoute("HTTPCOMMONERR404",function (){
                $ress = new stdClass;
                $ress->msg = "<h1>404 Not Found</h1>";
                $ress->code = 404;
                $ress->contentType = "text/html";
                $ress->headers = "";
                return $ress;
            });

            stdio::cout("server Started!");

            return true;
        }

        /**
         * 
         * loop() function
         * 
         * main function of the class main
         * 
         * @return bool
         * 
         */
        public function loop(){

            while(!socket_listen($this->socket));
            // while(!socket_connect($this->socket, $this->server->name, $this->server->port));

            while(!$user = socket_accept($this->socket));

            $query = socket_read($user,25565);
            // print_r($request);
            // return;
            if(!socket_getpeername($user,$remoteAddr,$remotePort)){ return false; }

            $reqPars = new RequestParser($query);
            $request = $reqPars->request();

            // var_dump($query);

            // var_dump($request);
            // return 0;

            $method = $request["method"];
            $response = $this->routes->$method($request); 
            
            if ($response) {
                $socketW = socket_sendto($user, $response->str, $response->len, 0, $remoteAddr, $remotePort);
                if ($socketW){
                    $this->server->connections++;
                    stdio::cout($this->server->connections.":".$socketW." -> (".$response->code.") q:".$request["query"]); 
                    usleep(10000);
                    return true;
                }
            }
            throw socket_last_error($this->socket);
            // var_dump($socketW);
            return false;
        }

    }