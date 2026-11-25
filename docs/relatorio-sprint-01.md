# SPRINT 01 — Cadastro e gestão de usuários

**Projeto:** PULSE  
**Entrega:** Relatório consolidado dos Pontos 01 a 10  
**Data:** 24/11/2026  
**Ambiente:** Desenvolvimento local — XAMPP  
**Versão do código:** a confirmar no pacote final  
**Responsável pela execução:** Bruno Renato

---

## Resumo executivo

O módulo de cadastro e gestão de usuários foi integrado ao PHP e ao MySQL/MariaDB, com validação no servidor, persistência real, consultas SELECT e JOIN, autorização administrativa, confirmação de exclusão e baixa lógica auditada. Os dez pontos possuem registro de implementação; a entrega integrada ainda depende da conferência do pacote final e das evidências.

O banco original `db_pulse` foi preservado durante os testes estruturais. A homologação utilizou `db_pulse_homologacao` e a aplicação foi testada em `db_pulse_instalacao_teste`. As contagens de usuários ao longo do relatório correspondem a momentos distintos dos testes.

**Convenção de evidências:** os diretórios são indicados a partir da raiz do projeto (`docs/evidencias/`); os links Markdown são relativos ao arquivo em `docs/` (`evidencias/`). Os resultados são os registrados pela equipe, não uma nova execução independente.

## PONTO 01 — Diagnóstico do módulo e organização do trabalho

### 1. Objetivo

Realizar o levantamento da estrutura atual do sistema PULSE, identificando páginas, rotas, arquivos PHP, dependências, conexão e banco de dados, com o objetivo de planejar a implementação do módulo de gestão de usuários sem comprometer as funcionalidades existentes.

### 2. Ambiente de desenvolvimento

| Componente | Configuração |
|---|---|
| Ambiente local | XAMPP |
| Apache | 2.4.71 |
| PHP | 8.5.13 |
| MariaDB | 10.11.19 |
| phpMyAdmin | 5.2.3 |
| Banco de dados | db_pulse |
| Editor de código | Visual Studio Code |
| Endereço da aplicação | http://localhost/pulse/ |

### 3. Mapa de arquivos e rotas

| Arquivo | Finalidade |
|---|---|
| `index.php` | Página inicial |
| `cadastro.php` | Cadastro de usuários |
| `login.php` | Autenticação |
| `dashboard.php` | Painel do sistema |
| `produtos.php` | Catálogo de produtos |
| `carrinho.php` | Carrinho de compras |
| `pagamento.php` | Página de pagamento |
| `sobre.php` | Informações institucionais |
| `contato.php` | Página de contato |
| `database/conexao.php` | Conexão PDO com o banco |
| `database/instruction.sql` | Script SQL |
| `includes/auth.php` | Autenticação e gerenciamento de sessão |
| `js/site.js` | Scripts gerais da aplicação |
| `js/carrinho.js` | Gerenciamento do carrinho |
| `css/style.css` | Estilização |
| `img/` | Recursos de imagem |
| `font/` | Recursos tipográficos |
| `README.md` | Documentação geral |
| `docs/` | Documentação técnica e evidências |

As páginas PHP estão localizadas na raiz do projeto e são acessadas por meio do endereço local `http://localhost/pulse/`, seguido do nome do arquivo correspondente.

#### 3.1. Dependências identificadas

- O cadastro utiliza a conexão PDO para comunicação com o banco.
- O arquivo `includes/auth.php` disponibiliza recursos relacionados à autenticação, sessão e proteção CSRF.
- As páginas de produtos e carrinho utilizam os scripts JavaScript existentes.
- A identidade visual da aplicação é definida pelo arquivo `css/style.css`.
- O arquivo `database/instruction.sql` contém a estrutura SQL do projeto.

Não foi identificada uma página específica para listagem e gerenciamento de usuários.

### 4. Estado inicial do sistema

Durante o diagnóstico, foram identificadas as seguintes condições:

- Apache e serviço de banco de dados disponíveis.
- Página inicial acessível.
- Página de produtos acessível.
- Carrinho acessível diretamente.
- Adição e visualização de produtos funcionando.
- Formulário de cadastro existente.
- Banco de dados disponível no ambiente local.
- Estrutura de conexão PDO existente.
- Inconsistências nas rotas dos botões da página inicial.
- Ausência de funcionalidades específicas de gestão de usuários exigidas pela Sprint.

### 5. Problemas identificados e correções

#### 5.1. Inconsistência nas rotas da página inicial

Os botões da página inicial direcionavam para arquivos com extensão `.html`, embora as páginas correspondentes utilizassem a extensão `.php`.

O problema foi reproduzido durante os testes, apresentando erro 404.

**Arquivo alterado:** `index.php`

| Botão | Endereço anterior | Endereço corrigido |
|---|---|---|
| Ver Produtos | produtos.html | produtos.php |
| Saiba Mais | sobre.html | sobre.php |
| Comprar Agora | carrinho.html | carrinho.php |

Após a correção, os três botões foram testados novamente e passaram a funcionar.

**Situação:** corrigido e validado.

#### 5.2. Armazenamento do carrinho

Foram identificadas duas chaves diferentes de armazenamento no localStorage:

- `pulse_carrinho`
- `pulse_carrinho_itens`

Os arquivos relacionados são `js/carrinho.js` e `js/site.js`.

Apesar da possibilidade de conflito, os testes realizados não apresentaram falhas na permanência dos produtos, quantidades ou contador após a atualização da página.

**Situação:** funcionamento aprovado no cenário testado. Possível conflito permanece como ponto de atenção técnico.

### 6. Diagnóstico do formulário de cadastro

**Arquivo:** `cadastro.php`

O formulário existente apresenta os campos nome completo, e-mail e senha, além dos botões Cadastrar e Limpar.

| Recurso | Estado inicial |
|---|---|
| Nome completo | Existente |
| E-mail | Existente |
| Senha | Existente |
| Botão Cadastrar | Existente |
| Botão Limpar | Existente |
| Seleção de setor | Ausente |
| Seleção de perfil | Ausente |
| Validação de entrada | Necessita testes |
| Persistência no banco | Necessita testes |
| Estilização | Necessita adequações |

A estrutura existente será preservada e adaptada aos requisitos da Sprint, mantendo a identidade visual do PULSE.

### 7. Diagnóstico do banco de dados

**Banco identificado:** `db_pulse`

O banco instalado possui 11 tabelas:

| Tabela | Finalidade |
|---|---|
| carrinhos | Carrinhos de compras |
| categorias | Categorias de produtos |
| estoque | Controle de estoque |
| fornecedores | Cadastro de fornecedores |
| itens_carrinho | Produtos associados aos carrinhos |
| itens_pedido | Produtos associados aos pedidos |
| pagamentos | Informações de pagamentos |
| pedidos | Registro de pedidos |
| perfis_vendedor | Informações comerciais dos vendedores |
| produtos | Cadastro de produtos |
| usuarios | Cadastro de usuários |

Não foram identificadas tabelas específicas de setores, perfis gerais, sessões de usuários ou auditoria no banco instalado.

Foram observadas diferenças entre o banco instalado e a versão do script SQL analisada inicialmente. Essas diferenças deverão ser consideradas durante a atualização do banco.

#### 7.1. Estrutura da tabela usuarios

| Campo | Tipo | Característica |
|---|---|---|
| id | BIGINT UNSIGNED | PK e AUTO_INCREMENT |
| nome | VARCHAR(150) | Obrigatório |
| email | VARCHAR(190) | Obrigatório e UNIQUE |
| senha_hash | VARCHAR(255) | Obrigatório |
| telefone | VARCHAR(20) | Opcional |
| cpf | CHAR(11) | Opcional e UNIQUE |
| tipo | ENUM | Cliente, vendedor ou administrador |
| criado_em | TIMESTAMP | Data de criação |

**Recursos existentes que serão preservados:**

- Chave primária.
- Incremento automático.
- Unicidade de e-mail e CPF.
- Campo destinado ao hash da senha.
- Classificação dos usuários.
- Registro automático da data de criação.

**Adequações necessárias:**

- Relacionamento com setores.
- Relacionamento com perfis.
- Controle de status.
- Data de atualização.
- Data de baixa.
- Integração com auditoria.

#### 7.2. Estrutura da tabela perfis_vendedor

| Campo | Tipo |
|---|---|
| id | BIGINT UNSIGNED |
| usuario_id | BIGINT UNSIGNED |
| nome_loja | VARCHAR(150) |
| cnpj | CHAR(14) |
| status | ENUM |
| criado_em | TIMESTAMP |

A tabela possui chave primária e índices únicos nos campos usuario_id e cnpj.

Sua finalidade é armazenar informações comerciais dos vendedores vinculados ao PULSE.

**Decisão técnica proposta:** preservar essa tabela e criar uma estrutura específica para os perfis gerais dos usuários.

A existência da chave estrangeira deverá ser confirmada durante a análise dos relacionamentos no Ponto 02.

Nenhuma alteração estrutural foi realizada no banco durante o diagnóstico.

### 8. Diagnóstico da conexão PHP/MySQL

**Arquivo:** `database/conexao.php`

**Função:** `pulse_db()`

**Banco configurado no diagnóstico:** `db_pulse`

A conexão existente utiliza PDO e apresenta os seguintes recursos:

- Reutilização da instância de conexão.
- Codificação utf8mb4.
- Configuração de exceções.
- Retorno associativo dos resultados.
- Desativação da emulação de consultas preparadas.

Foi confirmada a correspondência entre o nome do banco configurado no PHP e o banco instalado no ambiente local.

**Adequações necessárias:**

- Conferir a sintaxe do arquivo.
- Melhorar o tratamento controlado de falhas.
- Organizar o armazenamento seguro das credenciais.
- Comprovar a conexão por meio de uma consulta real ao banco.

A validação operacional da conexão será realizada no Ponto 03.

### 9. Plano de implementação

