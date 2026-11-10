<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
exigirLogin(); $pdo=conectarBanco();
$filtro=trim(is_string($_GET['status']??null)?$_GET['status']:'');
$busca=trim(is_string($_GET['q']??null)?$_GET['q']:'');
$estados=['Pendente','Confirmado','Em preparação','Entregue','Cancelado']; $where=[]; $params=[];
if($filtro!=='' && in_array($filtro,$estados,true)){$where[]='status=:status';$params[':status']=$filtro;}
if($busca!==''){$where[]='(nome LIKE :busca_nome OR telefone LIKE :busca_telefone OR email LIKE :busca_email OR CAST(id AS CHAR)=:id)';$termoBusca='%'.addcslashes($busca,'%_\\\\').'%';$params[':busca_nome']=$termoBusca;$params[':busca_telefone']=$termoBusca;$params[':busca_email']=$termoBusca;$params[':id']=$busca;}
$sql='SELECT * FROM pedidos'.($where?' WHERE '.implode(' AND ',$where):'').' ORDER BY criado_em DESC LIMIT 200';
$stmt=$pdo->prepare($sql);$stmt->execute($params);$pedidos=$stmt->fetchAll();
$flash=obterMensagem();$tipos=['pacote-5'=>'Pacote de R$ 5,00','pacote-7'=>'Pacote de R$ 7,00','ambos'=>'Ambos os tamanhos','atacado'=>'Atacado','duvida'=>'Dúvida / informação'];
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Pedidos | Painel Gelos Carvalho</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/admin.css"></head><body class="admin-body">
<header class="admin-header"><a class="admin-brand" href="index.php">🧊 <span>Gelos Carvalho</span></a><nav><a href="index.php">Painel</a><a class="admin-nav-active" href="pedidos.php">Pedidos</a><a href="../index.html" target="_blank" rel="noopener">Ver site ↗</a></nav><form method="post" action="logout.php"><?=campoCsrf()?><button class="admin-logout" type="submit">Sair</button></form></header>
<main class="admin-main"><div class="admin-heading"><div><p class="admin-eyebrow">Atendimento</p><h1>Pedidos recebidos</h1><p>Consulte os dados enviados pelo cliente e atualize o andamento.</p></div><a class="admin-button admin-button-secondary admin-button-link" href="index.php">← Painel</a></div>
<?php if($flash):?><div class="notice notice-<?=e($flash['tipo'])?>"><?=e($flash['texto'])?></div><?php endif;?>
<section class="admin-card"><form class="filters-form" method="get" action="pedidos.php"><label>Pesquisar<input type="search" name="q" value="<?=e($busca)?>" placeholder="Nome, telefone, e-mail ou número"></label><label>Status<select name="status"><option value="">Todos</option><?php foreach($estados as $estado):?><option value="<?=e($estado)?>" <?=$filtro===$estado?'selected':''?>><?=e($estado)?></option><?php endforeach;?></select></label><button class="admin-button" type="submit">Filtrar</button><a class="filter-clear" href="pedidos.php">Limpar</a></form>
<?php if(!$pedidos):?><p class="empty-state">Nenhum pedido corresponde aos filtros informados.</p><?php else:?><div class="orders-list"><?php foreach($pedidos as $p):?>
<article class="order-card"><div class="order-top"><div><span class="order-number">Pedido #<?=(int)$p['id']?></span><h2><?=e($p['nome'])?></h2><p><?=e($p['telefone'])?><?=$p['email']?' · '.e($p['email']):''?></p></div><span class="status"><?=e($p['status'])?></span></div>
<div class="order-details"><p><strong>Tipo:</strong> <?=e($tipos[$p['tipo_pedido']??'']??'Não informado')?></p><p><strong>Quantidade:</strong> <?=$p['quantidade']===null?'Não informada':(int)$p['quantidade'].' pacote(s)'?></p><p><strong>Recebido:</strong> <?=e(date('d/m/Y H:i',strtotime($p['criado_em'])))?></p><p class="order-message"><strong>Mensagem:</strong><br><?=nl2br(e($p['mensagem']))?></p></div>
<form class="status-form" method="post" action="atualizar_status.php"><?=campoCsrf()?><input type="hidden" name="pedido_id" value="<?=(int)$p['id']?>"><label for="status-<?=(int)$p['id']?>">Atualizar situação<select id="status-<?=(int)$p['id']?>" name="status" required><?php foreach($estados as $estado):?><option value="<?=e($estado)?>" <?=$p['status']===$estado?'selected':''?>><?=e($estado)?></option><?php endforeach;?></select></label><button class="admin-button admin-button-small" type="submit">Salvar status</button></form></article>
<?php endforeach;?></div><?php endif;?><p class="results-note">Exibindo até 200 pedidos mais recentes.</p></section></main></body></html>
