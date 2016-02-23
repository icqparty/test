<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Mail;



class RangeUser extends Range
{
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

                $this->get_users($urls,$flag_add_page=false);

            }




        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }



    function get_users($urls,$flag_add_page=true)
    {
        if (count($urls) < 0) {
            return false;
        }
        $user = new User($this->db);
        foreach ($urls as $url) {
            $status = 1;
            $count = $user->parse($url['page_id'], $url['parser_id']);

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