| Arquivo ou recurso | Ação planejada | Entrega |
|---|---|---|
| database/instruction.sql | Adequar estrutura SQL | Ponto 02 |
| usuarios | Adaptar estrutura existente | Ponto 02 |
| setores | Criar tabela de referência | Ponto 02 |
| perfis | Criar tabela de referência | Ponto 02 |
| perfis_vendedor | Preservar | Ponto 02 |
| sessoes_usuarios | Avaliar e integrar rastreabilidade | Ponto 02 |
| auditoria_sistema | Adequar estrutura de auditoria | Pontos 02 e 10 |
| database/conexao.php | Validar e adequar conexão PDO | Ponto 03 |
| cadastro.php | Integrar setores e perfis | Ponto 04 |
| cadastro.php | Implementar e testar validações | Ponto 05 |
| cadastro.php | Validar INSERT e duplicidade | Ponto 06 |
| Listagem de usuários | Implementar consulta e pesquisa | Ponto 07 |
| Consulta de relacionamentos | Implementar JOIN | Ponto 08 |
| Confirmação de exclusão | Implementar confirmação | Ponto 09 |
| Exclusão lógica e auditoria | Implementar e validar | Ponto 10 |

As funcionalidades comerciais existentes deverão ser preservadas durante a implementação.

### 10. Registro dos testes

| ID | Cenário | Resultado esperado | Resultado observado | Situação |
|---|---|---|---|---|
| P01-T01 | Acessar página inicial | Página disponível | Abriu normalmente | Atendeu |
| P01-T02 | Acessar produtos | Página disponível | Abriu normalmente | Atendeu |
| P01-T03 | Acessar carrinho | Página disponível | Abriu normalmente | Atendeu |
| P01-T04 | Adicionar produto | Produto adicionado | Funcionou | Atendeu |
| P01-T05 | Visualizar carrinho | Exibir produto | Funcionou | Atendeu |
| P01-T06 | Testar botões da página inicial | Navegação correta | Erro 404 | Falha inicial |
| P01-T07 | Retestar rotas corrigidas | Navegação correta | Funcionou | Atendeu |
| P01-T08 | Atualizar página do carrinho | Manter produto e quantidade | Dados preservados | Atendeu |
| P01-T09 | Verificar contador do carrinho | Manter valor correto | Contador correto | Atendeu |
| P01-T10 | Acessar cadastro | Exibir formulário | Formulário disponível | Atendeu |
| P01-T11 | Acessar phpMyAdmin | Visualizar banco | Banco disponível | Atendeu |
| P01-T12 | Inspecionar usuarios | Identificar campos e índices | Estrutura identificada | Atendeu |
| P01-T13 | Inspecionar perfis_vendedor | Identificar campos e índices | Estrutura identificada | Atendeu |
| P01-T14 | Conferir banco configurado no PHP | Corresponder ao banco instalado | db_pulse confirmado | Atendeu |

**Teste positivo:** acesso às páginas existentes e verificação da persistência do carrinho.

**Teste negativo:** reprodução do erro 404 nos botões da página inicial.

**Reteste:** navegação restabelecida após a correção dos endereços no arquivo index.php.

### 11. Evidências

As evidências estão organizadas em `docs/evidencias/ponto-01/`.

| ID | Evidência | Descrição |
|---|---|---|
| E01 | [01-estrutura-projeto.png](evidencias/ponto-01/01-estrutura-projeto.png) | Estrutura de arquivos |
| E02 | [02-pagina-inicial.png](evidencias/ponto-01/02-pagina-inicial.png) | Página inicial |
| E03 | [03-pagina-produtos.png](evidencias/ponto-01/03-pagina-produtos.png) | Catálogo |
| E04 | [04-pagina-carrinho.png](evidencias/ponto-01/04-pagina-carrinho.png) | Carrinho |
| E05 | [05-teste-link-com-erro.png](evidencias/ponto-01/05-teste-link-com-erro.png) | Erro 404 |
| E06 | [06-ver-produtos-corrigido.png](evidencias/ponto-01/06-ver-produtos-corrigido.png) | Rota de produtos corrigida |
| E07 | [07-saiba-mais-corrigido.png](evidencias/ponto-01/07-saiba-mais-corrigido.png) | Rota sobre corrigida |
| E08 | [08-comprar-agora-corrigido.png](evidencias/ponto-01/08-comprar-agora-corrigido.png) | Rota do carrinho corrigida |
| E09 | [09-carrinho-antes-atualizacao.png](evidencias/ponto-01/09-carrinho-antes-atualizacao.png) | Carrinho antes da atualização |
| E10 | [10-carrinho-apos-atualizacao.png](evidencias/ponto-01/10-carrinho-apos-atualizacao.png) | Persistência do carrinho |
| E11 | [11-cadastro-estado-inicial.png](evidencias/ponto-01/11-cadastro-estado-inicial.png) | Formulário original |
| E12 | [12-bancos-de-dados-existentes.png](evidencias/ponto-01/12-bancos-de-dados-existentes.png) | Banco instalado |
| E13 | [13-estrutura-tabela-usuarios.png](evidencias/ponto-01/13-estrutura-tabela-usuarios.png) | Estrutura de usuários |
| E14 | [14-estrutura-perfis-vendedor.png](evidencias/ponto-01/14-estrutura-perfis-vendedor.png) | Estrutura de vendedores |
| E15 | [15-configuracao-conexao-pdo.png](evidencias/ponto-01/15-configuracao-conexao-pdo.png) | Configuração PDO |

### 12. Pendências encaminhadas

- Confirmar os relacionamentos existentes e as chaves estrangeiras.
- Conciliar as diferenças entre o script SQL e o banco instalado.
- Implementar as adequações estruturais do banco.
- Conferir a sintaxe e validar operacionalmente a conexão PDO.
- Testar a submissão e persistência do cadastro.
- Implementar listagem, pesquisa, JOIN, exclusão lógica e auditoria.
- Investigar possíveis conflitos entre os mecanismos de armazenamento do carrinho, caso ocorram falhas.

### 13. Resultado do Ponto 01

**Situação proposta: Atendeu.**

O diagnóstico inicial permitiu identificar os arquivos, rotas, dependências, estrutura do banco de dados e configuração de conexão existentes.

Foram realizados testes positivos e negativos, identificada uma falha de navegação e registrada sua correção.

Também foram definidas as estruturas que deverão ser preservadas, adaptadas ou implementadas nas próximas entregas.

As pendências técnicas foram registradas e encaminhadas aos respectivos pontos da Sprint.

A conclusão desta etapa está condicionada à conferência das evidências e do inventário de dependências pela equipe.

**Próxima etapa:** Ponto 02 — Estrutura MySQL, relacionamentos e integridade.

---

## PONTO 02 — Estrutura MySQL, relacionamentos e integridade

**Data de início:** 24/11/2026

**Situação final:** Atendeu aos testes estruturais e de instalação registrados.

### 1. Objetivo

Adequar a estrutura do banco de dados do sistema PULSE aos requisitos da Sprint 01, implementando relacionamentos entre usuários, setores e perfis, garantindo a integridade dos dados e preservando as funcionalidades existentes.

### 2. Estado inicial

Conforme o diagnóstico realizado no Ponto 01, o banco db_pulse possui uma estrutura de usuários existente, com chave primária, unicidade de e-mail e CPF e classificação de usuários.

Entretanto, ainda não possui as tabelas de referência de setores e perfis gerais, nem os campos necessários para o controle de status e baixa de usuários.

### 3. Plano de implementação

- Realizar backup do banco existente.
- Preparar um ambiente de homologação.
- Analisar e adequar o script SQL.
- Criar as tabelas setores e perfis.
- Adaptar a tabela usuarios.
- Preservar a tabela perfis_vendedor.
- Implementar chaves estrangeiras e índices.
- Implementar controle de status Ativo/Baixa.
- Inserir registros fictícios para homologação.
- Executar testes de integridade e duplicidade.
- Documentar os resultados e evidências.

### 4. Evidências

Diretório: `docs/evidencias/ponto-02/`

### 5. Resultado da entrega

Resultado consolidado: estrutura implementada e instalação de teste aprovada, conforme etapas abaixo.

### Etapa 02 — Backup preventivo do banco de dados

**Data:** 24/11/2026

**Banco de origem:** `db_pulse`

**Ferramenta utilizada:** phpMyAdmin.

**Objetivo:** preservar a estrutura e os registros existentes antes da implementação das alterações previstas no Ponto 02.

**Procedimento:** exportação da estrutura do banco em formato SQL (a análise posterior não identificou comandos INSERT).

**Arquivo gerado:** `db_pulse_backup_2026-11-24.sql`

**Armazenamento:** diretório de segurança externo ao projeto.

**Resultado esperado:** geração de uma cópia de segurança do banco original, sem alterações nas tabelas existentes.

**Evidências:**

- [Configuração da exportação](evidencias/ponto-02/01-configuracao-backup.png)
- [Arquivo de backup gerado](evidencias/ponto-02/02-backup-realizado.png)

**Situação:** exportação realizada, com validação da restauração pendente na base de homologação.

### Etapa 03 — Criação do ambiente de homologação

**Banco original:** `db_pulse`

**Banco de homologação:** `db_pulse_homologacao`

**Ferramenta utilizada:** phpMyAdmin.

**Colação:** utf8mb4_unicode_ci.

**Objetivo:** disponibilizar um ambiente isolado para implementação e testes das alterações estruturais previstas na Sprint 01, preservando o banco original.

**Procedimento:** criação de um novo banco de dados destinado à homologação.

**Evidência:** [Criação do banco de homologação](evidencias/ponto-02/03-criacao-banco-homologacao.png)

**Situação:** banco de homologação criado.

**Pendências:** inspeção do arquivo de backup, restauração controlada da estrutura, validação das tabelas e preparação dos registros fictícios.

### Etapa 04 — Análise do backup SQL

**Arquivo analisado:** `db_pulse_backup_2026-11-24.sql.sql (nome registrado na análise; conferir extensão real)`

**Objetivo:** verificar a estrutura do backup antes da importação no ambiente de homologação.

**Resultado da análise:**

- 390 linhas de código SQL.
- 11 tabelas existentes.
- 13 restrições de chave estrangeira.
- Chaves primárias e índices implementados.
- Restrições de unicidade para e-mail e CPF.
- Ausência de comandos de criação, seleção ou exclusão de bancos.
- Ausência de comandos de inserção de registros.
- Codificação utf8mb4.
- Relacionamento entre usuarios e perfis_vendedor confirmado no script.

