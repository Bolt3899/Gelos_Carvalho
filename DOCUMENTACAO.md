# Documentação Técnica — Gelos Carvalho

**Projeto:** Site institucional e sistema de gerenciamento de pedidos  
**Desenvolvedor:** Arthur Carvalho de Souza  
**Instituição:** Instituto Federal Baiano — Campus Bom Jesus da Lapa  
**Ano:** 2026

---

## 1. Introdução

A Gelos Carvalho é apresentada neste projeto por meio de um site institucional voltado à divulgação da empresa, de seus produtos e de seus canais de atendimento. A evolução do projeto acrescenta uma aplicação de servidor para registrar solicitações enviadas pelo formulário público e permitir que a equipe acompanhe o atendimento em uma área administrativa.

O sistema não é uma loja virtual com pagamento eletrônico. O formulário registra uma solicitação de compra ou contato; a confirmação de disponibilidade, preço final, entrega e demais condições continua dependendo do contato entre a empresa e o cliente.

## 2. Problema e justificativa

Um formulário que apenas simula o envio não garante que a empresa receba ou armazene a solicitação. Sem persistência, os dados podem ser perdidos ao fechar ou atualizar a página, e não há um local central para acompanhar os pedidos.

A integração com PHP e MySQL resolve essa limitação ao receber os dados no servidor, validá-los, armazená-los e disponibilizá-los em um painel protegido por autenticação.

## 3. Objetivos

### 3.1 Objetivo geral
Desenvolver um site institucional para a Gelos Carvalho com mecanismo de registro e acompanhamento administrativo de pedidos.

### 3.2 Objetivos específicos
- Apresentar a empresa, seus produtos, serviços, imagens e formas de contato.
- Permitir que visitantes enviem solicitações por formulário.
- Validar os dados recebidos no servidor.
- Armazenar pedidos em banco de dados relacional.
- Disponibilizar acesso administrativo autenticado.
- Permitir consulta, pesquisa, filtragem e atualização da situação dos pedidos.
- Registrar alterações de situação para manter rastreabilidade do atendimento.

## 4. Escopo do sistema

### 4.1 Funcionalidades da área pública
- Página inicial com apresentação da empresa e destaques.
- Página Sobre com informações institucionais.
- Página Serviços com os produtos e informações comerciais.
- Página Galeria com imagens e filtros.
- Página Contato com endereço, telefone, WhatsApp, formulário e informações de atendimento.
- Envio de formulário com nome, telefone, e-mail opcional, tipo de pedido, quantidade opcional e mensagem obrigatória.
- Retorno visual de sucesso ou erro após o processamento.

### 4.2 Funcionalidades administrativas
- Autenticação de administrador por usuário ou e-mail e senha.
- Painel com indicadores gerais.
- Consulta dos pedidos recentes.
- Pesquisa por nome, telefone, e-mail ou número do pedido.
- Filtro por situação.
- Visualização das informações enviadas pelo cliente.
- Atualização do status do pedido.
- Registro do histórico das mudanças de status.
- Encerramento seguro da sessão.

### 4.3 Fora do escopo atual
- Pagamento on-line ou integração com gateway financeiro.
- Controle automático de estoque.
- Cálculo automático de frete ou rota de entrega.
- Confirmação automática de disponibilidade.
- Disparo automático de mensagens por WhatsApp ou e-mail.
- Aplicativo móvel nativo.

## 5. Requisitos funcionais

| Código | Requisito |
|---|---|
| RF01 | O sistema deve apresentar a página inicial da empresa. |
| RF02 | O sistema deve disponibilizar a página institucional Sobre. |
| RF03 | O sistema deve apresentar os produtos e serviços oferecidos. |
| RF04 | O sistema deve disponibilizar uma galeria de imagens com filtros por categoria. |
| RF05 | O sistema deve apresentar os canais de contato da empresa. |
| RF06 | O visitante deve poder enviar uma solicitação com nome, telefone e mensagem obrigatórios. |
| RF07 | O visitante deve poder informar e-mail, tipo de pedido e quantidade, quando aplicável. |
| RF08 | O servidor deve validar os dados recebidos antes de gravar o pedido. |
| RF09 | O sistema deve armazenar os pedidos no banco de dados. |
| RF10 | O sistema deve apresentar retorno de sucesso ou falha após o envio. |
| RF11 | O administrador deve autenticar-se para acessar o painel. |
| RF12 | O painel deve apresentar indicadores dos pedidos por situação. |
| RF13 | O administrador deve consultar e pesquisar pedidos registrados. |
| RF14 | O administrador deve filtrar pedidos por situação. |
| RF15 | O administrador deve alterar a situação de um pedido. |
| RF16 | O sistema deve registrar o histórico das alterações de situação. |
| RF17 | O administrador deve poder encerrar sua sessão. |

## 6. Requisitos não funcionais

