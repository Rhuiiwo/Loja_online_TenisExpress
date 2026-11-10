"use strict";

/* ================================================================
   AÇÃO 1 - RECUPERAR O CARRINHO SALVO NO NAVEGADOR
   ================================================================ */
let carrinho = [];
try {
    carrinho = JSON.parse(localStorage.getItem("carrinho-padaria")) || [];
} catch (erro) {
    carrinho = [];
    localStorage.removeItem("carrinho-padaria");
}

/* ================================================================
   AÇÃO 2 - PREPARAR A FORMATAÇÃO DOS VALORES EM REAL (BRL)
   ================================================================ */
const moeda = new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" });
function formatarMoeda(valor) { return moeda.format(valor); }

/* ================================================================
   AÇÃO 3 - SELECIONAR OS ELEMENTOS VISUAIS DO CARRINHO
   ================================================================ */
const painelCarrinho = document.querySelector("#carrinhoPainel");
const overlayCarrinho = document.querySelector("#carrinhoOverlay");
const botaoAbrirCarrinho = document.querySelector("#abrirCarrinho");
const botaoFecharCarrinho = document.querySelector("#fecharCarrinho");
const listaCarrinho = document.querySelector("#carrinhoLista");
const contadorCarrinho = document.querySelector("#contadorCarrinho");
const totalCarrinho = document.querySelector("#totalCarrinho");
const finalizarPedido = document.querySelector("#finalizarPedido");
const caminhoImportacaoCarrinho = new URL("../cliente/receber.php", document.currentScript.src).href;
const limparCarrinho = document.querySelector("#limparCarrinho");

/* ================================================================
   AÇÃO 4 - ABRIR E FECHAR O PAINEL LATERAL
   ================================================================ */
function abrirCarrinho() {
    painelCarrinho?.classList.add("aberto");
    overlayCarrinho?.classList.add("aberto");
    painelCarrinho?.setAttribute("aria-hidden", "false");
    overlayCarrinho?.setAttribute("aria-hidden", "false");
    botaoAbrirCarrinho?.setAttribute("aria-expanded", "true");
    document.body.classList.add("sem-scroll");
    botaoFecharCarrinho?.focus();
}

function fecharCarrinho() {
    painelCarrinho?.classList.remove("aberto");
    overlayCarrinho?.classList.remove("aberto");
    painelCarrinho?.setAttribute("aria-hidden", "true");
    overlayCarrinho?.setAttribute("aria-hidden", "true");
    botaoAbrirCarrinho?.setAttribute("aria-expanded", "false");
    document.body.classList.remove("sem-scroll");
}

botaoAbrirCarrinho?.addEventListener("click", abrirCarrinho);
botaoFecharCarrinho?.addEventListener("click", fecharCarrinho);
overlayCarrinho?.addEventListener("click", fecharCarrinho);
document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && painelCarrinho?.classList.contains("aberto")) fecharCarrinho();
});

/* ================================================================
   AÇÃO 5 - SALVAR O ESTADO DO CARRINHO NO localStorage
   ================================================================ */
function salvarCarrinho() {
    localStorage.setItem("carrinho-padaria", JSON.stringify(carrinho));
}

/* ================================================================
   AÇÃO 6 - DESENHAR OS ITENS DO CARRINHO NA TELA
   ================================================================ */
function renderizarCarrinho() {
    if (!listaCarrinho) return;

    if (carrinho.length === 0) {
        listaCarrinho.innerHTML = '<div class="carrinho-vazio"><span>🧺</span><strong>Seu carrinho está vazio.</strong><p>Adicione produtos para montar seu pedido.</p></div>';
    } else {
        listaCarrinho.innerHTML = carrinho.map((item) => `
            <div class="carrinho-item" data-id="${item.id}">
                <div class="carrinho-item-emoji">${item.emoji || "🥖"}</div>
                <div>
                    <strong>${item.nome}</strong>
                    <p>${formatarMoeda(item.preco)} cada</p>
                    <div class="carrinho-controles">
                        <button data-acao="menos" type="button" aria-label="Diminuir quantidade">−</button>
                        <span>${item.quantidade}</span>
                        <button data-acao="mais" type="button" aria-label="Aumentar quantidade">+</button>
                        <button class="remover-item" data-acao="remover" type="button">Remover</button>
                    </div>
                </div>
                <strong class="carrinho-subtotal">${formatarMoeda(item.preco * item.quantidade)}</strong>
            </div>
        `).join("");
    }

    atualizarResumo();
}

