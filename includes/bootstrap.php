<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
ini_set('display_errors', '0');
if (PHP_SAPI !== 'cli') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Cache-Control: no-store');
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_name('gelos_carvalho_sessao');
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
function e(?string $valor): string { return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirecionar(string $destino): never { header('Location: '.$destino); exit; }
function tokenCsrf(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function campoCsrf(): string { return '<input type="hidden" name="csrf_token" value="'.e(tokenCsrf()).'">'; }
function csrfValido(): bool {
    $enviado = $_POST['csrf_token'] ?? null;
    return is_string($enviado) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $enviado);
}
function exigirCsrf(): void {
    if (!csrfValido()) { http_response_code(403); exit('A verificação de segurança falhou. Atualize a página e tente novamente.'); }
}
function definirMensagem(string $tipo, string $texto): void { $_SESSION['mensagem_flash']=['tipo'=>$tipo,'texto'=>$texto]; }
function obterMensagem(): ?array {
    $mensagem=$_SESSION['mensagem_flash']??null; unset($_SESSION['mensagem_flash']);
    return is_array($mensagem)?$mensagem:null;
}
function exigirLogin(): array {
    if (empty($_SESSION['administrador_id'])) { definirMensagem('erro','Entre com sua conta para acessar o painel.'); redirecionar('../login.php'); }
    return ['id'=>(int)$_SESSION['administrador_id'],'nome'=>(string)($_SESSION['administrador_nome']??'Administrador')];
}
function registrarFalha(Throwable $erro): void {
    error_log(sprintf('Gelos Carvalho: %s (código %s) em %s:%d',get_class($erro),(string)$erro->getCode(),basename($erro->getFile()),$erro->getLine()));
}
