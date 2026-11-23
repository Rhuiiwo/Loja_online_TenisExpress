(() => {
    "use strict";

    const CHAVE_CARRINHO = "pulse_carrinho";

    function lerCarrinho() {
        try {
            const dados = JSON.parse(localStorage.getItem(CHAVE_CARRINHO)) || [];
            return Array.isArray(dados) ? dados : [];
        } catch (erro) {
            console.error("Não foi possível ler o carrinho:", erro);
            return [];
        }
    }

    function salvarCarrinho(itens) {
        localStorage.setItem(CHAVE_CARRINHO, JSON.stringify(itens));
        atualizarInterface();
    }

    function formatarMoeda(valor) {
        return Number(valor).toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
    }

    function converterPreco(texto) {
        // Ex.: "R$ 1.299,90" -> 1299.90
        const numero = texto
            .replace(/[^\d,.-]/g, "")
            .replace(/\./g, "")
            .replace(",", ".");
        return Number(numero);
    }

    function totalDeUnidades(itens) {
        return itens.reduce((soma, item) => soma + item.quantidade, 0);
    }

    function valorTotal(itens) {
        return itens.reduce((soma, item) => soma + item.preco * item.quantidade, 0);
    }

    function atualizarContadores(itens) {
        const quantidade = totalDeUnidades(itens);
        const linkCarrinho = document.getElementById("link-menu-carrinho");
        const qtdResumo = document.getElementById("qtd-itens-carrinho");
        const valorResumo = document.getElementById("valor-total-carrinho");

        if (linkCarrinho) {
            linkCarrinho.textContent = `Carrinho (${quantidade})`;
        }

        if (qtdResumo) {
            qtdResumo.textContent = quantidade;
        }

        if (valorResumo) {
            valorResumo.textContent = formatarMoeda(valorTotal(itens));
        }

        // Atualiza o título da página do carrinho, se o elemento existir.
        const contadorTitulo = document.getElementById("contador-titulo-carrinho");
        if (contadorTitulo) {
            contadorTitulo.textContent =
                `(${quantidade} ${quantidade === 1 ? "produto" : "produtos"})`;
        }
    }

    function adicionarProduto(botao) {
        const card = botao.closest(".produto-card");
        if (!card) return;

        const nome = card.querySelector(".produto-titulo")?.textContent.trim();
        const precoTexto = card.querySelector(".produto-preco")?.textContent.trim();
        const imagem = card.querySelector(".produto-imagem img")?.getAttribute("src") || "";
        const preco = converterPreco(precoTexto || "");

        if (!nome || !Number.isFinite(preco) || preco <= 0) {
            console.error("Produto sem nome ou preço válido.");
            return;
        }

        const itens = lerCarrinho();
        const id = nome.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/^-|-$/g, "");

        const existente = itens.find(item => item.id === id);

        if (existente) {
            existente.quantidade += 1;
        } else {
            itens.push({ id, nome, preco, imagem, quantidade: 1 });
        }

        salvarCarrinho(itens);

        const textoOriginal = botao.textContent;
        botao.textContent = "Adicionado ✓";
        botao.disabled = true;
        window.setTimeout(() => {
            botao.textContent = textoOriginal;
            botao.disabled = false;
        }, 900);
    }

    function criarElemento(tag, classe, texto) {
        const elemento = document.createElement(tag);
        if (classe) elemento.className = classe;
        if (texto !== undefined) elemento.textContent = texto;
        return elemento;
    }

    function renderizarCarrinho() {
        const lista = document.getElementById("lista-itens-carrinho");
        const resumo = document.getElementById("resumo-pedido-dinamico");
        if (!lista || !resumo) return;

        const itens = lerCarrinho();
        lista.replaceChildren();
        resumo.replaceChildren();

        if (itens.length === 0) {
            const vazio = criarElemento("li", "carrinho-vazio");
            const botaoVerProdutos = criarElemento("a", "btn-primario", "Ver produtos");
            botaoVerProdutos.href = "produtos.php";

            vazio.append(
                criarElemento("p", "", "Seu carrinho está vazio."),
                botaoVerProdutos
            );
            lista.append(vazio);

            resumo.append(
                criarElemento("h2", "", "Resumo do pedido"),
                criarElemento("p", "", "Adicione produtos para visualizar o total.")
            );
            return;
        }

        itens.forEach(item => {
            const linha = criarElemento("li", "item-produto-destaque");

            const quadroImagem = criarElemento("div", "item-imagem-quadro");

            const imagem = document.createElement("img");
            imagem.src = item.imagem;
            imagem.alt = item.nome;
            imagem.loading = "lazy";
            imagem.className = "item-imagem-pequena";
            quadroImagem.append(imagem);

            const detalhes = criarElemento("div", "item-info-detalhe");
            detalhes.append(
                criarElemento("h3", "item-titulo-pequeno", item.nome),
                criarElemento("span", "item-unitario", `${formatarMoeda(item.preco)} cada`)
            );

            const controles = criarElemento("div", "item-controles-qtd");

            const diminuir = criarElemento("button", "btn-qtd", "−");
            diminuir.type = "button";
            diminuir.setAttribute("aria-label", `Diminuir quantidade de ${item.nome}`);
            diminuir.addEventListener("click", () => alterarQuantidade(item.id, -1));

            const quantidade = criarElemento("span", "item-qtd-valor", String(item.quantidade));

            const aumentar = criarElemento("button", "btn-qtd", "+");
            aumentar.type = "button";
            aumentar.setAttribute("aria-label", `Aumentar quantidade de ${item.nome}`);
            aumentar.addEventListener("click", () => alterarQuantidade(item.id, 1));

            const remover = criarElemento("button", "btn-remover-item", "✕");
            remover.type = "button";
            remover.title = "Remover item";
            remover.setAttribute("aria-label", `Remover ${item.nome}`);
            remover.addEventListener("click", () => removerProduto(item.id));

            controles.append(diminuir, quantidade, aumentar, remover);

            const subtotal = criarElemento(
                "strong",
                "item-preco-pequeno",
                formatarMoeda(item.preco * item.quantidade)
            );

            linha.append(quadroImagem, detalhes, controles, subtotal);
            lista.append(linha);
        });

        const total = valorTotal(itens);
        const totalUnidades = totalDeUnidades(itens);

        const linhaItens = criarElemento("div", "resumo-linha");
        linhaItens.append(
            criarElemento("span", "", `Produtos (${totalUnidades} ${totalUnidades === 1 ? "item" : "itens"})`),
            criarElemento("span", "", formatarMoeda(total))
        );

        const linhaFrete = criarElemento("div", "resumo-linha desconto");
        linhaFrete.append(
            criarElemento("span", "", "Frete"),
            criarElemento("span", "", "Grátis")
        );

        const linhaTotal = criarElemento("div", "resumo-total");
        linhaTotal.append(
            criarElemento("span", "", "Total"),
            criarElemento("span", "", formatarMoeda(total))
        );

        const finalizar = criarElemento("button", "btn-finalizar", "Finalizar compra");
        finalizar.type = "button";
        finalizar.addEventListener("click", () => {
            alert(`Pedido confirmado! Total: ${formatarMoeda(total)}`);
        });

        const continuar = criarElemento("a", "btn-continuar", "Continuar comprando");
        continuar.href = "produtos.php";

        resumo.append(
            criarElemento("h2", "", "Resumo do pedido"),
            linhaItens,
            linhaFrete,
            linhaTotal,
            finalizar,
            continuar
        );
    }

    function alterarQuantidade(id, variacao) {
        const itens = lerCarrinho();
        const item = itens.find(produto => produto.id === id);
        if (!item) return;

        item.quantidade += variacao;
        const atualizados = itens.filter(produto => produto.quantidade > 0);
        salvarCarrinho(atualizados);
    }

    function removerProduto(id) {
        salvarCarrinho(lerCarrinho().filter(item => item.id !== id));
    }

    function atualizarInterface() {
        const itens = lerCarrinho();
        atualizarContadores(itens);
        renderizarCarrinho();
    }

    document.addEventListener("click", evento => {
        const botao = evento.target.closest(".btn-add-carrinho");
        if (botao) adicionarProduto(botao);
    });

    document.addEventListener("DOMContentLoaded", atualizarInterface);
    // Atualiza a interface caso o carrinho seja alterado em outra aba.
    window.addEventListener("storage", evento => {
        if (evento.key === CHAVE_CARRINHO) atualizarInterface();
    });

    // Executa também se este script for carregado depois do DOMContentLoaded.
    if (document.readyState !== "loading") atualizarInterface();
})();
