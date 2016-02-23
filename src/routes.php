<?php
// Routes

//Список парсеров
$app->get('/[update/{flag}]', function ($request, $response, $args) {

    $template = 'index.phtml';
    if (isset($args['flag'])) {
        $template = 'list.phtml';
    }
    // Sample log message

    $args['title'] = 'Главная';
    $results = $this->db->query('SELECT p.*,(select count(p_r.id) from parser_result p_r where p_r.parser_id=p.id) as count FROM parser as p WHERE p.remove=0', PDO::FETCH_ASSOC);
    $p=new \Parser\Process();
    foreach ($results as $row) {

        $p->setPid($row['pid']);
        $row['status_p']=$p->status();

        $args['items'][] = $row;
    }
    $args['count']=$this->db->query('SELECT count(*) as count from parser_result')->fetch()[0];

    return $this->renderer->render($response, $template, $args);
});

//add
$app->get('/parser/add', function ($request, $response, $args) {
    $args = $_GET;


    $this->db->exec("INSERT INTO parser (header,type,param,event,status) VALUES ('{$args['header']}', '{$args['type']}','{$args['param']}','','complete')");


    $args['result']['status']='ok';
    header("Content-Type: application/json");
    return $this->renderer->render($response,'json.phtml',$args);
});

//удалить
$app->get('/parser/remove', function ($request, $response, $args) {
    $args = $_GET;

    $row = $this->db->query("SELECT * FROM parser WHERE id='{$args['id']}';")->fetch();
    $p=new \Parser\Process();
    $p->setPid($row['pid']);

    if(!$p->status()){
        $this->db->exec("UPDATE parser set remove=1,status='stop' WHERE id='{$args['id']}';");
        $status='ok';
    }else{
        $status='error';
        $args['result']['message']='Остановите парсер';
    }
    $args['result']['status']=$status;

    header("Content-Type: application/json");

    return $this->renderer->render($response,'json.phtml',$args);
});


$app->get('/parser/status', function ($request, $response, $args) {

    $args = $_GET;

    $row = $this->db->query("SELECT * FROM parser WHERE id='{$args['id']}';")->fetch();

    $pr=new \Parser\Process('php /var/www/test/public/parser.php id='.$args['id'].' web=1');
    $pid='';
    if($args['event']=='stop'){
        $status='stop';
        $pr->setPid($row['pid']);
        $pr->stop();
    }else{
        $status='run';
        $pr->setPid($row['pid']);
        $pr->stop();

        $pr->start();
        $pid=$pr->getPid();
    }

    $this->db->exec("UPDATE parser set status='{$status}',pid='{$pid}' WHERE id='{$row['id']}';");


    $results = $this->db->query('SELECT p.*,(select count(p_r.id) from parser_result p_r where p_r.parser_id=p.id) as count FROM parser as p WHERE p.remove=0', PDO::FETCH_ASSOC);
    $p=new \Parser\Process();
    foreach ($results as $row) {
        $p->setPid($row['pid']);
        $row['status_p']=$p->status();
        $args['items'][] = $row;
    }
    $args['count']=$this->db->query('SELECT count(*) as count from parser_result')->fetch()[0];

    return $this->renderer->render($response, 'list.phtml', $args);
});

//EXPORT PARSER
$app->get('/parser/export/id/{id}', function ($request, $response, $args) {
    $args['title'] = 'Результат';
    $results = $this->db->query("SELECT * FROM parser_result where parser_id={$args['id']}");
    foreach ($results as $row) {

        $args['items'][] = $row;
    }
    return $this->renderer->render($response, 'results.phtml', $args);
});