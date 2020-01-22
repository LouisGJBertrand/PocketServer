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
    use Gui\Application;
    use Gui\Components\Button;

    require "RequestParser.php";
    require "routes.php";
    require "views.php";
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
            // $this->server->name = '172.20.10.3';
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
            global $views;
            $views = $this->views = require "data/views.php";
            $this->routes = require 'data/routes.php';

            stdio::cout("server Listening on http://".$this->server->name.":".$this->server->port);

            global $argv;
            foreach ($argv as $key => $value) {
                // if ($value == "-GUI") {
                    //     $this->server->gui = true;
                    //     $application = new Application();
                    //     $application->on('start', function() use ($application) {
                    //         $button = (new Button())
                    //             ->setLeft(40)
                    //             ->setTop(100)
                    //             ->setWidth(200)
                    //             ->setValue('Look, I\'m a button!');
                        
                    //         $button->on('click', function() use ($button) {
                    //             $button->setValue('Look, I\'m a clicked button!');
                    //         });
                    //     });
                    //     $application->run();
                    //     $this->server->application = $application;
                    // } else {
                    //     $this->server->gui = false;
                    // }
                //
                
            }

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

            $startReading = microtime(true);
            $query = socket_read($user,25565);
            // print_r($request);
            // return;
            if(!socket_getpeername($user,$remoteAddr,$remotePort)){ 
                $stopReading = microtime(true);
                $totalRequestTime = $stopReading - $startReading;
                print PHP_EOL.PHP_EOL."total request time = $totalRequestTime\r\n".'SOCKET ERROR : '.socket_last_error($this->socket).PHP_EOL.PHP_EOL;
                return false;
            }

            $stopReading = $startComputing = microtime(true);
            $reqPars = new RequestParser($query);
            $request = $reqPars->request();

            if(isset($request["params"])){
                // print("PARAMS : ");
                // var_dump($request["params"]);
                $params = $request["params"];
            }
            // var_dump($query);

            // var_dump($request);
            // return 0;

            $method = $request["method"];
            // if (isset($reqPars->requestParsed["Accept-Encoding:"])) {
            //     if (in_array('gzip',$reqPars->requestParsed["Accept-Encoding:"])||in_array('gzip,',$reqPars->requestParsed["Accept-Encoding:"])) {
            //         $gzipCompress = 1;
            //     } else {
            //         $gzipCompress = 0;
            //     }
            // } else {
            //     $gzipCompress = 0;
            // }

            $gzipCompress = 0;

            // print_r($gzipCompress);
            // return 0;

            $authorizedMethods = ["GET","POST"];

            $response = $this->routes->$method($request,$gzipCompress,$params); 
            $stopComputing = \microtime(true);
            
            if ($response->msglen >= 1023)
            {
                
            }

            $startOutputing = \microtime(true);
            if ($response) {

                // \print_r($response->len);
                // return 0;

                // $socketW = socket_sendto($user, $response->str, $response->len+1, 0, $remoteAddr, $remotePort);
                $socketW = socket_write($user, $response->str, $response->len);
                if ($socketW){
                    $stopOutputing = microtime(true);

                    $totalRequestTime = $stopOutputing - $startReading;
                    $totalRequestAcquisitionTime = $stopReading - $startReading;
                    $totalRequestComputationTime = $stopComputing - $startComputing;
                    $totalRequestOutputationTime = $stopOutputing - $startOutputing;

                    $this->server->connections++;
                    stdio::cout($this->server->connections.":".round($socketW*1e-6/$totalRequestTime,2)."Mo/s -> (".$response->code.") q:".$request["query"]); 
                    usleep(10000);
                    return true;
                }
            }
            print PHP_EOL.PHP_EOL.'SOCKET ERROR : '.socket_strerror(socket_last_error($this->socket)).PHP_EOL.PHP_EOL;
            // var_dump($socketW);
            return false;
        }

    }