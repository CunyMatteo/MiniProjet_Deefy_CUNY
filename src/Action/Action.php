<?php

namespace iutnc\deefy\Action;

abstract class Action{

    protected string $nomHost;
    protected string $methodeHttp;
    protected string $nomScript;

    public function __construct(){
        $this->nomHost = $_SERVER['HTTP_HOST'];
        $this->methodeHttp = $_SERVER['REQUEST_METHOD'];
        $this->nomScript = $_SERVER['SCRIPT_NAME'];
    }

    abstract public function execute() :string;
}