**Ponto de atenção:** verificar o mecanismo de armazenamento das tabelas após a importação.

**Decisão técnica:** utilizar a estrutura existente como base para a implementação da Sprint, preservando os relacionamentos comerciais do PULSE.

**Situação:** análise estática concluída. Importação e validação operacional pendentes.

### Etapa 05 — Validação da estrutura restaurada

**Data:** 24/11/2026

**Banco utilizado:** `db_pulse_homologacao`

**Objetivo:** verificar a integridade estrutural do banco de dados restaurado, garantindo a preservação das tabelas, índices e relacionamentos existentes antes da implementação dos requisitos da Sprint 01.

#### Procedimentos realizados

Foram executadas consultas SQL para verificar as tabelas existentes, os mecanismos de armazenamento, as chaves estrangeiras, os índices da tabela usuarios e a quantidade de usuários cadastrados.

#### Resultados obtidos

| Item validado | Esperado | Obtido | Resultado |
|---|---|---|---|
| Quantidade de tabelas | 11 | 11 | Aprovado |
| Tabelas utilizando InnoDB | 11 | 11 | Aprovado |
| Chaves estrangeiras | 13 | 13 | Aprovado |
| Chave primária de usuarios | id | id | Aprovado |
| Índice único de e-mail | Existente | Existente | Aprovado |
| Índice único de CPF | Existente | Existente | Aprovado |
| Usuários cadastrados | 0 | 0 | Aprovado |

#### Observações técnicas

Foi identificada uma diferença de colação entre as tabelas restauradas:

- 7 tabelas utilizam utf8mb4_unicode_ci.
- 4 tabelas utilizam utf8mb4_general_ci.

Recomenda-se a padronização da colação no ambiente de homologação durante a adequação do script SQL.

Os avisos apresentados pelo phpMyAdmin não impediram a execução das consultas.

#### Evidências

- [Validação das tabelas e mecanismo InnoDB](evidencias/ponto-02/06-validacao-tabelas-innodb.png)
- [Validação das chaves estrangeiras](evidencias/ponto-02/07-validacao-chaves-estrangeiras.png)
- [Validação dos índices de usuários](evidencias/ponto-02/08-validacao-indices-usuarios.png)
- [Validação da ausência de usuários cadastrados](evidencias/ponto-02/09-validacao-base-sem-usuarios.png)

#### Conclusão

A estrutura original foi restaurada no ambiente de homologação e validada por meio de consultas SQL. Os resultados correspondem aos critérios definidos para esta etapa.

O ambiente encontra-se preparado para a implementação das novas tabelas e adequação dos relacionamentos exigidos pela Sprint 01.

**Resultado da etapa:** Atendeu.

**Situação à época:** etapa intermediária; concluída posteriormente.

### Etapa 06 — Criação das tabelas de referência

**Data:** 24/11/2026

**Banco utilizado:** `db_pulse_homologacao`

**Objetivo:** implementar as tabelas de referência de setores e perfis, preparando a estrutura necessária para os relacionamentos com a tabela usuarios.

**Arquivo desenvolvido:** `database/migrations/001_criacao_setores_perfis.sql`

#### Estrutura implementada

Foram criadas as tabelas setores e perfis, contendo os seguintes campos:

- id: chave primária do tipo BIGINT UNSIGNED com incremento automático.
- nome: campo VARCHAR(100), obrigatório e com restrição de unicidade.
- ativo: campo booleano com valor padrão verdadeiro e restrição CHECK para os valores 0 e 1.

Ambas as tabelas utilizam o mecanismo InnoDB, codificação utf8mb4 e colação utf8mb4_unicode_ci.

#### Registros cadastrados

**Setores:**

| ID | Nome | Ativo |
|---|---|---|
| 1 | Administração | 1 |
| 2 | Comercial | 1 |
| 3 | Atendimento | 1 |
| 4 | Estoque | 1 |
| 5 | Clientes | 1 |

**Perfis:**

| ID | Nome | Ativo |
|---|---|---|
| 1 | Administrador | 1 |
| 2 | Vendedor | 1 |
| 3 | Atendente | 1 |
| 4 | Operador de estoque | 1 |
| 5 | Cliente | 1 |

#### Resultados dos testes de homologação

| Teste | Resultado esperado | Resultado obtido | Situação |
|---|---|---|---|
| Criação das tabelas | 13 tabelas no banco | 13 tabelas | Aprovado |
| Consulta de setores | 5 registros | 5 registros ativos | Aprovado |
| Consulta de perfis | 5 registros | 5 registros ativos | Aprovado |
| Cadastro de setor duplicado | Rejeição pelo banco | Erro #1062 | Aprovado |
| Cadastro de perfil duplicado | Rejeição pelo banco | Erro #1062 | Aprovado |

#### Testes negativos

Foi realizada uma tentativa de inserir novamente o setor Administração. A operação foi rejeitada pelo banco de dados por meio da restrição uk_setores_nome.

Também foi realizada uma tentativa de inserir novamente o perfil Administrador. A operação foi rejeitada por meio da restrição uk_perfis_nome.

Os dois testes retornaram o código de erro #1062, confirmando o funcionamento das restrições de unicidade.

#### Evidências

- [Criação das tabelas de referência](evidencias/ponto-02/10-criacao-tabelas-referencia.png)
- [Registros da tabela setores](evidencias/ponto-02/11-registros-tabela-setores.png)
- [Registros da tabela perfis](evidencias/ponto-02/12-registros-tabela-perfis.png)
- [Teste de setor duplicado](evidencias/ponto-02/13-teste-setor-duplicado.png)
- [Teste de perfil duplicado](evidencias/ponto-02/14-teste-perfil-duplicado.png)

#### Conclusão

As tabelas de referência foram implementadas com sucesso no ambiente de homologação. Os testes positivos e negativos apresentaram os resultados esperados, comprovando a criação das estruturas, a inserção dos registros iniciais e o funcionamento das restrições de unicidade.

A validação específica das restrições CHECK e dos relacionamentos com usuarios será realizada nas etapas seguintes.

**Resultado da etapa:** Atendeu.

**Situação à época:** etapa intermediária; concluída posteriormente.

**Próxima etapa:** adequação da tabela usuarios.

### Etapa 07 — Adequação da tabela usuarios

**Data:** 24/11/2026

**Banco utilizado:** `db_pulse_homologacao`

**Arquivo desenvolvido:** `database/migrations/002_adequacao_tabela_usuarios.sql`

#### Objetivo

Adequar a estrutura da tabela usuarios aos requisitos da Sprint 01, preparando os relacionamentos com as tabelas de referência e os mecanismos de controle de status, atualização e baixa lógica.

#### Alterações realizadas

A tabela usuarios foi modificada por meio do comando ALTER TABLE, preservando os oito campos existentes e acrescentando cinco novos campos, totalizando 13 campos.

| Campo | Tipo | Finalidade |
|---|---|---|
| setor_id | BIGINT UNSIGNED | Referência ao setor do usuário |
| perfil_id | BIGINT UNSIGNED | Referência ao perfil do usuário |
| status | ENUM('Ativo', 'Baixa') | Controle da situação cadastral |
| atualizado_em | TIMESTAMP | Registro automático da última atualização |
| baixado_em | TIMESTAMP | Registro da data e hora da baixa lógica |

#### Decisões técnicas

- Preservação dos oito campos existentes.
- Preservação da chave primária e dos índices únicos de e-mail e CPF.
- Manutenção temporária do campo tipo para compatibilidade com o sistema existente.
- Utilização do comando ALTER TABLE para evolução da estrutura.
- Permissão temporária de valores nulos nos campos setor_id e perfil_id durante a migração.
- Definição do status Ativo como valor padrão.
- Configuração da atualização automática do campo atualizado_em por meio de ON UPDATE CURRENT_TIMESTAMP.
- Preparação da estrutura para baixa lógica, sem exclusão física do cadastro.

#### Resultados dos testes de homologação

| Teste | Resultado esperado | Resultado obtido | Situação |
|---|---|---|---|
| Execução da migration 002 | Execução sem erros | Executada com sucesso | Aprovado |
| Estrutura da tabela usuarios | 13 campos | 13 campos identificados | Aprovado |
| Novos campos | 5 campos adicionados | 5 campos adicionados | Aprovado |
| Valores padrão | Conforme especificação | Configurações confirmadas | Aprovado |
| Índices existentes | 3 índices preservados | 3 índices preservados | Aprovado |
| Atualização automática | Configuração ON UPDATE CURRENT_TIMESTAMP | Configuração confirmada | Aprovado |

#### Validação dos índices

A consulta SHOW INDEX confirmou a preservação dos seguintes índices:

- PRIMARY: chave primária do campo id.
- email: índice único do campo email.
- cpf: índice único do campo cpf.

Os três índices apresentaram Non_unique igual a 0, confirmando a manutenção das respectivas restrições de unicidade.

#### Testes negativos e limitações

Os testes negativos relacionados à duplicidade de e-mail, referências inexistentes, valores inválidos de status e obrigatoriedade dos relacionamentos serão executados após a conclusão da estrutura relacional e a inserção dos registros fictícios.

A atualização automática e a baixa lógica ainda deverão ser validadas operacionalmente.

#### Evidências

- [Adequação da tabela usuarios](evidencias/ponto-02/15-adequacao-tabela-usuarios.png)
- [Nova estrutura da tabela usuarios](evidencias/ponto-02/16-nova-estrutura-usuarios.png)
- [Validação dos novos campos](evidencias/ponto-02/17-validacao-novos-campos.png)
- [Preservação dos índices existentes](evidencias/ponto-02/18-preservacao-indices-usuarios.png)

#### Conclusão

A adequação da tabela usuarios foi realizada com sucesso no ambiente de homologação. As consultas de validação confirmaram a existência dos 13 campos, as configurações dos novos atributos e a preservação dos índices anteriores.

A estrutura encontra-se preparada para a implementação das chaves estrangeiras e dos mecanismos de integridade referencial.

**Resultado da etapa:** Atendeu.

