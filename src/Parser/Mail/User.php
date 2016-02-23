<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 04.01.16
 * Time: 16:23
 */

namespace Parser\Mail;


class User extends Answer
{
    public function parse($page_id, $parser_id)
    {


        echo "USER[{$page_id}]";

        $content_html = $this->get("https://otvet.mail.ru/api/v2/stats?user={$page_id}", false);

        $result_info_user = json_decode($content_html, true);

        if ($result_info_user['status'] != '200' || $result_info_user['maildomain'] == 'nobody') {

            return 0;
        }

        $email = $result_info_user['semail'];
        echo " EMAIL[";

        try {
            $insert_query = "INSERT INTO parser_result (parser_id,email,create_date,create_date_z,display_name,user_id,page_id)
 VALUES ({$parser_id}, '{$email}','','','{$result_info_user['snick']}',{$page_id},{$page_id});";
            //echo $insert_query."\n";


            $this->db->exec($insert_query);
            echo $email . ",";
        } catch (\Exception $e) {

        }
        echo "]\n";

        return 1;

    }
}
