<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Rambler;

use Parser\Request;

class Comment extends Request
{


    public function parse($page_id, $parser_id)
    {
        echo "PAGE[{$page_id}]";

        $result_comment = json_decode($this->get("http://c.rambler.ru/api/app/4/widget/init?xid={$page_id}", false), true);

        if (!$comments = @$result_comment['comments']){
            echo  " [NO COMMENT]\n";
            return 0;
        } ;
        echo  " COMMENTS[".count($comments)."] EMAIL[";

        $i = 0;
        foreach ($comments as $comment) {

            $array_repl=array(
                'https://avatars.rambler.ru/200x200/',
                '//avatars.rambler.ru/200x200/',
                '//cdn-comments.rambler.ru/embed/i/default-userpic.png',

            );
            $email = str_replace($array_repl, '', $comment['userpic']);


            if($email==''){
                continue;
            }

            $result['page_id'] = $page_id;
            $result['parser_id'] = $parser_id;
            $result['email'] = $email;
            $result['display_name'] = $comment['displayName'];
            $result['user_id'] = $comment['userId'];
            $result['username'] = $comment['username'];
            $result['create_date'] = date('Y-m-d H:i:s', strtotime($comment['createdAt']));
            $result['create_date_z'] = $comment['createdAt'];
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

        return count($comments);
    }

}