**Situação à época:** etapa intermediária; concluída posteriormente.

**Próxima etapa:** implementação dos relacionamentos e índices.

### Etapa 08 — Implementação dos relacionamentos e índices

**Data de execução:** 24/11/2026

**Banco utilizado:** `db_pulse_homologacao`

**Arquivo desenvolvido:** `database/migrations/003_relacionamentos_indices.sql`

**Situação:** Concluída.

#### Objetivo

Implementar os relacionamentos entre as tabelas usuarios, setores e perfis, garantindo a obrigatoriedade das referências, a integridade referencial dos registros e a disponibilidade de índices para consultas futuras.

#### Implementação realizada

Inicialmente, foi executada uma consulta para verificar a quantidade de registros existentes na tabela usuarios. O resultado retornou zero registros, permitindo a alteração dos campos sem necessidade de migração de usuários preexistentes.

A migration 003 foi executada com sucesso no ambiente de homologação.

Os campos setor_id e perfil_id foram definidos como obrigatórios por meio da restrição NOT NULL.

Foram implementadas as seguintes chaves estrangeiras:

| Restrição | Campo | Referência |
|---|---|---|
| fk_usuarios_setor | setor_id | setores.id |
| fk_usuarios_perfil | perfil_id | perfis.id |

Os relacionamentos possuem cardinalidade de um para muitos (1:N), permitindo que diversos usuários estejam associados ao mesmo setor ou perfil.

#### Índices implementados

| Índice | Campo | Finalidade |
|---|---|---|
| idx_usuarios_setor | setor_id | Auxiliar consultas por setor |
| idx_usuarios_perfil | perfil_id | Auxiliar consultas por perfil |
| idx_usuarios_status | status | Auxiliar consultas por situação cadastral |

Os índices anteriormente existentes de chave primária, e-mail e CPF foram preservados, totalizando seis índices na tabela usuarios.

#### Decisões técnicas

- Obrigatoriedade dos relacionamentos com setores e perfis.
- Utilização de chaves estrangeiras para garantir a existência dos registros referenciados.
- Utilização de ON DELETE RESTRICT para impedir a exclusão de referências vinculadas.
- Utilização de ON UPDATE RESTRICT para impedir alterações incompatíveis nos identificadores referenciados.
- Manutenção da separação entre perfis gerais de usuários e perfis comerciais de vendedores.
- Execução das alterações exclusivamente no ambiente de homologação.

#### Resultados dos testes estruturais

| Teste | Resultado esperado | Resultado obtido | Situação |
|---|---|---|---|
| Verificação inicial | 0 usuários | 0 usuários | Aprovado |
| Execução da migration 003 | Sem erros | Executada com sucesso | Aprovado |
| Obrigatoriedade dos campos | NOT NULL | Ambos os campos obrigatórios | Aprovado |
| Índices da tabela usuarios | 6 índices | 6 índices identificados | Aprovado |
| Novas chaves estrangeiras | 2 relacionamentos | 2 relacionamentos identificados | Aprovado |
| Regras de integridade | RESTRICT | RESTRICT em ambas as FKs | Aprovado |
| Relacionamentos gerais | 15 chaves estrangeiras | 15 chaves estrangeiras identificadas | Aprovado |

#### Evidências

- [Verificação inicial de usuários](evidencias/ponto-02/19-verificacao-usuarios-antes-relacionamentos.png)
- [Execução da migration 003](evidencias/ponto-02/20-execucao-migration-relacionamentos.png)
- [Validação dos campos obrigatórios](evidencias/ponto-02/21-validacao-campos-obrigatorios.png)
- [Validação dos índices](evidencias/ponto-02/22-validacao-indices-usuarios.png)
- [Validação das chaves estrangeiras](evidencias/ponto-02/23-validacao-chaves-estrangeiras.png)
- [Validação das regras de integridade](evidencias/ponto-02/24-validacao-regras-integridade.png)
- [Validação dos relacionamentos gerais](evidencias/ponto-02/25-validacao-relacionamentos-gerais.png)

#### Conclusão

A implementação estrutural dos relacionamentos e índices foi concluída com sucesso. O banco de homologação passou a apresentar 15 chaves estrangeiras, incluindo os dois novos relacionamentos exigidos para o cadastro de usuários.

As restrições de obrigatoriedade, unicidade e integridade referencial encontram-se configuradas na estrutura do banco de dados.

#### Pendências

Inserção de usuários fictícios, testes práticos de integridade referencial, rejeição de e-mails duplicados, validação dos relacionamentos por meio de JOIN, implementação da auditoria e adequação das operações PHP à nova estrutura.

**Resultado da etapa:** Atendeu aos objetivos estruturais previstos.

**Situação à época:** etapa intermediária; concluída posteriormente.

**Próxima etapa:** Inserção de dados fictícios e testes de integridade.

### Etapa 09 — Inserção de dados fictícios e validação positiva

**Data:** 24/11/2026

**Banco de dados:** `db_pulse_homologacao`

A migration 004 foi executada com sucesso, realizando a inserção de seis usuários fictícios destinados aos testes de homologação.

Foram realizadas consultas SQL para verificar a quantidade de registros, a distribuição dos usuários por status, os campos de rastreabilidade e os relacionamentos entre usuários, setores e perfis.

**Resultados obtidos:**

- Seis usuários fictícios cadastrados.
- Cinco usuários com status Ativo.
- Um usuário com status Baixa.
- Relacionamentos entre usuários, setores e perfis demonstrados por meio de INNER JOIN.
- Datas de criação e atualização preenchidas.
- Data de baixa preenchida no usuário com status Baixa.
- Data de baixa nula nos usuários ativos.

**Evidências:**

- `26-registros-inseridos-migration-004.png`
- `27-validacao-quantidade-usuarios.png`
- `28-validacao-status-usuarios.png`
- `29-validacao-join-usuarios.png`

**Resultado da validação positiva:** Atendeu.

**Pendências:** execução dos testes negativos de integridade referencial, duplicidade de e-mail e demais restrições aplicáveis.

### Etapa 09 — Testes negativos e validação final de integridade

**Data de execução:** 24/11/2026  
**Ambiente:** db_pulse_homologacao  
**Situação:** Concluída.

Foram realizados sete testes negativos de integridade no banco de dados de homologação do sistema PULSE, com o objetivo de verificar o funcionamento das restrições de unicidade, obrigatoriedade dos relacionamentos e integridade referencial.

#### Resultados dos testes

| Teste | Resultado esperado | Resultado obtido | Situação |
|---|---|---|---|
| E-mail duplicado | Rejeitar duplicidade | Erro MySQL #1062 | Aprovado |
| Setor inexistente | Rejeitar relacionamento inválido | Erro MySQL #1452 | Aprovado |
| Perfil inexistente | Rejeitar relacionamento inválido | Erro MySQL #1452 | Aprovado |
| Setor nulo | Impedir cadastro sem setor | Erro MySQL #1452 | Aprovado |
| Perfil nulo | Impedir cadastro sem perfil | Erro MySQL #1452 | Aprovado |
| Exclusão de setor vinculado | Impedir exclusão | Erro MySQL #1451 | Aprovado |
| Exclusão de perfil vinculado | Impedir exclusão | Erro MySQL #1451 | Aprovado |

**Observação técnica:** nos testes de setor e perfil nulos, o banco retornou o erro #1452. A rejeição foi comprovada, enquanto a configuração NOT NULL foi verificada separadamente na estrutura da tabela usuarios.

#### Validação após os testes

- Total de usuários cadastrados: 6.
- Usuários ativos: 5.
- Usuários com status Baixa: 1.
- Usuários sem relacionamento válido: 0.
- Registros inválidos remanescentes: 0.

#### Evidências

- [30 — Teste de e-mail duplicado](evidencias/ponto-02/30-teste-email-duplicado.png)
- [31 — Teste de setor inexistente](evidencias/ponto-02/31-teste-setor-inexistente.png)
- [32 — Teste de perfil inexistente](evidencias/ponto-02/32-teste-perfil-inexistente.png)
- [33 — Teste de setor obrigatório](evidencias/ponto-02/33-teste-setor-obrigatorio.png)
- [34 — Teste de perfil obrigatório](evidencias/ponto-02/34-teste-perfil-obrigatorio.png)
- [35 — Teste de exclusão de setor vinculado](evidencias/ponto-02/35-teste-exclusao-setor-vinculado.png)
- [36 — Teste de exclusão de perfil vinculado](evidencias/ponto-02/36-teste-exclusao-perfil-vinculado.png)
- [37 — Validação da quantidade após os testes](evidencias/ponto-02/37-validacao-quantidade-pos-testes.png)
- [38 — Validação da integridade referencial](evidencias/ponto-02/38-validacao-integridade-referencial.png)

#### Conclusão da etapa

Os testes demonstraram que as restrições implementadas impedem a duplicidade de e-mails, a inclusão de relacionamentos inválidos e a exclusão de setores e perfis vinculados a usuários.

Após a execução dos testes, os seis registros fictícios permaneceram cadastrados, sem relacionamentos inválidos.

**Resultado da etapa: Atendeu.**

### Etapa 10.4.1 — Preparação do ambiente de instalação

Foi criado o banco de dados `db_pulse_instalacao_teste`, destinado exclusivamente à validação da instalação completa dos scripts SQL do projeto PULSE.

O ambiente foi configurado com o conjunto de caracteres `utf8mb4` e a collation `utf8mb4_unicode_ci`.

A consulta de validação confirmou que o novo banco não possui tabelas, garantindo um ambiente inicialmente vazio para a execução dos scripts.

Os bancos `db_pulse` e `db_pulse_homologacao` foram mantidos, sem alterações durante esta etapa.

**Resultado:** Atendeu.

**Evidências:** Criação do banco, identificação dos três bancos existentes e validação da ausência de tabelas no novo ambiente.

**Próxima atividade:** Preparação das cópias temporárias dos scripts SQL e execução sequencial da instalação.

### Etapa 10.4.2 — Preparação dos scripts temporários de instalação

**Objetivo:** Preparar e conferir os scripts SQL para instalação completa em uma base de dados isolada.

**Banco de destino:** `db_pulse_instalacao_teste`

