<?php

class App_ErrorController extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null) {
    }
}