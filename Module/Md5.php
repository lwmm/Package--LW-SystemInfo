<?php

namespace LwSystemInfo\Module;

class Md5
{
    protected $GET;
    protected $config;


    public function __construct($GET)
    {
        $this->GET = $GET;
        $this->config = \lw_registry::getInstance()->getEntry("config");
    }

    public function execute()
    {
        $array = array();        
        $array["expectedMd5"] = $this->GET["expectedMd5"];
        $array["configPath"] = $this->GET["configPath"];
        $array["path"] = urldecode($this->GET["filePath"]);
        $array["completePath"] = $this->config["path"][$array["configPath"]] . $array["path"];
      
        if (!isset($this->GET["charset"])) {
            $charset = "UTF-8";
        }else{
            $charset = $this->GET["charset"];
        }

        $path = $array["completePath"];
        $path = str_replace("//", "", $path);
        $path = str_replace("..", "", $path);
        
        if (is_file($path)) {
            $file = fopen($path, "r");
            while (!feof($file)) {
                $content .= fgets($file);
            }
            fclose($file);
            $array["recievedMd5"] = md5($this->convert($charset, $content));
        }else{
            $array["recievedMd5"] = false;
        }
        
        return $array;
    }

    private function convert($targetCharset, $content)
    {
        if ($this->isUtf8($content)) {
            if ($targetCharset == "UTF-8") {
                return $content;
            } else {
                return mb_convert_encoding($content, "ISO-8859-15", "UTF-8");
            }
        } else {
            if ($targetCharset == "UTF-8") {
                return utf8_encode($content);
            } else {
                return mb_convert_encoding(utf8_encode($content), "ISO-8859-15", "UTF-8");
            }
        }
    }

    private function isUtf8($str)
    {
        $strlen = strlen($str);
        for ($i = 0; $i < $strlen; $i++) {
            $ord = ord($str[$i]);
            if ($ord < 0x80) { // 0bbbbbbb
                continue;
            } elseif (($ord & 0xE0) === 0xC0 && $ord > 0xC1) { // 110bbbbb (exkl C0-C1)
                $n = 1;
            } elseif (($ord & 0xF0) === 0xE0) { // 1110bbbb
                $n = 2;
            } elseif (($ord & 0xF8) === 0xF0 && $ord < 0xF5) { // 11110bbb (exkl F5-FF)
                $n = 3;
            } else { // ungültiges UTF-8-Zeichen
                return false;
            }

            for ($c = 0; $c < $n; $c++) { // $n Folgebytes? // 10bbbbbb
                if (++$i === $strlen || (ord($str[$i]) & 0xC0) !== 0x80) { // ungültiges UTF-8-Zeichen
                    return false;
                }
            }
        }
        return true; // kein ungültiges UTF-8-Zeichen gefunden
    }

}