#### Procedimentos realizados

- Criação de uma pasta temporária externa ao projeto PULSE.
- Organização dos cinco arquivos SQL necessários à instalação.
- Conferência individual dos comandos de seleção do banco.
- Validação das referências aos bancos original e de homologação.
- Pesquisa consolidada dos comandos direcionados ao novo ambiente.

#### Resultados obtidos

A pesquisa consolidada apresentou seis ocorrências distribuídas nos cinco arquivos SQL, sendo duas no arquivo principal e uma em cada migration.

Todos os scripts apresentaram o direcionamento esperado para o banco de instalação.

#### Evidências

| Nº | Arquivo | Resultado |
|---|---|---|
| 44 | 44-organizacao-scripts-temporarios.png | Aprovado |
| 45 | 45-pesquisa-banco-destino-scripts.png | Pesquisa inicial sem resultados |
| 46 | 46-validacao-instruction-banco-destino.png | Aprovado |
| 47 | 47-validacao-migration-001-banco-destino.png | Aprovado |
| 48 | 48-validacao-migration-002-banco-destino.png | Aprovado |
| 49 | 49-validacao-migration-003-banco-destino.png | Aprovado |
| 50 | 50-validacao-migration-004-banco-destino.png | Aprovado |
| 51 | 51-validacao-final-isolamento-scripts.png | Sem ocorrências dos bancos anteriores na pesquisa |
| 52 | 52-confirmacao-banco-destino-cinco-scripts.png | Aprovado |

**Resultado da preparação:** Atendeu.

**Observação:** A validação realizada nesta etapa corresponde à conferência estática dos scripts. A execução e a integridade da instalação completa serão verificadas nas próximas etapas.

**Situação à época:** etapa intermediária; concluída posteriormente.

### Etapa 10.4 — Teste de instalação completa

**Objetivo:** validar a execução sequencial do script principal e das quatro migrations da Sprint 01 em uma base de dados nova e independente.

**Banco utilizado:** `db_pulse_instalacao_teste`.

**Procedimento realizado:**

Foi criada uma base de dados exclusiva para o teste de instalação. Os scripts SQL foram copiados para uma pasta temporária e tiveram suas referências de banco de dados ajustadas, evitando alterações nas bases original e de homologação.

Foram executados, sequencialmente, os seguintes arquivos:

1. `instruction.sql`
2. `001_criacao_setores_perfis.sql`
3. `002_adequacao_tabela_usuarios.sql`
4. `003_relacionamentos_indices.sql`
5. `004_dados_ficticios_homologacao.sql`

#### Resultados obtidos

| Indicador | Esperado | Obtido |
|---|---:|---:|
| Total de tabelas | 15 | 15 |
| Chaves estrangeiras | 19 | 19 |
| Setores | 5 | 5 |
| Perfis | 5 | 5 |
| Usuários fictícios | 6 | 6 |
| Usuários ativos | 5 | 5 |
| Usuários baixados | 1 | 1 |
| Usuários órfãos | 0 | 0 |

#### Conclusão técnica

O teste confirmou a compatibilidade estrutural entre o arquivo `instruction.sql` corrigido e as quatro migrations da Sprint 01.

As tabelas, os índices, as restrições de unicidade, as chaves estrangeiras e os registros fictícios foram criados conforme os resultados esperados.

A consulta JOIN demonstrou a associação correta entre usuários, setores e perfis.

**Resultado do teste:** aprovado.

**Evidências:** disponíveis em `docs/evidencias/ponto-02/`, arquivos 55 a 58 e 60 a 65.

**Observações:** a evidência intermediária 59 não foi apresentada. A instalação foi validada utilizando cópias temporárias dos scripts com o banco de destino ajustado. As regras de autorização e a auditoria operacional da aplicação serão verificadas nas etapas correspondentes da Sprint 01.

**Resultado consolidado do Ponto 02:** ATENDEU. A instalação foi validada com cópias ajustadas; conferir os scripts definitivos antes da entrega. O teste específico de status inválido não possui resultado registrado.

## PONTO 03 — Conexão PHP/MySQL com PDO

**Data:** 24/11/2026

**Objetivo:** Implementar e validar uma conexão centralizada entre PHP e MySQL utilizando PDO, com tratamento de exceções e consulta real ao banco de dados.

### Arquivos utilizados

- `database/conexao.php`
- `teste_conexao.php` — arquivo temporário de validação.

### Implementações realizadas

- Centralização da conexão por meio da função `pulse_db()`.
- Configuração de credenciais por variáveis de ambiente, com valores padrão para desenvolvimento local.
- Utilização da codificação UTF8MB4.
- Configuração de exceções PDO.
- Desativação da emulação de prepared statements.
- Tratamento de falhas sem exposição de informações sensíveis ao usuário.

### Teste positivo

Conexão estabelecida com o banco `db_pulse_instalacao_teste`, utilizando o driver MySQL.

A consulta retornou:

- Total de usuários: 6.
- Usuários ativos: 5.
- Usuários baixados: 1.

Resultado: aprovado.

### Teste de erro

Foi simulada a indisponibilidade do banco de dados. O sistema apresentou uma mensagem de falha controlada, sem exposição de credenciais ou detalhes internos da exceção.

Resultado: aprovado.

### Teste de recuperação

Após o restabelecimento do serviço MySQL, a conexão e a consulta foram executadas novamente com sucesso, mantendo os resultados esperados.

Resultado: aprovado.

### Evidências

Diretório: `docs/evidencias/ponto-03/` (links relativos ao relatório).

- `evidencias/ponto-03/01-conexao-pdo-e-consulta-real.png`
- `evidencias/ponto-03/02-falha-controlada-conexao.png`
- `evidencias/ponto-03/03-restabelecimento-conexao.png`

### Resultado final

**ATENDEU.**

A conexão centralizada e os testes de comunicação, indisponibilidade e recuperação foram validados no ambiente local de homologação. O tratamento de falhas nas demais páginas será verificado nas etapas correspondentes.

## PONTO 04 — Consulta às tabelas relacionadas no formulário

### 4.1. Objetivo

Integrar os campos de seleção de setor e perfil do formulário de cadastro às tabelas de referência do MySQL, utilizando PHP, PDO e consultas SELECT reais.

### 4.2. Arquivos envolvidos

- `cadastro.php`
- `database/conexao.php`

### 4.3. Implementações realizadas

- Carregamento dinâmico dos setores e perfis diretamente do banco de dados.
- Utilização de consultas SELECT por meio da conexão PDO.
- Filtragem dos registros ativos.
- Ordenação alfabética das opções.
- Utilização das chaves primárias como valores dos campos de seleção.
- Exibição dos nomes de setores e perfis na interface.
- Tratamento da ausência de registros disponíveis.
- Bloqueio do botão de cadastro quando não existem referências disponíveis.
- Restrição do cadastro público ao perfil Cliente, evitando a atribuição indevida de privilégios administrativos.
- Preservação da estrutura e da identidade visual existente da PULSE.

### 4.4. Testes realizados

| Teste | Resultado obtido | Situação |
|---|---|---|
| Carregamento dos setores | Registros exibidos diretamente do MySQL | Aprovado |
| Carregamento dos perfis | Perfil Cliente disponibilizado no cadastro público | Aprovado |
| Inclusão de setor fictício | Novo setor exibido automaticamente no formulário | Aprovado |
| Remoção dos registros temporários | Opções atualizadas e base de teste preservada | Aprovado |
| Ausência de setores ativos | Mensagem informativa e botão Cadastrar desabilitado | Aprovado |
| Restabelecimento dos setores | Formulário voltou a disponibilizar as opções | Aprovado |
| Inspeção dos valores HTML | IDs correspondentes aos registros do banco identificados | Aprovado |

### 4.5. Ambiente de testes

- Ambiente local: XAMPP.
- Banco de dados: `db_pulse_instalacao_teste`.
- Rota: `http://localhost/pulse/cadastro.php`.
- Dados utilizados: registros fictícios de homologação.

### 4.6. Evidências

As evidências técnicas estão armazenadas no diretório:

`docs/evidencias/ponto-04/`

### 4.7. Pendências

Não foram identificadas pendências impeditivas no escopo de carregamento das tabelas relacionadas.

Os testes de submissão, validação de entradas manipuladas e persistência dos usuários serão consolidados nos Pontos 05 e 06.

### 4.8. Conclusão

O formulário passou a consultar diretamente as tabelas relacionadas do MySQL, eliminando a necessidade de listas fixas e tratando adequadamente a indisponibilidade de referências.

**Resultado: ATENDEU.**

**Situação: CONCLUÍDO.**

## PONTO 05 — Formulário funcional e validação de entrada

### Objetivo

Integrar o formulário de cadastro ao PHP, garantindo a
validação dos dados no navegador e no servidor.

### Arquivos envolvidos

- cadastro.php
- database/conexao.php
- includes/auth.php

### Implementações

- Validação dos campos obrigatórios.
- Validação de nome, e-mail e senha.
- Verificação de setores e perfis ativos.
- Restrição do perfil no cadastro público.
- Mensagens de erro individuais por campo.
- Preservação dos dados não sensíveis.
- Proteção contra CSRF.
- Utilização de consultas SQL preparadas.
- Armazenamento de senha por hash.

### Testes realizados

| Teste | Resultado |
|---|---|
| Campos obrigatórios vazios | Aprovado |
| E-mail inválido | Aprovado |
| Senha curta | Aprovado |
| Validação diretamente no PHP | Aprovado |
| Setor inexistente | Aprovado |
| Perfil não autorizado | Aprovado |
| Preservação dos dados não sensíveis | Aprovado |
| Cadastro válido | Aprovado |

### Evidências

Diretório: docs/evidencias/ponto-05/

- 01-validacao-campos-obrigatorios.png
- 02-validacao-email-invalido.png
- 03-validacao-senha-curta.png
- 04-validacao-servidor-php.png
- 05-validacao-setor-inexistente.png
- 06-validacao-perfil-nao-autorizado.png
- 07-cadastro-valido.png

### Resultado final

O formulário apresentou comportamento adequado nos
testes de validação, rejeitando entradas inválidas e
permitindo o cadastro com dados válidos.

