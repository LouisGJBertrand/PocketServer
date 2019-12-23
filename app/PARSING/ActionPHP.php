<?php

    // namespace PYLOTT\POCKETSERVER\PARSING;
    class ActionPHP
    {

        var $fp;
        var $parsed;

        public function __construct($fp) {
            $this->fp = $fp;
        }

        public function parse()
        {
            $this->parsed = shell_exec('php '.$this->fp);
            if ($this->parsed != null) {
                return true;
            }
            return false;
        }
    }
    