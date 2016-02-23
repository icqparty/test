<?php
namespace Parser\Rambler;


class Range extends Rambler
{

    public $page_start;
    public $page_end;
    public $direction=1;

    function initParams()
    {
        $part_param=explode('/',$this->param);

        $this->page_start=$part_param[0];

        if(isset($part_param[2]) && $part_param[2]=='-'){
            $this->direction=-1;
        }

        $this->page_end=$part_param[0]+$part_param[1]*$this->direction;

    }

    function start($db_page_parse = true){
        $i=0;
        $page_id=$this->page_start;
        while($page_id<>$this->page_end){
            $page_id+=$this->direction;
            $i++;
            $urls=array();
            $urls[$page_id] = array(
                'parser_id' => $this->parser_id,
                'page_id' => $page_id,
                'new' => true
            );
            echo $i."-";
            $this->get_comments($urls,false);
        }


    }


}