| Código | Requisito |
|---|---|
| RNF01 | A interface deve manter identidade visual coerente com o site original. |
| RNF02 | As páginas devem adaptar-se a diferentes larguras de tela. |
| RNF03 | O backend deve utilizar PHP 8.1 ou superior. |
| RNF04 | A persistência deve utilizar MySQL ou MariaDB com codificação utf8mb4. |
| RNF05 | O acesso ao banco deve ser realizado por PDO e consultas parametrizadas. |
| RNF06 | Senhas administrativas devem ser armazenadas por hash seguro, nunca em texto puro. |
| RNF07 | Operações por formulário devem possuir proteção contra CSRF. |
| RNF08 | Dados inseridos pelo usuário devem ser validados no servidor e escapados na apresentação. |
| RNF09 | A área administrativa deve exigir autenticação. |
| RNF10 | O sistema deve evitar a exposição de detalhes técnicos de erros ao visitante. |
| RNF11 | A implantação deve ocorrer em servidor com suporte a PHP e MySQL; hospedagem estática não é suficiente. |
| RNF12 | O ambiente de produção deve utilizar HTTPS e credenciais próprias, não as credenciais padrão do ambiente local. |

## 7. Regras de negócio

- **RN01:** Nome, telefone e mensagem são obrigatórios para registrar uma solicitação.
- **RN02:** O e-mail pode ser omitido; quando informado, deve possuir formato válido.
- **RN03:** A quantidade, quando informada, deve ser um inteiro positivo.
- **RN04:** Os tipos de solicitação aceitos são pacote de R$ 5,00, pacote de R$ 7,00, ambos os tamanhos, atacado e dúvida/informação.
- **RN05:** Todo novo pedido recebe a situação inicial “Pendente”.
- **RN06:** As situações disponíveis são “Pendente”, “Confirmado”, “Em preparação”, “Entregue” e “Cancelado”.
- **RN07:** Somente administradores autenticados podem consultar pedidos e alterar sua situação.
- **RN08:** A alteração de situação deve ser registrada no histórico, com situação anterior, nova situação, administrador e data.
- **RN09:** O registro de uma solicitação não representa confirmação automática de venda, estoque, entrega ou preço final.
- **RN10:** O painel não disponibiliza exclusão de pedidos, preservando o registro do atendimento.

## 8. Casos de uso

| Código | Caso de uso | Ator | Descrição |
|---|---|---|---|
| CSU01 | Navegar pelo site | Visitante | Acessa as páginas públicas e consulta as informações institucionais. |
| CSU02 | Consultar produtos | Visitante | Visualiza os produtos e serviços divulgados. |
| CSU03 | Consultar galeria | Visitante | Visualiza imagens e utiliza os filtros disponíveis. |
| CSU04 | Enviar pedido ou mensagem | Visitante | Preenche o formulário e envia uma solicitação. |
| CSU05 | Validar e registrar solicitação | Sistema | Valida os campos e grava o pedido no banco. |
| CSU06 | Realizar login | Administrador | Informa usuário/e-mail e senha para acessar o painel. |
| CSU07 | Visualizar painel | Administrador | Consulta indicadores e pedidos recentes. |
| CSU08 | Pesquisar e filtrar pedidos | Administrador | Localiza pedidos por dados do cliente, número ou situação. |
| CSU09 | Atualizar situação do pedido | Administrador | Altera a situação e registra a mudança no histórico. |
| CSU10 | Encerrar sessão | Administrador | Encerra a sessão autenticada. |

## 9. Arquitetura e tecnologias

A aplicação segue uma separação simples entre apresentação, processamento e persistência:

- **Apresentação:** HTML5 e CSS3, com JavaScript para interações da interface.
- **Servidor:** PHP, responsável por validar os formulários, autenticar administradores e executar as operações do sistema.
- **Persistência:** MySQL/MariaDB, acessado por PDO.
- **Sessão e segurança:** sessões PHP, hash de senha, tokens CSRF e escape de saída.

A conexão com o banco fica centralizada em `config/database.php`. O arquivo `includes/bootstrap.php` reúne inicialização de sessão, funções de segurança, autenticação e mensagens temporárias.

### 9.1 Organização dos arquivos principais

```text
Gelos_Carvalho/
├── index.html
├── sobre.html
├── servicos.html
├── galeria.html
├── contato.php
├── contato.html
├── pedido.php
├── login.php
├── admin/
│   ├── index.php
│   ├── pedidos.php
│   ├── atualizar_status.php
│   └── logout.php
├── config/
│   └── database.php
├── includes/
│   └── bootstrap.php
├── database/
│   ├── gelos_carvalho.sql
│   └── criar_admin.php
├── css/
│   ├── style.css
│   └── admin.css
├── img/
└── audio/
```

## 10. Modelo de dados

### 10.1 Tabela administradores
Armazena as contas que podem acessar o painel.

- `id`: identificador.
- `nome`: nome de exibição.
- `usuario`: nome de usuário único.
- `email`: e-mail único.
- `senha_hash`: hash da senha.
- `ativo`: indica se a conta pode autenticar-se.
- `criado_em`: data de criação.
- `ultimo_acesso`: último login registrado.

### 10.2 Tabela pedidos
Armazena as solicitações recebidas pelo formulário.

