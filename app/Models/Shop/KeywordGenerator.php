<?php
namespace App\Models\Shop;

abstract class KeywordGenerator {
    /**
     * @param $name
     * @return string
     */
    public static function fromName($name) : string {
        $keyword = str_replace(' ', '_', $name);
        $keyword = iconv('UTF-8', 'ASCII//TRANSLIT', $keyword);
        $keyword = preg_replace('/[^A-Za-z0-9_]/u', '-', $keyword);
        return $keyword;
    }

    /**
     * Hidden KeywordGenerator constructor.
     */
    private function __construct() { }
}
