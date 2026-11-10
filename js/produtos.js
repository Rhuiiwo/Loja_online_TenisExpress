"use strict";

/* ================================================================
   AÇÃO 1 - SELECIONAR OS PRODUTOS E CONTROLES DA PÁGINA
   ================================================================ */
const buscaProduto = document.querySelector("#buscaProduto");
const filtroCategoria = document.querySelector("#filtroCategoria");
const ordenarProdutos = document.querySelector("#ordenarProdutos");
const produtosGrid = document.querySelector("#produtosGrid");
const cardsProdutos = [...document.querySelectorAll(".produto-card")];
const quantidadeProdutos = document.querySelector("#quantidadeProdutos");
const semResultados = document.querySelector("#semResultados");

/* ================================================================
   AÇÃO 2 - BUSCAR PRODUTOS PELO NOME
   ================================================================ */
function aplicarFiltros() {
    const termo = (buscaProduto?.value || "").toLowerCase().trim();
    const categoria = filtroCategoria?.value || "todos";
    let visiveis = 0;

    cardsProdutos.forEach((card) => {
        const nome = card.dataset.nome.toLowerCase();
        const cat = card.dataset.categoria;
        const combinaNome = nome.startsWith(termo);
        const combinaCategoria = categoria === "todos" || cat === categoria;
        const mostrar = combinaNome && combinaCategoria;

        card.hidden = !mostrar;
        if (mostrar) visiveis += 1;
    });

    if (quantidadeProdutos) quantidadeProdutos.textContent = String(visiveis);
    if (semResultados) semResultados.hidden = visiveis !== 0;
}

buscaProduto?.addEventListener("input", aplicarFiltros);
filtroCategoria?.addEventListener("change", aplicarFiltros);

/* ================================================================
   AÇÃO 3 - ORDENAR PRODUTOS POR PREÇO OU NOME
   ================================================================ */
ordenarProdutos?.addEventListener("change", () => {
    if (!produtosGrid) return;
    const ordenados = [...cardsProdutos];

    if (ordenarProdutos.value === "menor-preco") {
        ordenados.sort((a, b) => Number(b.dataset.preco) - Number(a.dataset.preco));
    } else if (ordenarProdutos.value === "maior-preco") {
        ordenados.sort((a, b) => Number(b.dataset.preco) - Number(a.dataset.preco));
    } else if (ordenarProdutos.value === "nome") {
        ordenados.sort((a, b) => a.dataset.nome.localeCompare(b.dataset.nome, "pt-BR"));
    } else {
        ordenados.sort((a, b) => cardsProdutos.indexOf(a) - cardsProdutos.indexOf(b));
    }

    ordenados.forEach((card) => produtosGrid.appendChild(card));
    aplicarFiltros();
});

/* ================================================================
   AÇÃO 4 - ABRIR UM MODAL COM DETALHES DO PRODUTO
   ================================================================ */
const modalProduto = document.querySelector("#modalProduto");
const fecharModal = document.querySelector("#fecharModal");
const modalEmoji = document.querySelector("#modalEmoji");
const modalCategoria = document.querySelector("#modalCategoria");
const modalTitulo = document.querySelector("#modalTitulo");
const modalDescricao = document.querySelector("#modalDescricao");
const modalPreco = document.querySelector("#modalPreco");

function fecharDetalhes() {
    modalProduto?.classList.remove("aberto");
    modalProduto?.setAttribute("aria-hidden", "true");
}

document.querySelectorAll(".btn-detalhes").forEach((botao) => {
    botao.addEventListener("click", () => {
        const card = botao.closest(".produto-card");
        if (!card || !modalProduto) return;

        if (modalEmoji) modalEmoji.textContent = card.dataset.emoji;
        if (modalCategoria) modalCategoria.textContent = card.querySelector(".produto-categoria")?.textContent || "Produto";
        if (modalTitulo) modalTitulo.textContent = card.dataset.nome;
        if (modalDescricao) modalDescricao.textContent = card.dataset.descricao;
        if (modalPreco) modalPreco.textContent = Number(card.dataset.preco).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });

        modalProduto.classList.add("aberto");
        modalProduto.setAttribute("aria-hidden", "false");
        fecharModal?.focus();
    });
});

/* ================================================================
   AÇÃO 5 - FECHAR O MODAL PELO X, PELO FUNDO OU PELA TECLA ESC
   ================================================================ */
fecharModal?.addEventListener("click", fecharDetalhes);
modalProduto?.addEventListener("click", (event) => {
    if (event.target === modalProduto) fecharDetalhes();
});
document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") fecharDetalhes();
});

aplicarFiltros();
