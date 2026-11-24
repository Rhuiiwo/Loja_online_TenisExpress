# Manual do usuário — PULSE

## Acessar a PULSE

Para a configuração local, inicie Apache e MySQL pelo XAMPP Control Panel e abra [http://localhost/pulse/index.php](http://localhost/pulse/index.php). Esse endereço pressupõe que a pasta do projeto se chama `pulse` e está diretamente dentro da pasta `htdocs` do XAMPP. Se a pasta tiver outro nome, substitua `pulse` no endereço pelo nome usado.

O caminho `C:\xampp\htdocs\pulse\index.php` identifica um arquivo no computador; não é um endereço para abrir o site. Use o endereço `http://localhost/...` no navegador para que o Apache execute o PHP. Consulte o [README](../README.md) para instalar o projeto, importar o banco de dados e solucionar erros de acesso.

A navegação superior dá acesso ao início, catálogo, informações da loja, contatos e painel.

## Localizar produtos

Abra **Produtos** para consultar as categorias e itens disponíveis. 

## Montar o carrinho

Selecione **Adicionar ao Carrinho** em um item para adicionar o produto ao carrinho. O ícone do carrinho apresenta a quantidade de itens. No painel, é possível remover produtos, consultar o total e avançar para o pagamento. Os itens ficam no armazenamento local do navegador.

## Criar conta ou entrar

1. Abra a página de cadastro.
2. Informe nome, e-mail e senha de pelo menos oito caracteres.
3. Selecione um setor disponível e o perfil Cliente, permitido no cadastro público.
4. Clique em Cadastrar. Corrija eventuais mensagens de validação.
5. Para entrar novamente, utilize o e-mail e a senha cadastrados na página Login.

O e-mail deve ser único. Contas baixadas não podem autenticar-se.

## Gestão de usuários (administrador)

1. Entre com uma conta administrativa de homologação fornecida pela equipe.
2. No Dashboard, clique em **Gestão de usuários**.
3. Consulte os usuários ativos, seus setores, perfis e status.
4. Pesquise por nome ou e-mail. Use **Limpar** para retornar à lista completa.

### Excluir um usuário

1. Localize o usuário e clique em **Excluir**.
2. Confira a identificação do usuário na confirmação “Deseja realmente excluir este usuário?”.
3. Clique em **Cancelar** para manter o cadastro ou em **Confirmar** para realizar a baixa.
4. Após a confirmação bem-sucedida, o usuário deixa de aparecer na lista de ativos, mas seu registro permanece no banco.

A operação é restrita a administradores e registrada em auditoria. Uma tentativa repetida sobre usuário já baixado não deve produzir nova baixa. Usuários comuns não têm acesso à gestão administrativa.

Não utilize contas reais para demonstrar a exclusão.

## Revisar o método de pagamento

Na página de pagamento:

1. Selecione débito, crédito ou Pix.
2. Para cartão, informe titular, número, validade e código de segurança.
3. Para Pix, informe a chave solicitada no formulário.
4. Acione **Revisar pagamento** para validar os campos preenchidos.

Os campos mudam conforme a opção selecionada. Dados de pagamento não são armazenados no navegador pelo carrinho nem persistidos no banco de dados. O sistema exige integração com um provedor de pagamentos para autorizar transações.

## Preferências e atendimento

A página **Contatos** informa os canais e horários de atendimento.