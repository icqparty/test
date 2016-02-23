<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Rambler;


use HtmlParser\ParserDom;
use Parser\News;

class Rambler extends News
{

    public $find_tag = 'a.latest__caption';
    public $page_count = 50;



    function get_url_page_db()
    {
        $select_query = "SELECT * FROM parser_page WHERE parser_id={$this->parser_id};";
        $results_row = $this->db->query($select_query);

        $result = array();
        if ($results_row) {
            foreach ($results_row as $page) {
                $result[$page['page_id']] = array(
                    'parser_id' => $page['parser_id'],
                    'page_id' => $page['page_id'],
                    'new' => false
                );
            }
        }

        return $result;
    }

    function initParams()
    {

        $this->url_full_page = $this->url_page . '/latest/?page=';


    }

    function start($db_page_parse = true)
    {

        try {
            //$db_page_parse=false;
            $urls = $db_page_parse ? $this->get_url_page_db() : array();
            echo "GET PAGES_DB..." . count($urls) . "\n";

            $page = 1;
            for ($page = 1; $page <= $this->page_count; $page++) {
                $html = $this->get($this->url_full_page . $page, false);

                $dom = new ParserDom($html);
                $urls_html = $dom->find($this->find_tag);

                foreach ($urls_html as $url_html) {
                    $page_id = preg_replace("/[^0-9]/", '', $url_html->getAttr('href'));

                    if (empty($page_id) || $page_id == 0 || isset($urls[$page_id])) {
                        continue;
                    }
                    echo $page_id . "--\n";


                    $urls[$page_id] = array(
                        'parser_id' => $this->parser_id,
                        'page_id' => $page_id,
                        'new' => true
                    );


                }

            }

            echo "GET PAGES_NEW..." . count($urls) . "\n";

            echo "PARSING PAGES...\n";
            $this->get_comments($urls);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }

    function get_comments($urls,$flag_add_page=true)
    {
        if (count($urls) < 0) {
            return false;
        }
        $comment = new Comment($this->db);
        foreach ($urls as $url) {
            $status = 1;
            $count = $comment->parse($url['page_id'], $url['parser_id']);

            if ($url['new']) {
                try {
                    if($flag_add_page){
                        $insert_query = "INSERT INTO parser_page (parser_id,page_id,status,count_comments)  VALUES ({$url['parser_id']}, {$url['page_id']},{$status},{$count});";
                        $this->db->exec($insert_query);
                    }

                } catch (\Exception $e) {
                }
            }
        }

        return true;

    }
}
