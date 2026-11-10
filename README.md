# Gelos Carvalho — Site institucional e gerenciamento de pedidos

Sistema web da Gelos Carvalho, empresa de fabricação e venda de gelo em Serra do Ramalho — BA. O projeto preserva as páginas institucionais e acrescenta backend PHP para registrar solicitações e gerenciar o atendimento.

## Funcionalidades

### Área pública
- Páginas Início, Sobre, Serviços, Galeria e Contato.
- Formulário processado no servidor e validado em PHP.
- Registro dos pedidos no MySQL, com dados de contato, tipo, quantidade e mensagem.
- Mensagens de confirmação ou falha após o envio.
- Atalhos de telefone e WhatsApp mantidos.

### Área administrativa
- Login de administrador.
- Painel com indicadores de pedidos registrados, pendentes, em andamento e entregues.
- Listagem dos pedidos recentes.
- Pesquisa por nome, telefone, e-mail ou número do pedido e filtro por situação.
- Consulta dos dados enviados pelo cliente.
- Atualização de situação: Pendente, Confirmado, Em preparação, Entregue ou Cancelado.
- Histórico das alterações de situação, com identificação do administrador.

## Tecnologias
HTML5, CSS3 e JavaScript; PHP 8.1+; MySQL/MariaDB; PDO para acesso ao banco; sessões PHP e tokens CSRF.

## Estrutura principal
- `index.html`, `sobre.html`, `servicos.html`, `galeria.html`: páginas institucionais.
- `contato.php`: formulário público integrado; `contato.html` redireciona para a nova versão.
- `pedido.php`: validação e gravação de pedidos.
- `login.php`: autenticação administrativa.
- `admin/index.php`: painel e indicadores.
- `admin/pedidos.php`: consulta e filtro.
- `admin/atualizar_status.php`: atualização de situação.
- `admin/logout.php`: encerramento de sessão.
- `config/database.php`: conexão PDO.
- `includes/bootstrap.php`: sessão, CSRF, autenticação e mensagens.
- `database/gelos_carvalho.sql`: estrutura do banco.
- `database/criar_admin.php`: criação da primeira conta.
- `css/style.css`: identidade visual pública original.
- `css/admin.css`: interface administrativa.

## Requisitos
PHP 8.1 ou superior, MySQL/MariaDB, extensões `pdo_mysql` e `mbstring), e servidor web com suporte a PHP (por exemplo, XAMPP). GitHub Pages não executa PHP.

## Instalação local com XAMPP
1. Coloque o projeto em `C:\xampp\htdocs\Gelos_Carvalho`.
2. Inicie Apache e MySQL.
3. Importe `database/gelos_carvalho.sql` pelo phpMyAdmin.
4. Confira `config/database.php`. Os valores padrão são host `127.0.0.1`, porta `3306`, banco `gelos_carvalho`, usuário `root` e senha vazia (configuração comum do XAMPP local).
5. No terminal, acesse a pasta do projeto e execute:

   ```bash
   php database/criar_admin.php "Nome do Administrador" usuario admin@exemplo.com
   ```

   O script solicitará a senha no terminal. Use uma senha forte com pelo menos 10 caracteres. O assistente cria somente a primeira conta administrativa.
6. Acesse `http://localhost/Gelos_Carvalho/` para abrir o site.
7. Acesse `http://localhost/Gelos_Carvalho/login.php` para entrar no painel.

## Configuração de hospedagem
Configure as variáveis de ambiente `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS` com os dados do provedor. Importe o banco antes de ativar o formulário. Em produção, use HTTPS, credenciais exclusivas e permissões mínimas para o usuário do banco. Não publique senhas reais.

## Modelo de dados
- `administradores`: contas administrativas, hash da senha, ativação e último acesso.
- `pedidos`: informações do cliente, quantidade, mensagem, estado e datas.
- `pedido_historico`: estado anterior/novo, administrador responsável e data da alteração.

O painel não permite excluir pedidos, preservando o registro do atendimento.

## Segurança
- Senhas armazenadas com `password_hash` e verificadas com `password_verify`.
- Consultas parametrizadas por PDO.
- Validação dos campos no servidor.
- Proteção CSRF no envio, login, alteração de situação e logout.
- Regeneração do identificador de sessão após autenticação.
- Cookies `HttpOnly` e `SameSite=Lax`; `Secure` quando HTTPS está ativo.
- Conteúdo exibido escapado com `htmlspecialchars`.
- Erros técnicos são registrados no log do servidor, sem expor detalhes ao visitante.

## Limitações e próximos passos
- O sistema registra solicitações, mas não processa pagamentos.
- O registro não confirma automaticamente estoque, preço final, entrega ou disponibilidade. A equipe deve contatar o cliente para confirmar.
- Não há envio automático por e-mail ou WhatsApp.
- Domínio, HTTPS, credenciais e backups dependem da configuração da hospedagem.
- É necessário executar os testes de integração no servidor após importar o banco; o envio ao GitHub, por si só, não representa implantação concluída.

## Contexto acadêmico
**Desenvolvedor:** Arthur Carvalho de Souza  
**Instituição:** Instituto Federal Baiano — Campus Bom Jesus da Lapa  
**Projeto:** Gelos Carvalho — site institucional e gerenciamento de pedidos.
