<?php

    // namespace PYLOTT\POCKETSERVER\PARSING;
    class ActionPHP
    {

        var $fp;
        var $parsed;

        public function __construct($fp, $params) {
            $this->fp = $fp;
            $this->params = $params;
        }

        public function parse()
        {
            $str = 'php '.$this->fp;
            foreach($this->params as $key => $value){
                $str .= " ".$value;
            }
            $this->parsed = shell_exec($str);
            if ($this->parsed != null) {
                return true;
            }
            return false;
        }
    }
    