- `id`: identificador.
- `nome`, `telefone`, `email`: dados de contato.
- `tipo_pedido`: categoria solicitada.
- `quantidade`: quantidade de pacotes, quando informada.
- `mensagem`: conteúdo enviado pelo visitante.
- `status`: situação atual.
- `criado_em`, `atualizado_em`: datas de registro e atualização.

### 10.3 Tabela pedido_historico
Armazena as mudanças de situação dos pedidos.

- `id`: identificador.
- `pedido_id`: pedido relacionado.
- `administrador_id`: conta que realizou a alteração.
- `status_anterior`, `status_novo`: estados antes e depois da mudança.
- `alterado_em`: data da alteração.

## 11. Segurança

O backend utiliza consultas preparadas do PDO para evitar que os valores enviados pelo visitante sejam interpretados como comandos SQL. As senhas são armazenadas com `password_hash` e verificadas por `password_verify`. O painel verifica a existência de uma sessão autenticada.

Os formulários de envio, login, alteração de situação e logout utilizam token CSRF. O identificador da sessão é regenerado após autenticação. Os dados exibidos são escapados com `htmlspecialchars`. Detalhes de exceções são registrados no log do servidor, sem serem apresentados diretamente ao visitante.

Em ambiente de produção, devem ser configurados HTTPS, usuário de banco com privilégios mínimos, senha forte, permissões adequadas de arquivos e rotina de backup.

## 12. Instalação e configuração

### 12.1 Requisitos
- PHP 8.1 ou superior.
- MySQL ou MariaDB.
- Extensões PHP `pdo_mysql` e `mbstring`.
- Apache ou outro servidor web configurado para executar PHP.

### 12.2 Execução local no XAMPP
1. Colocar o projeto em `C:\xampp\htdocs\Gelos_Carvalho`.
2. Iniciar Apache e MySQL.
3. Importar `database/gelos_carvalho.sql` pelo phpMyAdmin.
4. Conferir as configurações de conexão em `config/database.php`.
5. No terminal, acessar a pasta do projeto e executar:

   ```bash
   php database/criar_admin.php "Nome do Administrador" usuario admin@exemplo.com
   ```

6. Informar uma senha forte quando solicitada pelo terminal.
7. Acessar o site em `http://localhost/Gelos_Carvalho/`.
8. Acessar o painel em `http://localhost/Gelos_Carvalho/login.php`.

Os valores padrão da conexão são destinados ao XAMPP local. Para hospedagem, devem ser definidas as variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS`. O GitHub Pages não executa PHP e, portanto, não pode hospedar esta versão dinâmica.

## 13. Plano de testes

Os testes abaixo devem ser executados em ambiente PHP/MySQL após a importação do banco. Eles constituem um plano de validação, não uma declaração de que foram executados.

| ID | Cenário | Resultado esperado |
|---|---|---|
| T01 | Abrir cada página pública | Páginas carregam e navegação funciona. |
| T02 | Enviar formulário sem nome | Servidor rejeita a solicitação. |
| T03 | Enviar telefone inválido | Servidor rejeita a solicitação. |
| T04 | Enviar e-mail inválido | Servidor rejeita a solicitação. |
| T05 | Enviar formulário válido | Pedido é gravado e mensagem de sucesso é exibida. |
| T06 | Conferir pedido no banco | Dados enviados aparecem na tabela pedidos. |
| T07 | Abrir painel sem login | Acesso é redirecionado para autenticação. |
| T08 | Login com credenciais inválidas | Acesso é negado sem revelar qual campo está incorreto. |
| T09 | Login com credenciais válidas | Administrador acessa o painel. |
| T10 | Pesquisar pedido | Resultados correspondem ao termo informado. |
| T11 | Filtrar por situação | Apenas pedidos da situação selecionada são exibidos. |
| T12 | Atualizar situação | Estado é alterado e histórico é gravado. |
| T13 | Enviar formulário sem token CSRF | Operação é recusada. |
| T14 | Encerrar sessão | Sessão é invalidada e painel deixa de ser acessível. |
| T15 | Abrir em dispositivo móvel | Conteúdo permanece utilizável em tela menor. |

## 14. Estado da implementação e limitações

A implementação do backend e do painel foi preparada na branch `backend-php-pedidos`. A integração depende da configuração de PHP e MySQL e da execução dos testes de instalação e fluxo no ambiente local ou de hospedagem.

Não se deve considerar o sistema publicado em produção apenas porque os arquivos foram enviados ao GitHub. A implantação exige servidor com PHP, banco importado, conta administrativa criada, HTTPS e configuração das credenciais.

Não há processamento de pagamento, envio automático de mensagens, confirmação de estoque ou cálculo de entrega nesta versão.

## 15. Referências técnicas

- PHP Documentation. Disponível em: https://www.php.net/docs.php
- PHP Data Objects (PDO). Disponível em: https://www.php.net/manual/pt_BR/book.pdo.php
- MySQL Documentation. Disponível em: https://dev.mysql.com/doc/
- OWASP Cheat Sheet Series — Cross-Site Request Forgery Prevention. Disponível em: https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html
- OWASP Cheat Sheet Series — Password Storage. Disponível em: https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html