Status: CONCLUÍDO — ATENDEU.

## PONTO 06 — INSERT real e resposta do cadastro

### Objetivo

Implementar a persistência real de usuários no MySQL,
utilizando PDO, instruções preparadas, relacionamentos
por IDs, tratamento de erros e auditoria das operações.

### Arquivos envolvidos

- `cadastro.php`
- `dashboard.php`
- `includes/auth.php`
- `includes/auditoria.php`
- `database/conexao.php`

### Implementações realizadas

- INSERT preparado utilizando PDO.
- Relacionamento com as tabelas setores e perfis.
- Armazenamento de senha utilizando password_hash().
- Tratamento de duplicidade de e-mail.
- Tratamento de erros de chaves estrangeiras.
- Transação para gravação do usuário e da auditoria.
- Registro de sucesso e falha das operações.
- Confirmação visual de cadastro bem-sucedido.
- Redirecionamento para o dashboard.
- Mensagem temporária utilizando sessão PHP.
- Preservação da identidade visual da PULSE.

### Auditoria e rastreabilidade

Foi utilizada a tabela existente `auditoria_sistema`,
sem necessidade de criar uma nova tabela.

As operações registram:

- Identificador do usuário afetado, quando disponível.
- Identificador do responsável, quando identificado.
- Ação executada.
- Entidade afetada.
- Identificador do registro.
- Descrição do resultado.
- Data e horário da operação.

Nenhuma senha é registrada na auditoria.

### Testes realizados

| Teste | Resultado |
|---|---|
| Cadastro válido | Aprovado |
| Persistência após atualização | Aprovado |
| E-mail duplicado | Aprovado |
| Ausência de registros duplicados | Aprovado |
| Armazenamento seguro da senha | Aprovado |
| Auditoria de sucesso | Aprovado |
| Auditoria de falha | Aprovado |
| Confirmação visual | Aprovado |
| Redirecionamento ao dashboard | Aprovado |
| Remoção da mensagem após atualização | Aprovado |

### Resultado da auditoria

O usuário de teste foi persistido com:

- ID: 14
- Nome: Usuário Auditoria Sprint 01
- E-mail: sprint06@pulse.example
- Setor ID: 5
- Perfil ID: 5
- Status: Ativo

A tabela de auditoria apresentou dois registros:

1. Sucesso: usuário cadastrado.
2. Falha: duplicidade de registro.

A consulta ao banco confirmou a existência de apenas
um usuário com o e-mail utilizado no teste.

### Correções realizadas

Durante a homologação, foi identificado um erro
relacionado à variável $mensagem não inicializada.

A correção consistiu na inicialização da variável
como string vazia e na proteção adicional contra
valores nulos durante sua exibição.

Após a correção, o fluxo de cadastro foi retomado
e a confirmação visual foi demonstrada.

### Evidências

Diretório: `docs/evidencias/ponto-06/`

- 01-insert-persistencia-mysql.png
- 02-bloqueio-email-duplicado.png
- 03-confirmacao-sem-duplicidade.png
- 04-armazenamento-seguro-senha.png
- 05-confirmacao-cadastro-sucesso.png
- 06-auditoria-cadastro-sucesso-falha.png
- 07-persistencia-usuario-auditado.png

### Ressalvas técnicas

A validação de relacionamentos inválidos foi
demonstrada nos Pontos 02 e 05.

O tratamento específico da exceção de chave
estrangeira durante o INSERT não foi testado
diretamente nesta etapa.

A reversão da transação diante de uma falha
na gravação da auditoria também não foi
testada diretamente.

### Resultado final

Status: Atendeu, com ressalvas de cobertura de testes.

Data da homologação: 24/11/2026.

## PONTO 07 — Consultas SELECT e listagem de usuários

### 1. Objetivo

Implementar uma página de gestão de usuários integrada ao
MySQL, permitindo consultar registros ativos e realizar
pesquisas por nome ou e-mail de forma segura.

### 2. Arquivos utilizados

| Arquivo | Finalidade |
|---|---|
| `usuarios.php` | Listagem e pesquisa de usuários |
| `dashboard.php` | Acesso à gestão de usuários |
| `login.php` | Autenticação de usuários ativos |
| `includes/auth.php` | Controle de autorização administrativa |
| `database/conexao.php` | Conexão PDO com o MySQL |
| `js/site.js` | Funcionalidades gerais da interface |

### 3. Implementações realizadas

- Criação da página de gestão de usuários.
- Consulta SELECT diretamente ao MySQL.
- Utilização de consultas preparadas com PDO.
- Filtragem de registros com status Ativo.
- Pesquisa por nome ou e-mail.
- Tratamento de caracteres especiais.
- Mensagem para pesquisas sem resultados.
- Ordenação dos registros por nome e ID.
- Proteção da saída HTML.
- Restrição de acesso a administradores ativos.
- Integração com o dashboard.
- Preservação da identidade visual do PULSE.

### 4. Consulta SQL

A consulta principal utiliza a seguinte estrutura:

```sql
SELECT id, nome, email, status
FROM usuarios
WHERE status = :status
ORDER BY nome ASC, id ASC;
```

Quando uma pesquisa é realizada, são acrescentadas
condições LIKE parametrizadas para nome e e-mail.

Os valores informados pelo usuário são enviados
separadamente da instrução SQL por meio do PDO.

### 5. Testes realizados

| Teste | Resultado | Situação |
|---|---|---|
| Listagem de usuários ativos | Registros ativos exibidos | Aprovado |
| Pesquisa por nome | Registro correspondente localizado | Aprovado |
| Pesquisa por e-mail | Registro correspondente localizado | Aprovado |
| Pesquisa inexistente | Mensagem de nenhum usuário encontrado | Aprovado |
| Tentativa de SQL Injection | Nenhum registro indevido retornado | Aprovado |
| Atualização pelo MySQL | Alteração confirmada durante o teste local | Aprovado |
| Acesso sem autenticação | Redirecionamento para login | Aprovado |
| Acesso sem permissão | Acesso administrativo negado | Aprovado |
| Integração com dashboard | Botão administrativo exibido | Aprovado |

### 6. Evidências

Diretório: `docs/evidencias/ponto-07/`

- 01-listagem-usuarios-ativos.png
- 02-pesquisa-por-nome.png
- 03-pesquisa-por-email.png
- 04-pesquisa-sem-resultados.png
- 05-protecao-sql-injection.png
- 06-atualizacao-dinamica-mysql.png
- 07-bloqueio-sem-autenticacao.png
- 08-bloqueio-usuario-sem-permissao.png
- 09-integracao-dashboard.png

### 7. Correções realizadas

- Adequação da autenticação para usuários ativos.
- Implementação da verificação administrativa no banco.
- Ajustes de HTML, JavaScript e tema relatados durante a implementação; conferir a versão definitiva dos arquivos e o funcionamento do tema.
- Integração da gestão de usuários ao dashboard.

### 8. Segurança e integridade

A listagem utiliza consultas preparadas e parâmetros
tratados, reduzindo o risco de SQL Injection.

A autorização administrativa é verificada no servidor,
consultando o tipo e o status do usuário autenticado.

Os registros com status Baixa não são apresentados
na listagem de usuários ativos.

Nenhuma senha ou hash de senha é exibido na listagem.

### 9. Pendências e observações

- Complementar a evidência da atualização do nome
  diretamente na interface.
- Complementar a evidência de autenticação do cliente
  antes da tentativa de acesso administrativo.
- Confirmar a alternância de tema após os ajustes.
- Realizar regressão das funcionalidades existentes
  durante a homologação final.

Os testes locais foram confirmados pelo responsável
pela execução. As evidências disponíveis foram revisadas.

### 10. Resultado final

Status: ATENDEU AOS REQUISITOS FUNCIONAIS.

A página de gestão de usuários encontra-se implementada,
integrada ao MySQL e disponível pelo dashboard para
administradores autorizados.

A consulta inicial foi posteriormente ampliada com o JOIN do Ponto 08.

## PONTO 08 — JOIN entre usuários, setores e perfis

### 1. Objetivo

Implementar uma consulta relacional utilizando JOIN
entre as tabelas usuarios, setores e perfis, permitindo
a apresentação dos nomes dos setores e perfis na
interface de gestão de usuários.

### 2. Arquivos envolvidos

- usuarios.php
- database/conexao.php
- includes/auth.php

### 3. Implementações realizadas

- Consulta SQL utilizando INNER JOIN.
- Relacionamento entre usuários, setores e perfis.
- Inclusão das colunas Setor e Perfil na interface.
- Manutenção da listagem exclusiva de usuários ativos.
- Preservação da pesquisa por nome e e-mail.
- Preservação das consultas preparadas com PDO.
- Manutenção da restrição de acesso administrativo.
- Preservação da identidade visual do sistema PULSE.

### 4. Consulta SQL de referência

```sql
SELECT
    u.id,
    u.nome,
    u.email,
    s.nome AS setor,
    p.nome AS perfil,
    u.status
FROM usuarios AS u
INNER JOIN setores AS s
    ON u.setor_id = s.id
INNER JOIN perfis AS p
    ON u.perfil_id = p.id
WHERE u.status = 'Ativo'
ORDER BY u.nome ASC, u.id ASC;
```

### 5. Explicação das condições ON

A condição ON u.setor_id = s.id estabelece
o relacionamento entre a chave estrangeira do setor
na tabela usuarios e a chave primária da tabela setores.

A condição ON u.perfil_id = p.id estabelece
o relacionamento entre a chave estrangeira do perfil
na tabela usuarios e a chave primária da tabela perfis.

Os relacionamentos são realizados pelos identificadores,
evitando associações incorretas por nomes semelhantes.

O INNER JOIN retorna os registros que possuem
correspondência nas tabelas relacionadas.

### 6. Testes realizados

| Teste | Resultado informado |
|---|---|
| Consulta JOIN no MySQL | Aprovado |
| Integração com a interface | Aprovado |
| Validação dos setores e perfis | Aprovado |
| Ausência de registros duplicados | Aprovado |
| Alteração dinâmica do setor | Aprovado |
| Restauração do setor original | Aprovado |
| Pesquisa por nome | Aprovado |
| Pesquisa por e-mail | Aprovado |
| Pesquisa inexistente | Aprovado |
| Proteção contra SQL Injection | Aprovado |
| Acesso sem autenticação | Aprovado |
| Acesso sem permissão | Aprovado |

