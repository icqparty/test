<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Mail;


use Keboola\Csv\Exception;
use Parser\News;

class Range extends News
{
    public $url_page = 'https://otvet.mail.ru/api/v2/question';
    public $page_start;
    public $page_end;
    public $direction=1;

    function initParams()
    {

        $this->param=str_replace('\\','/',$this->param);

        $part_param=explode('/',$this->param);



        $this->page_start=$part_param[0];

        if(isset($part_param[2]) && $part_param[2]=='-'){
            $this->direction=-1;
        }

        $this->page_end=$this->page_start+$part_param[1]-1*$this->direction;


    }

    function start($db_page_parse = true)
    {

        try {

            $page_id=$this->page_start;
            while($page_id<>$this->page_end){
                $page_id+=$this->direction;
                $urls=array();
                $urls[$page_id] = array(
                    'parser_id' => $this->parser_id,
                    'page_id' => $page_id,
                    'new' => true
                );

                $this->get_answer($urls,$flag_add_page=false);

            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }



    function get_answer($urls,$flag_add_page=true)
    {
        if (count($urls) < 0) {
            return false;
        }
        $comment = new Answer($this->db);
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
