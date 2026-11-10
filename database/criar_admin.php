<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
if(PHP_SAPI!=='cli'){http_response_code(403);exit("Execute pelo terminal.\n");}
if($argc<4){fwrite(STDERR,"Uso: php database/criar_admin.php \"Nome completo\" usuario email\n");exit(1);}
$nome=trim($argv[1]);$usuario=trim($argv[2]);$email=trim($argv[3]);
if($nome===''||strlen($nome)>120||!preg_match('/^[A-Za-z0-9._-]{3,50}$/',$usuario)||filter_var($email,FILTER_VALIDATE_EMAIL)===false){fwrite(STDERR,"Dados inválidos.\n");exit(1);}
if(PHP_OS_FAMILY!=='Windows'&&function_exists('shell_exec'))shell_exec('stty -echo');
fwrite(STDOUT,'Senha do administrador (mínimo 10 caracteres): ');$senha=trim((string)fgets(STDIN));
if(PHP_OS_FAMILY!=='Windows'&&function_exists('shell_exec')){shell_exec('stty echo');fwrite(STDOUT,PHP_EOL);}
if(strlen($senha)<10||strlen($senha)>72){fwrite(STDERR,"A senha deve ter entre 10 e 72 bytes.\n");exit(1);}
try{
 $pdo=conectarBanco();$total=(int)$pdo->query('SELECT COUNT(*) FROM administradores')->fetchColumn();
 if($total>0){fwrite(STDERR,"Já existe administrador. O assistente inicial não cria contas adicionais.\n");exit(1);}
 $pdo->prepare('INSERT INTO administradores(nome,usuario,email,senha_hash) VALUES(?,?,?,?)')->execute([$nome,$usuario,$email,password_hash($senha,PASSWORD_DEFAULT)]);
 fwrite(STDOUT,"Administrador criado com sucesso.\n");
}catch(Throwable $erro){registrarFalha($erro);fwrite(STDERR,"Não foi possível criar a conta. Confira banco e dados.\n");exit(1);}
