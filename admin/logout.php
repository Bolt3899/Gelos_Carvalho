<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
exigirLogin();
if(($_SERVER['REQUEST_METHOD']??'')!=='POST'){header('Allow: POST');http_response_code(405);exit('Método não permitido.');}
exigirCsrf();
$_SESSION=[];$cookie=session_get_cookie_params();
setcookie(session_name(),'', ['expires'=>time()-3600,'path'=>$cookie['path'],'secure'=>$cookie['secure'],'httponly'=>$cookie['httponly'],'samesite'=>$cookie['samesite']]);
session_destroy();redirecionar('../login.php');
