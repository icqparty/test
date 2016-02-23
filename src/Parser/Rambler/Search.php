<?php
namespace Parser\Rambler;


use HtmlParser\ParserDom;
use PHPHtmlParser\Dom;

class Search extends Rambler
{
    function initParams()
    {
        $this->param=urlencode($this->param);
        $this->url_full_page = $this->url_page."/search?query={$this->param}&page=";


    }

}