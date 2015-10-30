<?php

class CTextFilter{
    
/**
* Properties
*/
public static $purify = null;


public function __construct() {    
}



  /**
* Clean your HTML with HTMLPurifier, create an instance of HTMLPurifier if it does not exists.
*
* @param $data string the dirty HTML.
* @returns string as the clean HTML.
*/
   public static function Purify($data) {
    
    return $data;
  }


    
  /**
* BBCode formatting converting to HTML.
*
* @param string data to be converted.
* @returns string the formatted text.
*/
  public static function Bbcode2HTML($data) {
    $search = array(
      '/\[b\](.*?)\[\/b\]/is',
      '/\[i\](.*?)\[\/i\]/is',
      '/\[u\](.*?)\[\/u\]/is',
      '/\[img\](https?.*?)\[\/img\]/is',
      '/\[url\](https?.*?)\[\/url\]/is',
      '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
      );
    $replace = array(
      '<strong>$1</strong>',
      '<em>$1</em>',
      '<u>$1</u>',
      '<img src="$1" />',
      '<a href="$1">$1</a>',
      '<a href="$1">$2</a>'
      );
    return preg_replace($search, $replace, $data);
  }
  
  /**
* Make clickable links from URLs in text.
*
* @param string text text to be converted.
* @returns string the formatted text.
*/
  public static function MakeClickable($text) {
    return preg_replace_callback(
      '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
      create_function(
        '$matches',
        'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
      ),
      $text
    );
  }
  
 /**
* Markdown syntax
*
* @param $data string with Markdown text.
* @returns string as formatted HTML.
*/
   public static function Markdown($data) {
    require_once(__DIR__.'/php-markdown-lib/Michelf/Markdown.php');
    $data = Michelf\Markdown::defaultTransform($data);
    return $data;
  }
  
   /**
* MarkdownExtra syntax
*
* @param $data string with Markdown text.
* @returns string as formatted HTML.
*/
   public static function MarkdownExtra($data) {
    require_once(__DIR__.'/php-markdown-lib/Michelf/MarkdownExtra.php');
    return \Michelf\MarkdownExtra::defaultTransform($data);
  }
  
  
/**
* Support enhanced SmartyPants/Typographer for better typography.
*
* @param string text text to be converted.
* @returns string the formatted text.
*/
    public static function Typographer($text) {
        require_once(__DIR__.'/PHP_SmartyPants_Typographer_1.0.1/smartypants.php');
        return SmartyPants($text);
    }

    
    
}