### 7. Demonstração da atualização dinâmica

Foi realizada a alteração temporária do setor
Comercial para Comercial - Teste JOIN.

```sql
UPDATE setores
SET nome = 'Comercial - Teste JOIN'
WHERE id = 2
AND nome = 'Comercial';
```

A alteração foi conferida na interface, conforme
confirmação do responsável pelos testes.

A restauração do nome original foi confirmada no Ponto 10.

```sql
UPDATE setores
SET nome = 'Comercial'
WHERE id = 2
AND nome = 'Comercial - Teste JOIN';
```

O teste demonstra que os nomes dos setores são
obtidos dinamicamente da tabela relacionada.

### 8. Evidências

Diretório: docs/evidencias/ponto-08/

- 01-consulta-join-mysql.png
- 02-listagem-join-interface.png
- 03-validacao-setores-perfis.png
- 04-validacao-sem-duplicidade.png
- 05-alteracao-setor-mysql.png
- 06-alteracao-setor-interface.png
- 07-join-pesquisa-nome.png
- 08-join-pesquisa-email.png
- 09-join-pesquisa-inexistente.png
- 10-join-protecao-sql-injection.png
- 11-join-bloqueio-sem-login.png
- 12-join-bloqueio-sem-permissao.png

### 9. Segurança e integridade

Foram preservados os mecanismos de autenticação,
autorização administrativa e consultas preparadas.

A tentativa de acesso direto à gestão de usuários
por uma conta sem permissão administrativa apresentou:

"Acesso restrito a administradores."

Não foram necessárias alterações estruturais
no banco de dados.

### 10. Observações sobre a validação

O INNER JOIN foi escolhido considerando a
obrigatoriedade das chaves estrangeiras.

Os testes locais foram confirmados pelo responsável.
O bloqueio de acesso sem permissão foi verificado
visualmente por meio de captura de tela.

As demais evidências deverão ser conferidas antes
da compactação final do projeto.

### 11. Resultado final

Status: CONCLUÍDO.

A consulta relacional foi implementada e integrada
à interface, atendendo aos requisitos funcionais
do Ponto 08 da Sprint 01, conforme os testes
confirmados pelo responsável.

## PONTO 09 — Confirmação de exclusão de usuários

### 1. Objetivo

Implementar um mecanismo de confirmação antes da
exclusão de usuários, evitando operações acidentais
e permitindo o cancelamento da ação.

### 2. Arquivo envolvido

- usuarios.php

### 3. Implementações realizadas

- Inclusão da coluna Ações na listagem.
- Inclusão do botão Excluir para cada usuário.
- Implementação de janela de confirmação.
- Identificação do usuário selecionado.
- Apresentação da mensagem obrigatória:
  "Deseja realmente excluir este usuário?"
- Inclusão dos botões Cancelar e Confirmar.
- Preservação da identidade visual do PULSE.
- Manutenção da restrição de acesso administrativo.
- Preservação da consulta JOIN e da pesquisa.

### 4. Funcionamento da confirmação

Ao clicar no botão Excluir, o sistema apresenta
uma janela de confirmação contendo a identificação
do usuário selecionado.

O administrador pode cancelar ou confirmar a ação.

O cancelamento fecha a janela sem modificar
os registros armazenados no banco de dados.

Nesta etapa, a confirmação ainda não realiza
a exclusão lógica do usuário, pois essa operação
será implementada no Ponto 10.

### 5. Testes realizados

| Teste | Resultado informado |
|---|---|
| Exibição do botão Excluir | Aprovado |
| Abertura da janela | Aprovado |
| Mensagem obrigatória | Aprovado |
| Identificação do cliente | Aprovado |
| Identificação do vendedor | Aprovado |
| Cancelamento da exclusão | Aprovado |
| Integridade do registro no MySQL | Aprovado |
| Funcionamento do botão Confirmar | Aprovado |

### 6. Validação da integridade no MySQL

Foi utilizada a seguinte consulta para verificar
a integridade do registro após o cancelamento:

```sql
SELECT
    id,
    nome,
    email,
    status,
    baixado_em
FROM usuarios
WHERE id = 5;
```

Resultado confirmado pelo responsável pelos testes:

- ID: 5
- Nome: Cliente Teste
- Status: Ativo
- baixado_em: NULL

O resultado indica que o cancelamento não provocou
a exclusão física ou lógica do registro.

### 7. Evidências

Diretório: docs/evidencias/ponto-09/

- 01-listagem-com-acao-excluir.png
- 02-janela-confirmacao-exclusao.png
- 03-identificacao-cliente.png
- 04-identificacao-vendedor.png
- 05-cancelamento-exclusao.png
- 06-integridade-apos-cancelamento.png
- 07-acao-confirmar-exclusao.png

### 8. Segurança e integridade

Foram preservados os mecanismos de autenticação
e autorização administrativa implementados
nas entregas anteriores.

A janela de confirmação constitui uma proteção
contra operações acidentais na interface.

A segurança da operação de exclusão no servidor
será implementada no Ponto 10, utilizando POST,
validação CSRF, autorização administrativa,
validação do identificador e registro de auditoria.

### 9. Observações sobre a validação

Foi adotado o elemento nativo dialog do HTML
para a implementação da janela de confirmação,
sem necessidade de bibliotecas externas.

Os testes locais foram aprovados conforme
as confirmações do responsável pela execução.

As capturas de tela deverão ser conferidas
antes da compactação final do projeto.

### 10. Resultado final

Status: CONCLUÍDO.

O mecanismo de confirmação de exclusão foi
implementado conforme os requisitos funcionais
do Ponto 09 da Sprint 01.

A exclusão lógica efetiva foi integrada posteriormente no Ponto 10.

## PONTO 10 — Exclusão segura, auditoria e entrega integrada

### 1. Objetivo

Implementar a exclusão lógica de usuários com segurança,
integridade, rastreabilidade e confirmação explícita,
preservando os registros armazenados no MySQL.

### 2. Arquivos envolvidos

- usuarios.php
- excluir_usuario.php
- includes/auth.php
- includes/auditoria.php
- database/conexao.php

### 3. Implementações realizadas

- Exclusão lógica mediante confirmação.
- Processamento pelo método POST.
- Validação de token CSRF.
- Validação da autorização administrativa.
- Validação do identificador do usuário.
- Proteção contra exclusão da própria conta.
- Atualização do status para Baixa.
- Registro da data e hora da baixa.
- Utilização de consultas preparadas com PDO.
- Utilização de transações.
- Registro da operação na auditoria do sistema.
- Tratamento de tentativas de exclusão repetidas.
- Atualização automática da listagem.
- Preservação da consulta JOIN.
- Preservação da pesquisa por nome e e-mail.

### 4. Funcionamento da exclusão lógica

O administrador seleciona o usuário e aciona
o botão Excluir.

Após a confirmação, os dados são enviados ao
arquivo excluir_usuario.php pelo método POST.

O servidor valida a autorização administrativa,
o token CSRF e o identificador recebido.

A operação altera o status do usuário para Baixa
e registra a data da operação.

O registro permanece armazenado no MySQL,
preservando o histórico e os relacionamentos.

A atualização e a auditoria são realizadas
dentro de uma transação.

### 5. Consulta SQL de referência

```sql
UPDATE usuarios
SET
    status = 'Baixa',
    baixado_em = CURRENT_TIMESTAMP
WHERE id = :id
AND status = 'Ativo';
```

A consulta representa a operação de baixa lógica,
sem utilizar DELETE para remover fisicamente
o registro.

### 6. Resultado da exclusão

Usuário utilizado no teste:

- ID: 5
- Nome: Cliente Teste
- E-mail: cliente@pulse.example

| Informação | Antes | Depois |
|---|---|---|
| Status | Ativo | Baixa |
| baixado_em | NULL | Data e hora da baixa |
| Usuários ativos | 9 | 8 |
| Registro no MySQL | Existente | Preservado |
| Visível na listagem | Sim | Não |

Mensagem apresentada pelo sistema:

"Usuário excluído com sucesso (baixa lógica)."

### 7. Validação da persistência

```sql
SELECT
    id,
    nome,
    email,
    status,
    criado_em,
    atualizado_em,
    baixado_em
FROM usuarios
WHERE id = 5;
```

Conforme confirmação do responsável pelos testes,
o registro permaneceu armazenado no banco,
com status Baixa e data da operação preenchida.

### 8. Auditoria e rastreabilidade

A tabela auditoria_sistema foi utilizada para
registrar a operação de exclusão lógica.

```sql
SELECT
    id,
    responsavel_id,
    usuario_afetado_id,
    acao,
    entidade,
    registro_id,
    descricao,
    criado_em
FROM auditoria_sistema
WHERE usuario_afetado_id = 5
ORDER BY id DESC;
```

A auditoria permite identificar:

- Responsável pela operação.
- Usuário afetado.
- Operação realizada.
- Resultado da operação.
- Data e hora do registro.

A existência do registro de auditoria foi
confirmada pelo responsável pelos testes locais.

### 9. Testes realizados

| Teste | Resultado |
|---|---|
| Verificação do mecanismo InnoDB | Aprovado |
| Janela de confirmação | Aprovado |
| Cancelamento da exclusão | Aprovado |
| Exclusão lógica | Aprovado |
| Persistência do registro | Aprovado |
| Registro de auditoria | Aprovado |
| Bloqueio GET sem ID | Aprovado |
| Bloqueio GET com ID | Aprovado |
| Bloqueio CSRF ausente | Aprovado |
| Bloqueio CSRF inválido | Aprovado |
| Bloqueio de ID inválido | Aprovado |
| Exclusão repetida | Aprovado |
| Preservação da data da baixa | Aprovado |
| Pesquisa por usuário ativo | Aprovado |
| Pesquisa por usuário baixado | Aprovado |
| Consulta JOIN | Aprovado |
| Acesso sem autenticação | Aprovado |
| Acesso como cliente | Aprovado |
| POST sem autorização administrativa | Aprovado |

