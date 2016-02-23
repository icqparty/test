<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Mail;

use Parser\Request;

class Answer extends Request
{


    public function parse($page_id, $parser_id)
    {


        echo "PAGE[{$page_id}]";

        $content_html=$this->get("https://otvet.mail.ru/api/v2/question?qid={$page_id}", false);

        $result_answer = json_decode($content_html,true);

        $answers = @$result_answer['answers'];



        if (!$answers || count($answers)==0){
            echo  " [NO COMMENT]\n";
            return 0;
        } ;


        echo  " COMMENTS[".count($answers)."] EMAIL[";


        $i = 0;
        foreach ($answers as $answer) {

            $email=$answer['oemail'];
            if (explode('@',$email)[1]=='nobody.mail.ru') {
                return 0;
            }

            $result['page_id'] = $page_id;
            $result['parser_id'] = $parser_id;
            $result['user_id'] = $answer['usrid'];
            $result['create_date']='';
            $result['create_date_z']='';
            $result['display_name']=$answer['nick'];
            $result['email'] = $email;


            try {
                $insert_query = "INSERT INTO parser_result (parser_id,email,create_date,create_date_z,display_name,user_id,page_id)
 VALUES ({$result['parser_id']}, '{$result['email']}','{$result['create_date']}','{$result['create_date_z']}','{$result['display_name']}',{$result['user_id']},{$result['page_id']});";
            //echo $insert_query."\n";


                $this->db->exec($insert_query);
                $i++;
                echo $email.",";
            } catch (\Exception $e) {

            }
        }
        echo  "] ADD COUNT[{$i}]\n";

        return $i;
    }

}
