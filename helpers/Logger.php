<?php

class Logger{

    private $endLine;
    private $separator;
    private $beginBlock;
    private $endBlock;

    function __construct( $separator = " ", $endLine = "\n", $beginBlock = "[", $endBlock = "]" ){
        $this->endLine = $endLine ;
        $this->separator = $separator;
        $this->beginBlock = $beginBlock;
        $this->endBlock = $endBlock;
    }

    public function info($text)
    {
        $this->write($text, "INFO");
    }

    public function warning($text)
    {
        $this->write($text, "WARN");
    }

    public function error($text)
    {
        $this->write($text, "ERROR");
    }

    private function write($text, $logLevel)
    {
        file_put_contents($this->getFileName(), $this->getFormattedMessage($logLevel, $text), FILE_APPEND);
    }

    private function getFormattedMessage($logLevel, $text)
    {
        return $this->beginBlock .  $this->getDateTime() . $this->endBlock . $this->beginBlock . $logLevel . $this->endBlock . $this->separator . $text . $this->endLine;
    }

    private function getFileName()
    {
        return "log/log-" . $this->today() . ".txt";
    }

    private function today()
    {
        return date("y-m-d");
    }

    private function getDateTime()
    {
        return date("y-m-d h:i:s");
    }
}