Resultados registrados conforme confirmações
do responsável pela execução e evidências
visuais disponibilizadas durante os testes.

### 10. Tratamento de exclusão repetida

Ao tentar realizar novamente a baixa do usuário
de ID 5, o sistema apresentou:

"Usuário inexistente ou já baixado.
Nenhuma alteração realizada."

A listagem permaneceu com oito usuários ativos.

A preservação da data original da baixa foi
confirmada pelo responsável pelos testes.

### 11. Correções realizadas

Durante a execução, foi identificada a utilização
da versão demonstrativa da confirmação de exclusão.

A falha foi corrigida mediante substituição
e sincronização dos arquivos PHP atualizados
entre as pastas de desenvolvimento e execução.

Após a correção, a exclusão lógica foi realizada
com sucesso, conforme evidência da interface.

Também foi restaurado o nome original do setor
Comercial, utilizado temporariamente no teste
de atualização dinâmica do JOIN do Ponto 08.

### 12. Evidências

Diretório: docs/evidencias/ponto-10/

- 01-validacao-mecanismo-transacional.png
- 02-confirmacao-exclusao-logica.png
- 03-cancelamento-exclusao-interface.png
- 04-cancelamento-integridade-mysql.png
- 05-exclusao-logica-sucesso.png
- 06-persistencia-baixa-mysql.png
- 07-auditoria-exclusao-logica.png
- 08-bloqueio-requisicao-get.png
- 09-integridade-apos-requisicao-get.png
- 10-bloqueio-csrf-ausente.png
- 11-bloqueio-csrf-invalido.png
- 12-bloqueio-id-invalido.png
- 13-integridade-apos-testes-seguranca.png
- 14-bloqueio-exclusao-repetida.png
- 15-integridade-apos-exclusao-repetida.png
- 16-regressao-listagem-join.png
- 17-pesquisa-usuario-ativo.png
- 18-pesquisa-usuario-baixado.png
- 19-regressao-sem-autenticacao.png
- 20-regressao-sem-permissao.png
- 21-bloqueio-post-sem-permissao.png

### 13. Segurança e integridade

A exclusão física foi substituída pela baixa lógica.

Foram adotados mecanismos de proteção contra
requisições indevidas, operações não autorizadas
e duplicação da baixa.

A utilização de transações busca garantir
a consistência entre a atualização do usuário
e o registro de auditoria.

### 14. Observações e pendências

Os testes funcionais foram confirmados pelo
responsável pela execução local.

Foram verificadas visualmente as evidências de:

- Exclusão lógica com sucesso.
- Redução de nove para oito usuários ativos.
- Bloqueio de requisições GET.
- Integridade do Vendedor Teste no MySQL.
- Tratamento de exclusão repetida.
- Restauração do nome original do setor Comercial.

As demais evidências dependem de conferência
durante a revisão final.

A reversão de uma transação mediante falha
forçada de auditoria não foi testada diretamente.

O bloqueio da exclusão da própria conta também
não foi testado separadamente.

O pacote ZIP definitivo ainda precisa ser
organizado e validado.

### 15. Resultado final

Status funcional: CONCLUÍDO.

Status da entrega integrada:
AGUARDANDO ORGANIZAÇÃO DO PACOTE FINAL.

A implementação atende aos requisitos funcionais
testados do Ponto 10 da Sprint 01, conforme
os resultados confirmados pelo responsável.

---

## Revisão de preparação da entrega — 25/11/2026

- Scripts SQL e conexão PDO padronizados para `db_pulse`; a instalação histórica permanece registrada separadamente.
- A revisão de `excluir_usuario.php` passou a atualizar `status = 'Baixa'`, `condicao = 'baixa'` e `baixado_em` na mesma operação transacional com auditoria.
- Revisão do tratamento de falhas de conexão em `cadastro.php`, `login.php` e `includes/auth.php`.
- Evidências complementares de regressão revisadas visualmente: listagem, pesquisa de ativo e baixado, redirecionamento sem autenticação, bloqueio de cliente e POST sem autorização (Ponto 10, capturas 16 a 21). Conferir os arquivos no pacote final.
- Reteste integrado da versão revisada pendente: instalação isolada, cadastro, login, baixa com ambos os campos e auditoria. O tipo root e a estrutura de sessões não comprovam implementação de permissões exclusivas ou rastreamento automático de sessões.

## Matriz consolidada de testes obrigatórios — T01 a T12

Data registrada da execução: 24/11/2026. Responsável: executor local da equipe (nome a preencher). A matriz consolida os resultados relatados nos pontos anteriores; os arquivos indicados devem ser conferidos no ZIP. Não equivale a uma nova execução dos testes.

| ID | Cenário | Resultado esperado | Resultado registrado | Situação | Evidência (relativa a `docs/`) |
|---|---|---|---|---|---|
| T01 | Cadastro válido | Persistir usuário único | Cadastro persistido e confirmado | Atendeu | `evidencias/ponto-06/01-insert-persistencia-mysql.png` |
| T02 | Dados inválidos | Rejeitar entradas inválidas no servidor | Campos vazios, e-mail inválido e senha curta rejeitados | Atendeu | `evidencias/ponto-05/01` a `04` |
| T03 | E-mail duplicado | Rejeitar sem duplicar | Duplicidade rejeitada; um registro confirmado | Atendeu | `evidencias/ponto-06/02` e `03` |
| T04 | FK inexistente | Rejeitar referência inválida | Setor inexistente bloqueado; perfil não autorizado bloqueado | Atendeu no cenário registrado | `evidencias/ponto-05/05` e `06`; Ponto 02 |
| T05 | Pesquisa existente e inexistente | Retornar correspondências ou mensagem vazia | Pesquisa por nome/e-mail e ausência de resultados aprovadas | Atendeu | `evidencias/ponto-07/02` a `04` |
| T06 | JOIN | Exibir setor e perfil corretos, sem duplicação | Consulta e interface aprovadas conforme relato | Atendeu | `evidencias/ponto-08/01` a `04` |
| T07 | Alterar referência | Refletir novo nome na interface | Comercial alterado temporariamente e restaurado | Atendeu | `evidencias/ponto-08/05` e `06`; Ponto 10 |
| T08 | Cancelar exclusão | Preservar registro | Usuário permaneceu ativo após cancelamento | Atendeu | `evidencias/ponto-09/05` e `06` |
| T09 | Confirmar baixa | Preservar registro, registrar data e auditoria, retirar da lista ativa | ID 5 baixado; listagem de 9 para 8 ativos | Atendeu | `evidencias/ponto-10/05` a `07` |
| T10 | GET, CSRF ausente/inválido e ID inválido | Bloquear operações indevidas | Bloqueios registrados; ID 2 permaneceu ativo | Atendeu | `evidencias/ponto-10/08` a `13` |
| T11 | Repetir baixa | Não alterar registro ou data original | Mensagem de usuário inexistente ou já baixado; data preservada conforme relato | Atendeu | `evidencias/ponto-10/14` e `15` |
| T12 | Caracteres especiais e SQL Injection | Tratar entrada sem execução indevida | Tentativa de injeção não retornou registros indevidos; teste separado de caracteres especiais não detalhado | Parcialmente documentado | `evidencias/ponto-07/05`; `evidencias/ponto-08/10` |

## Registro final da equipe

| Ponto | Arquivos principais | Resultado funcional registrado |
|---|---|---|
| 01 | `index.php`, inventário do projeto | Atendeu |
| 02 | `database/instruction.sql`, migrations 001–004 | Atendeu |
| 03 | `database/conexao.php`, `teste_conexao.php` | Atendeu |
| 04 | `cadastro.php`, `database/conexao.php` | Atendeu |
| 05 | `cadastro.php`, `includes/auth.php` | Atendeu |
| 06 | `cadastro.php`, `dashboard.php`, `includes/auditoria.php` | Atendeu com ressalvas de cobertura |
| 07 | `usuarios.php`, `login.php`, `dashboard.php`, `includes/auth.php`, `js/site.js` | Atendeu funcionalmente |
| 08 | `usuarios.php` | Atendeu funcionalmente |
| 09 | `usuarios.php` | Atendeu funcionalmente |
| 10 | `usuarios.php`, `excluir_usuario.php`, `includes/auth.php`, `includes/auditoria.php` | Atendeu funcionalmente; entrega integrada pendente |

### Pendências finais e impactos

- Conferir existência, nomenclatura e conteúdo das capturas, incluindo a evidência 59 ausente no Ponto 02 e os complementos do Ponto 07.
- Conferir a versão definitiva dos scripts SQL e repetir a instalação se houver alterações após o teste registrado.
- Validar README, versão do código, identificação dos responsáveis e pacote ZIP sem backups, credenciais ou resíduos; retirar o script temporário de conexão se não for necessário.
- Confirmar regressão das funcionalidades comerciais e alternância de tema na versão definitiva.
- Complementar o teste de caracteres especiais (T12); os testes de falha forçada de auditoria, exclusão da própria conta e exceção de FK no INSERT não foram executados diretamente conforme o relatório.
- Conferir sincronização entre a pasta de desenvolvimento e a pasta executada pelo XAMPP.

Responsável pelas pendências: Bruno Renato.

### Conclusão geral

Os resultados obtidos ao longo da Sprint 01 demonstram a implementação funcional do módulo de cadastro e gestão de usuários do sistema PULSE, contemplando a persistência de dados, a integridade referencial, as consultas relacionais, a validação de entradas, o controle de acesso e a exclusão lógica com registro de auditoria.

Os testes realizados permitiram verificar o funcionamento das funcionalidades implementadas, identificar inconsistências e aplicar as correções necessárias, preservando a estrutura e a identidade visual do sistema.

O trabalho desenvolvido estabelece uma base sólida, segura e organizada para a continuidade do projeto, contribuindo para a evolução das funcionalidades e o aprimoramento da aplicação nas próximas sprints.

**Situação funcional:** dez pontos implementados conforme o relato de execução.  
**Situação da entrega integrada:** pendente de conferência e organização do pacote final.  
**Parecer do professor:** a preencher.
