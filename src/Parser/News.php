<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser;


use HtmlParser\ParserDom;

class News extends Request
{

    public $url_page = 'http://news.rambler.ru';
    public $url_full_page = '';
    public $param = '';
    public $parser_id = '';
    public $db;

    public function __construct($db, $parser_id, $param = array())
    {

        $this->db = $db;
        $this->param = $param;
        $this->parser_id = $parser_id;
        $this->initParams();

        echo $this->url_full_page . "\n";
    }

    public function update_count($count=0)
    {
        $this->db->exec("UPDATE parser set `count`={$count} WHERE id='{$this->parser_id}';");
    }
}
