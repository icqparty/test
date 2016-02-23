<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
foreach ($argv as $arg) {
    $e = explode("=", $arg);
    if (count($e) == 2)
        $_GET[$e[0]] = $e[1];
    else
        $_GET[$e[0]] = 0;
}

$start = $_GET['start'];
$end = $_GET['end'];
$direction = $_GET['direction'];

$param=$start.'/'.$end.'/'.$direction;
$parser_id=8;



function log_($message)
{
    file_put_contents('/var/www/test/data/parser.log', "{$message}\n", FILE_APPEND);

}


$config = array(
    'host' => 'leadroi.ru',
    'dbname' => 'parser_email_leadroi',
    'user' => 'leadroi',
    'password' => 'wcvM)_{9435w)(N*)(',
);



$pid=getmypid();
while (1) {
    $db = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $class = '\\Parser\\News' . ucfirst('range');
            $object = new $class($db, $parser_id, $param);
            $db->exec("UPDATE parser set status='run',pid='{$pid}' WHERE id='{$parser['id']}';");
            $object->start(false);
            $db->exec("UPDATE parser set status='complete',pid='' WHERE id='{$parser['id']}';");

        } catch (Exception $error) {
            //echo $error->getTraceAsString();

            $db->exec("UPDATE parser set status='error',pid='' WHERE id='{$parser['id']}';");
            log_("ID_PARSER({$id}) " . $error->getTraceAsString());
        }

}