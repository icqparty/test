<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';




function log_($message)
{
    file_put_contents('/var/www/test/data/parser.log', "{$message}\n", FILE_APPEND);

}
foreach ($argv as $arg) {
    $e = explode("=", $arg);
    if (count($e) == 2)
        $_GET[$e[0]] = $e[1];
    else
        $_GET[$e[0]] = 0;
}

$id = $_GET['id'];
$web = @$_GET['web'];
$loop = 1;

$config = array(
    'host' => 'leadroi.ru',
    'dbname' => 'parser_email_leadroi',
    'user' => 'leadroi',
    'password' => 'wcvM)_{9435w)(N*)(',
);
try {
    $db = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $parser_row = $db->query("SELECT * FROM parser WHERE id={$id}", PDO::FETCH_ASSOC)->fetch();



    if (!$parser_row) {
        echo "NO EXIST";
        exit();
    }


    if(!$web){
        $status='run';
        $pid=getmypid();
        exec('kill '.$parser_row['pid']);
        $db->exec("UPDATE parser set status='{$status}',pid='{$pid}' WHERE id='{$parser_row['id']}';");

    }



    $class = '\\Parser\\' . $parser_row['type'];
    $object = new $class($db, $parser_row['id'], $parser_row['param']);
    //while(1){
        $object->start();
    //}


    $db->exec("UPDATE parser set status='complete' WHERE id='{$id}';");

} catch (Exception $error) {
    //echo $error->getTraceAsString();

    $db->exec("UPDATE parser set status='error',pid='' WHERE id='{$id}';");
    log_("ID_PARSER({$id}) " . $error->getTraceAsString());
}
