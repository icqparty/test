<?php
namespace Parser\Rambler;


class Category extends Rambler
{
    function initParams()
    {
        $this->page_count=1;
        $this->find_tag='a.columns__link';
        $this->url_full_page = $this->url_page .'/'. $this->param . "/?limit=50&page=";
    }

}