<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
if (!empty($_SESSION['administrador_id'])) redirecionar('admin/index.php');
$erro=''; $identificacao='';
if (($_SERVER['REQUEST_METHOD']??'')==='POST') {
    $identificacao=trim(is_string($_POST['identificacao']??null)?$_POST['identificacao']:'');
    $senha=is_string($_POST['senha']??null)?$_POST['senha']:'';
    if (!csrfValido()) $erro='Sua sessão expirou. Atualize a página e tente novamente.';
    elseif ($identificacao==='' || $senha==='') $erro='Informe usuário/e-mail e senha.';
    else try {
        $stmt=conectarBanco()->prepare('SELECT id,nome,senha_hash,ativo FROM administradores WHERE usuario=:usuario OR email=:email LIMIT 1');
        $stmt->execute([':usuario'=>$identificacao, ':email'=>$identificacao]); $conta=$stmt->fetch();
        if ($conta && (int)$conta['ativo']===1 && password_verify($senha,$conta['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['administrador_id']=(int)$conta['id'];
            $_SESSION['administrador_nome']=$conta['nome'];
            $_SESSION['csrf_token']=bin2hex(random_bytes(32));
            conectarBanco()->prepare('UPDATE administradores SET ultimo_acesso=NOW() WHERE id=?')->execute([$conta['id']]);
            redirecionar('admin/index.php');
        }
        $erro='Usuário ou senha inválidos.';
    } catch (Throwable $falha) { registrarFalha($falha); $erro='Não foi possível validar o acesso. Verifique a configuração do banco.'; }
}
$flash=obterMensagem();
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Entrar | Painel Gelos Carvalho</title><link rel="stylesheet" href="css/style.css"><link rel="stylesheet" href="css/admin.css"></head>
<body class="admin-body"><main class="login-shell"><section class="admin-card login-card">
<a class="admin-brand" href="index.html">🧊 <span>Gelos Carvalho</span></a><p class="admin-eyebrow">Área restrita</p><h1>Acesso administrativo</h1>
<?php if($flash):?><div class="notice notice-<?=e($flash['tipo'])?>"><?=e($flash['texto'])?></div><?php endif;?>
<?php if($erro!==''):?><div class="notice notice-erro" role="alert"><?=e($erro)?></div><?php endif;?>
<form method="post" action="login.php"><?=campoCsrf()?><label for="identificacao">Usuário ou e-mail</label><input id="identificacao" name="identificacao" type="text" autocomplete="username" value="<?=e($identificacao)?>" required><label for="senha">Senha</label><input id="senha" name="senha" type="password" autocomplete="current-password" required><button class="admin-button" type="submit">Entrar no painel</button></form>
<a class="back-link" href="index.html">← Voltar ao site</a></section></main></body></html>
