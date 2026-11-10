<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
$admin=exigirLogin(); $pdo=conectarBanco();
$total=(int)$pdo->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
$pendentes=(int)$pdo->query("SELECT COUNT(*) FROM pedidos WHERE status='Pendente'")->fetchColumn();
$andamento=(int)$pdo->query("SELECT COUNT(*) FROM pedidos WHERE status IN ('Confirmado','Em preparação')")->fetchColumn();
$entregues=(int)$pdo->query("SELECT COUNT(*) FROM pedidos WHERE status='Entregue'")->fetchColumn();
$recentes=$pdo->query('SELECT id,nome,telefone,tipo_pedido,quantidade,status,criado_em FROM pedidos ORDER BY criado_em DESC LIMIT 8')->fetchAll();
$flash=obterMensagem(); $tipos=['pacote-5'=>'Pacote de R$ 5,00','pacote-7'=>'Pacote de R$ 7,00','ambos'=>'Ambos os tamanhos','atacado'=>'Atacado','duvida'=>'Dúvida / informação'];
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Painel | Gelos Carvalho</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/admin.css"></head><body class="admin-body">
<header class="admin-header"><a class="admin-brand" href="index.php">🧊 <span>Gelos Carvalho</span></a><nav><a class="admin-nav-active" href="index.php">Painel</a><a href="pedidos.php">Pedidos</a><a href="../index.html" target="_blank" rel="noopener">Ver site ↗</a></nav><form method="post" action="logout.php"><?=campoCsrf()?><button class="admin-logout" type="submit">Sair</button></form></header>
<main class="admin-main"><div class="admin-heading"><div><p class="admin-eyebrow">Visão geral</p><h1>Painel administrativo</h1><p>Olá, <?=e($admin['nome'])?>. Acompanhe os pedidos recebidos pelo site.</p></div><a class="admin-button admin-button-link" href="pedidos.php">Consultar pedidos</a></div>
<?php if($flash):?><div class="notice notice-<?=e($flash['tipo'])?>"><?=e($flash['texto'])?></div><?php endif;?>
<section class="stats-grid"><article class="stat-card"><span>Pedidos registrados</span><strong><?=$total?></strong></article><article class="stat-card"><span>Aguardando atendimento</span><strong><?=$pendentes?></strong></article><article class="stat-card"><span>Em andamento</span><strong><?=$andamento?></strong></article><article class="stat-card"><span>Entregues</span><strong><?=$entregues?></strong></article></section>
<section class="admin-card"><div class="section-heading"><div><p class="admin-eyebrow">Entradas recentes</p><h2>Últimos pedidos</h2></div><a href="pedidos.php">Ver todos →</a></div>
<?php if(!$recentes):?><p class="empty-state">Nenhum pedido foi recebido ainda.</p><?php else:?><div class="table-wrap"><table class="orders-table"><thead><tr><th>Pedido</th><th>Cliente</th><th>Produto / tipo</th><th>Quantidade</th><th>Status</th><th>Recebido em</th></tr></thead><tbody>
<?php foreach($recentes as $p):?><tr><td>#<?=(int)$p['id']?></td><td><strong><?=e($p['nome'])?></strong><small><?=e($p['telefone'])?></small></td><td><?=e($tipos[$p['tipo_pedido']??'']??'Não informado')?></td><td><?=$p['quantidade']===null?'—':(int)$p['quantidade']?></td><td><span class="status"><?=e($p['status'])?></span></td><td><?=e(date('d/m/Y H:i',strtotime($p['criado_em'])))?></td></tr><?php endforeach;?>
</tbody></table></div><?php endif;?></section></main></body></html>
