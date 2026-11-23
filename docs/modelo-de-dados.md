# Modelo de dados

O esquema é criado pelo script [`instruction.sql`](../database/instruction.sql) no banco `db_pulse`. Os registros persistentes descritos abaixo usam InnoDB e chaves estrangeiras para manter a integridade entre entidades.

## Entidades

| Tabela | Finalidade | Relações principais |
| --- | --- | --- |
| `usuarios` | Contas de clientes, vendedores e administradores. | Referenciada por perfis de vendedor, carrinhos e pedidos. |
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

## Inicialização

Importe `database/instruction.sql` no MySQL ou pelo phpMyAdmin. Em seguida, confira host, banco e credenciais em [`conexao.php`](../database/conexao.php).