/* ================================================================
   AÇÃO 7 - CALCULAR QUANTIDADE TOTAL E VALOR TOTAL
   ================================================================ */
function atualizarResumo() {
    const quantidadeTotal = carrinho.reduce((soma, item) => soma + item.quantidade, 0);
    const valorTotal = carrinho.reduce((soma, item) => soma + item.preco * item.quantidade, 0);

    if (contadorCarrinho) contadorCarrinho.textContent = String(quantidadeTotal);
    if (totalCarrinho) totalCarrinho.textContent = formatarMoeda(valorTotal);
    if (limparCarrinho) limparCarrinho.disabled = carrinho.length === 0;
    if (finalizarPedido) finalizarPedido.disabled = carrinho.length === 0;
}

/* ================================================================
   AÇÃO 8 - CENTRALIZAR TODA ATUALIZAÇÃO DO CARRINHO
   ================================================================ */
function atualizarCarrinho() {
    renderizarCarrinho();
    salvarCarrinho();
}

/* ================================================================
   AÇÃO 9 - ADICIONAR UM PRODUTO SEM DUPLICAR A LINHA
   ================================================================ */
function adicionarProduto(produto) {
    const existente = carrinho.find((item) => item.id === produto.id);

    if (existente) existente.quantidade += 1;
    else carrinho.push({ ...produto, quantidade: 1 });

    atualizarCarrinho();
}

/* ================================================================
   AÇÃO 10 - LER O CARD QUANDO O USUÁRIO CLICA EM ADICIONAR
   ================================================================ */
document.querySelectorAll(".btn-adicionar").forEach((botao) => {
    botao.addEventListener("click", () => {
        const card = botao.closest(".produto-card");
        if (!card) return;

        adicionarProduto({
            id: card.dataset.id,
            nome: card.dataset.nome,
            preco: Number(card.dataset.preco),
            emoji: card.dataset.emoji
        });

        window.mostrarToast?.(`${card.dataset.nome} adicionado ao carrinho.`);
        abrirCarrinho();
    });
});

/* ================================================================
   AÇÃO 11 - AUMENTAR, DIMINUIR OU REMOVER ITENS
   ================================================================ */
listaCarrinho?.addEventListener("click", (event) => {
    const botao = event.target.closest("button[data-acao]");
    if (!botao) return;

    const itemElemento = botao.closest(".carrinho-item");
    const id = itemElemento?.dataset.id;
    const item = carrinho.find((produto) => produto.id === id);
    if (!item) return;

    if (botao.dataset.acao === "mais") item.quantidade += 1;
    if (botao.dataset.acao === "menos") item.quantidade -= 1;

    if (botao.dataset.acao === "remover" || item.quantidade <= 0) {
        carrinho = carrinho.filter((produto) => produto.id !== id);
    }

    atualizarCarrinho();
});

/* ================================================================
   AÇÃO 12 - ESVAZIAR TODO O CARRINHO
   ================================================================ */
limparCarrinho?.addEventListener("click", () => {
    if (carrinho.length === 0) return;
    if (!confirm("Deseja realmente esvaziar o carrinho?")) return;

    carrinho = [];
    atualizarCarrinho();
    window.mostrarToast?.("Carrinho esvaziado.");
});

/* ================================================================
   AÇÃO 13 - INICIAR COMPRA REAL VIA PHP / MYSQL
   ================================================================ */
finalizarPedido?.addEventListener("click", () => {
    if (carrinho.length === 0) {
        window.mostrarToast?.("Adicione pelo menos um produto.", "erro");
        return;
    }
    salvarCarrinho();
    window.location.assign(caminhoImportacaoCarrinho);
});

/* ================================================================
   AÇÃO 14 - RESTAURAR O CARRINHO AO ABRIR/ATUALIZAR A PÁGINA
   ================================================================ */
renderizarCarrinho();
