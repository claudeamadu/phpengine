<?php

class MetaTags{
    
    public static function viewPort($content = 'width=device-width, initial-scale=1')
    {
        return '<meta name="viewport" content="' . $content . '">';
    }
    public static function charset($charset = 'utf-8')
    {
        return '<meta charset="' . $charset . '">';
    }
}