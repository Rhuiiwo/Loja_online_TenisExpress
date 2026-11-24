# Manual de uso

## Acessar a PULSE

Para a configuração local, inicie Apache e MySQL pelo XAMPP Control Panel e abra [http://localhost/pulse/index.php](http://localhost/pulse/index.php). Esse endereço pressupõe que a pasta do projeto se chama `pulse` e está diretamente dentro da pasta `htdocs` do XAMPP. Se a pasta tiver outro nome, substitua `pulse` no endereço pelo nome usado.

O caminho `C:\xampp\htdocs\pulse\index.php` identifica um arquivo no computador; não é um endereço para abrir o site. Use o endereço `http://localhost/...` no navegador para que o Apache execute o PHP. Consulte o [README](../README.md) para instalar o projeto, importar o banco de dados e solucionar erros de acesso.

A navegação superior dá acesso ao início, catálogo, informações da loja, contatos e painel.

## Localizar produtos

Abra **Produtos** para consultar as categorias e itens disponíveis. 

## Montar o carrinho

Selecione **Adicionar ao Carrinho** em um item para adicionar o produto ao carrinho. O ícone do carrinho apresenta a quantidade de itens. No painel, é possível remover produtos, consultar o total e avançar para o pagamento. Os itens ficam no armazenamento local do navegador.

## Criar conta ou entrar

Use **Cadastro** para criar uma conta com nome, e-mail e senha de pelo menos oito caracteres. Para uma conta existente, use **Login** com o e-mail e a senha cadastrados.

## Revisar o método de pagamento

Na página de pagamento:

1. Selecione débito, crédito ou Pix.
2. Para cartão, informe titular, número, validade e código de segurança.
3. Para Pix, informe a chave solicitada no formulário.
4. Acione **Revisar pagamento** para validar os campos preenchidos.

Os campos mudam conforme a opção selecionada. Dados de pagamento não são armazenados no navegador pelo carrinho nem persistidos no banco de dados. O sistema exige integração com um provedor de pagamentos para autorizar transações.

## Preferências e atendimento

A página **Contatos** informa os canais e horários de atendimento.