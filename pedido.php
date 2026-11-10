<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
if (($_SERVER['REQUEST_METHOD']??'') !== 'POST') { header('Allow: POST'); http_response_code(405); exit('Método não permitido.'); }
exigirCsrf();
$nome=trim(is_string($_POST['nome']??null)?$_POST['nome']:'');
$telefone=trim(is_string($_POST['telefone']??null)?$_POST['telefone']:'');
$email=trim(is_string($_POST['email']??null)?$_POST['email']:'');
$tipo=trim(is_string($_POST['pedido']??null)?$_POST['pedido']:'');
$qtdTexto=trim(is_string($_POST['quantidade']??null)?$_POST['quantidade']:'');
$mensagem=trim(is_string($_POST['mensagem']??null)?$_POST['mensagem']:'');
$tiposPermitidos=['','pacote-5','pacote-7','ambos','atacado','duvida'];
$erros=[];
if ($nome==='' || mb_strlen($nome,'UTF-8')>120) $erros[]='Informe seu nome (até 120 caracteres).';
$digitos=preg_replace('/\\D+/','',$telefone);
if (strlen($digitos)<10 || strlen($digitos)>13 || strlen($telefone)>30) $erros[]='Informe um telefone válido com DDD.';
if ($email!=='' && (strlen($email)>190 || filter_var($email,FILTER_VALIDATE_EMAIL)===false)) $erros[]='Informe um e-mail válido ou deixe o campo vazio.';
if (!in_array($tipo,$tiposPermitidos,true)) $erros[]='Selecione um tipo de pedido válido.';
$quantidade=null;
if ($qtdTexto!=='') {
    $validada=filter_var($qtdTexto,FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>99999]]);
    if ($validada===false) $erros[]='A quantidade deve ser um número inteiro entre 1 e 99.999.';
    else $quantidade=$validada;
}
if ($mensagem==='' || mb_strlen($mensagem,'UTF-8')>3000) $erros[]='Escreva uma mensagem (até 3.000 caracteres).';
if ($erros) { definirMensagem('erro',implode(' ',$erros)); redirecionar('contato.php'); }
try {
    $stmt=conectarBanco()->prepare('INSERT INTO pedidos (nome,telefone,email,tipo_pedido,quantidade,mensagem) VALUES (:nome,:telefone,:email,:tipo,:quantidade,:mensagem)');
    $stmt->execute([':nome'=>$nome,':telefone'=>$telefone,':email'=>$email!==''?$email:null,':tipo'=>$tipo!==''?$tipo:null,':quantidade'=>$quantidade,':mensagem'=>$mensagem]);
    definirMensagem('sucesso','Seu pedido foi registrado. A equipe da Gelos Carvalho entrará em contato para confirmar os detalhes.');
} catch (Throwable $erro) {
    registrarFalha($erro);
    definirMensagem('erro','Não foi possível registrar seu pedido agora. Tente novamente ou entre em contato pelo WhatsApp.');
}
redirecionar('contato.php');
