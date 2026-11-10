<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
$admin=exigirLogin();
if(($_SERVER['REQUEST_METHOD']??'')!=='POST'){header('Allow: POST');http_response_code(405);exit('Método não permitido.');}
exigirCsrf();
$id=filter_var($_POST['pedido_id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
$status=is_string($_POST['status']??null)?trim($_POST['status']):'';
$estados=['Pendente','Confirmado','Em preparação','Entregue','Cancelado'];
if($id===false||!in_array($status,$estados,true)){definirMensagem('erro','Pedido ou situação inválida.');redirecionar('pedidos.php');}
try{
 $pdo=conectarBanco();$pdo->beginTransaction();
 $stmt=$pdo->prepare('SELECT status FROM pedidos WHERE id=? FOR UPDATE');$stmt->execute([$id]);$anterior=$stmt->fetchColumn();
 if($anterior===false){$pdo->rollBack();definirMensagem('erro','Pedido não encontrado.');redirecionar('pedidos.php');}
 if($anterior!==$status){
  $pdo->prepare('UPDATE pedidos SET status=? WHERE id=?')->execute([$status,$id]);
  $pdo->prepare('INSERT INTO pedido_historico (pedido_id,administrador_id,status_anterior,status_novo) VALUES (?,?,?,?)')->execute([$id,$admin['id'],$anterior,$status]);
 }
 $pdo->commit();definirMensagem('sucesso','Situação do pedido #'.$id.' atualizada.');
}catch(Throwable $erro){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();registrarFalha($erro);definirMensagem('erro','Não foi possível atualizar a situação do pedido.');}
redirecionar('pedidos.php');
