# Modelo de dados

O esquema é criado pelo script [`instruction.sql`](../database/instruction.sql) e pelas quatro migrations descritas no [README](../README.md). A instalação da Sprint foi validada em `db_pulse_instalacao_teste`. Os registros persistentes descritos abaixo usam InnoDB e chaves estrangeiras para manter a integridade entre entidades.

## Atualização do modelo de dados — Sprint 01

### Estrutura de usuários

| Campo | Tipo | Restrição |
|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| nome | VARCHAR(150) | NOT NULL |
| email | VARCHAR(190) | NOT NULL, UNIQUE |
| senha_hash | VARCHAR(255) | NOT NULL |
| telefone | VARCHAR(20) | Opcional |
| cpf | CHAR(11) | UNIQUE, opcional |
| tipo | ENUM | cliente, vendedor, administrador |
| setor_id | BIGINT UNSIGNED | FK, NOT NULL |
| perfil_id | BIGINT UNSIGNED | FK, NOT NULL |
| status | ENUM | Ativo, Baixa |
| criado_em | TIMESTAMP | Data de criação |
| atualizado_em | TIMESTAMP | Atualização automática |
| baixado_em | TIMESTAMP | Data da baixa, opcional |

### Tabelas de referência

As tabelas `setores` e `perfis` possuem os campos `id`, `nome` e `ativo`, com chave primária, unicidade do nome e controle de ativação.

### Relacionamentos

- `usuarios.setor_id` → `setores.id`
- `usuarios.perfil_id` → `perfis.id`

Os relacionamentos possuem cardinalidade 1:N e restrições ON DELETE RESTRICT e ON UPDATE RESTRICT.

A utilização de chaves estrangeiras evita referências inválidas e a duplicação desnecessária dos nomes de setores e perfis.

### Índices

- PRIMARY
- email
- cpf
- idx_usuarios_setor
- idx_usuarios_perfil
- idx_usuarios_status

### Rastreabilidade

A estrutura contempla as tabelas `sessoes_usuarios` e `auditoria_sistema`, destinadas ao controle de sessões e ao registro de operações.

A auditoria de cadastro e baixa lógica registra operações em `auditoria_sistema`. A estrutura de sessões existe, mas o registro automático de início e fim de cada sessão não foi comprovado no material revisado.

A baixa lógica altera `usuarios.status` para `Baixa` e preenche `baixado_em`, preservando o registro e seus relacionamentos. O campo `tipo` é utilizado na autorização administrativa da aplicação.

A tabela `auditoria_sistema` possui identificadores do responsável e do usuário afetado (quando disponíveis), ação, entidade, registro, descrição e data de criação, além de campos opcionais de valores anterior/novo e IP. Senhas e tokens não devem ser registrados.

### Consulta relacional

```sql
SELECT u.id, u.nome, u.email, s.nome AS setor,
       p.nome AS perfil, u.status
FROM usuarios AS u
INNER JOIN setores AS s ON s.id = u.setor_id
INNER JOIN perfis AS p ON p.id = u.perfil_id
WHERE u.status = 'Ativo'
ORDER BY u.nome, u.id;
```

As condições ON associam as chaves estrangeiras às chaves primárias, evitando multiplicação artificial de registros. A consulta reflete alterações nos nomes das referências sem duplicá-los na tabela de usuários.

### Validação estrutural

A instalação completa foi validada no banco `db_pulse_instalacao_teste`, apresentando 15 tabelas, 19 chaves estrangeiras, cinco setores, cinco perfis e seis usuários fictícios, sem registros órfãos.

## Entidades

| Tabela | Finalidade | Relações principais |
| --- | --- | --- |
| `usuarios` | Contas de clientes, vendedores e administradores. | Referenciada por perfis de vendedor, carrinhos e pedidos. |
| `setores` | Referência de setores. | Um setor pode ter vários usuários. |
| `perfis` | Referência de perfis gerais. | Um perfil pode ter vários usuários. |
| `sessoes_usuarios` | Estrutura de rastreabilidade de sessões. | Vinculada a usuários. |
| `auditoria_sistema` | Registro de operações. | Responsável e usuário afetado, quando disponíveis. |
| `perfis_vendedor` | Informações comerciais e estado do cadastro do vendedor. | Pertence a um usuário. |
| `categorias` | Classificação dos produtos. | Pode ser associada a vários produtos. |
| `fornecedores` | Cadastro de fornecedores. | Pode ser associado a vários produtos. |
| `produtos` | Catálogo, preço, descrição e estado de publicação. | Relaciona vendedor, categoria, fornecedor e estoque. |
| `estoque` | Quantidade e localização por produto. | Cada registro corresponde a um produto. |
| `carrinhos` | Carrinhos persistentes associados a clientes. | Possui itens de carrinho. |
| `itens_carrinho` | Produto e quantidade em cada carrinho. | Chave composta por carrinho e produto. |
| `pedidos` | Pedido, cliente, canal, estado e valor total. | Possui itens de pedido e pagamentos. |
| `itens_pedido` | Produtos, quantidades e preços registrados no pedido. | Pertence a um pedido e pode referenciar um produto. |
| `pagamentos` | Método, estado, valor e data de pagamento do pedido. | Cada pagamento pertence a um pedido. |

## Relações e regras

- Usuários podem ter um perfil de vendedor; a relação é única por usuário.
- Produtos podem referenciar vendedor, categoria e fornecedor. A remoção dessas entidades preserva o produto e limpa a referência.
- O estoque é identificado pelo produto e removido junto com ele.
- Um carrinho reúne produtos por meio de `itens_carrinho`, com quantidade maior ou igual a zero.
- Cada pedido pode ter vários itens e pagamentos. A exclusão do pedido remove os itens e pagamentos associados.
- Valores de produto, pedido, item e pagamento têm restrições para impedir preços negativos.

## Observação sobre persistência

O cadastro e o login utilizam a tabela `usuarios`. O carrinho da interface permanece no armazenamento local do navegador e o checkout da interface não cria pedidos nem persiste dados financeiros. As demais tabelas estão disponíveis no esquema para as próximas operações da aplicação.

## Limite da validação

Este modelo descreve a estrutura de 13 campos de `usuarios` e seis índices registrada nos testes da Sprint. O documento anterior também mencionava `root`, `condicao` e `idx_usuario_tipo_condicao`, que não constavam dessa estrutura validada. Caso tenham sido adicionados posteriormente, será necessário conferir o SQL definitivo e atualizar este modelo. A existência das tabelas não comprova, por si só, a integração funcional de todos os módulos.

## Inicialização

Importe `database/instruction.sql` e as quatro migrations, na ordem indicada no README. Em seguida, confira host, banco e credenciais em [`conexao.php`](../database/conexao.php).
