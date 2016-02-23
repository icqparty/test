<?php
/**
 * Created by PhpStorm.
 * User: icqparty
 * Date: 29.12.15
 * Time: 14:43
 */

namespace Parser;


use Keboola\Csv\Exception;

class Request
{
    private $host_proxy;
    private $port_proxy;
    public  $db;

    public function __construct($db,$port_proxy=9150,$host_proxy='172.17.0.2')
    {
        $this->db=$db;
        $this->port_proxy = $port_proxy;
        $this->host_proxy = $host_proxy;
    }



    function get($url,$flag=true,$headers=null)
    {
        $timeout=5;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if(is_array($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }


        if($flag){
            //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208');
            curl_setopt($ch, CURLOPT_PROXY, $this->host_proxy . ':' . $this->port_proxy);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
        $result = curl_exec($ch);
        $message=curl_error($ch);
        if(!empty($message)){
            throw(new Exception($message));
        }
        curl_close($ch);
        return $result;
    }
}