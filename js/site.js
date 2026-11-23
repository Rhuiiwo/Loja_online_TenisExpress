'use strict';

const CHAVE_CARRINHO_PULSE = 'pulse_carrinho_itens';
const VALOR_FRETE_PADRAO = 15.00;
const PERCENTUAL_DESCONTO = 0.05; // 5% de desconto sobre o subtotal

document.documentElement.dataset.theme =
    localStorage.getItem('pulse-theme') === 'dark' ? 'dark' : 'light';

function converterPrecoParaNumero(valorOuTexto) {
    if (typeof valorOuTexto === 'number') {
        return Number.isFinite(valorOuTexto) ? valorOuTexto : 0;
    }
    if (!valorOuTexto) {
        return 0;
    }
    const limpo = String(valorOuTexto)
        .replace(/[^\d,.-]/g, '')
        .replace(/\./g, '')
        .replace(',', '.');
    const numero = parseFloat(limpo);
    return Number.isFinite(numero) ? numero : 0;
}

function formatarBRL(valor) {
    return (Number.isFinite(valor) ? valor : 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function normalizarItemCarrinho(item) {
    return {
        nome: String(item.nome ?? item.name ?? 'Produto').trim(),
        categoria: String(item.categoria ?? item.category ?? '').trim(),
        preco: converterPrecoParaNumero(item.preco ?? item.price ?? 0),
        imagem: String(item.imagem ?? item.image ?? ''),
        quantidade: Math.max(0, parseInt(item.quantidade ?? item.quantity ?? 0, 10) || 0)
    };
}

function lerCarrinho() {
    try {
        const salvo = JSON.parse(localStorage.getItem(CHAVE_CARRINHO_PULSE) || '[]');
        if (!Array.isArray(salvo)) {
            return [];
        }
        return salvo.map(normalizarItemCarrinho).filter((item) => item.quantidade > 0);
    } catch (erro) {
        return [];
    }
}

function salvarCarrinho(itens) {
    const limpos = itens.map(normalizarItemCarrinho).filter((item) => item.quantidade > 0);
    localStorage.setItem(CHAVE_CARRINHO_PULSE, JSON.stringify(limpos));
}

function calcularResumoCarrinho(itens) {
    let totalItens = 0;
    let subtotal = 0;

    for (const item of itens) {
        totalItens += item.quantidade;
        subtotal += item.quantidade * item.preco;
    }

    const frete = totalItens > 0 ? VALOR_FRETE_PADRAO : 0;
    const desconto = totalItens > 0 ? subtotal * PERCENTUAL_DESCONTO : 0;
    const totalFinal = Math.max(0, subtotal + frete - desconto);

    return {
        totalItens,
        subtotal,
        frete,
        desconto,
        totalFinal
    };
}

function atualizarMostradorTopo(itens) {
    const { totalItens, subtotal } = calcularResumoCarrinho(itens);

    const elQtd = document.getElementById('qtd-itens-carrinho');
    const elTotal = document.getElementById('valor-total-carrinho');
    const elMenu = document.getElementById('link-menu-carrinho');
    const botaoCarrinho = document.querySelector('.cart-button');

    if (elQtd) {
        elQtd.textContent = String(totalItens);
    }
    if (elTotal) {
        elTotal.textContent = formatarBRL(subtotal);
    }
    if (elMenu) {
        elMenu.textContent = `Carrinho (${totalItens})`;
    }
    if (botaoCarrinho) {
        botaoCarrinho.textContent = totalItens ? `🛒 (${totalItens})` : '🛒';
        botaoCarrinho.setAttribute('aria-label', `Abrir carrinho com ${totalItens} itens`);
    }
}

function renderizarPaginaCarrinho(itens) {
    const listaEl = document.getElementById('lista-itens-carrinho');
    const resumoEl = document.getElementById('resumo-pedido-dinamico');
    const contadorTituloEl = document.getElementById('contador-titulo-carrinho');

    if (!listaEl || !resumoEl) {
        return;
    }

    const { totalItens, subtotal, frete, desconto, totalFinal } = calcularResumoCarrinho(itens);

    if (contadorTituloEl) {
        contadorTituloEl.textContent = `(${totalItens} ${totalItens === 1 ? 'produto' : 'produtos'})`;
    }

    if (itens.length === 0) {
        listaEl.innerHTML = `
            <li class="item-produto-destaque carrinho-vazio">
                <p>Seu carrinho está vazio no momento.</p>
                <a href="produtos.php" class="btn-primario">Explorar Produtos</a>
            </li>
        `;
    } else {
        listaEl.innerHTML = itens.map((item, index) => {
            const subtotalItem = item.preco * item.quantidade;
            return `
                <li class="item-produto-destaque" data-index="${index}">
                    <img src="${item.imagem}" alt="${item.nome}" class="item-imagem-pequena">
                    <div class="item-info-detalhe">
                        ${item.categoria ? `<p class="item-categoria-pequena">${item.categoria}</p>` : ''}
                        <h3 class="item-titulo-pequeno">${item.nome}</h3>
                        <small class="item-unitario">Unitário: ${formatarBRL(item.preco)}</small>
                    </div>
                    <div class="item-controles-qtd">
                        <button type="button" class="btn-qtd" data-acao="diminuir" data-index="${index}" aria-label="Diminuir quantidade de ${item.nome}">-</button>
                        <span class="item-qtd-valor">${item.quantidade}</span>
                        <button type="button" class="btn-qtd" data-acao="aumentar" data-index="${index}" aria-label="Aumentar quantidade de ${item.nome}">+</button>
                        <button type="button" class="btn-remover-item" data-acao="remover" data-index="${index}" aria-label="Remover ${item.nome}">✕</button>
                    </div>
                    <strong class="item-preco-pequeno">${formatarBRL(subtotalItem)}</strong>
                </li>
            `;
        }).join('');
    }

    resumoEl.innerHTML = `
        <h2>Resumo do Pedido</h2>
        <div class="resumo-linha">
            <span>${totalItens} ${totalItens === 1 ? 'produto' : 'produtos'}</span>
            <span>${formatarBRL(subtotal)}</span>
        </div>
        <div class="resumo-linha">
            <span>Preço original</span>
            <span>${formatarBRL(subtotal)}</span>
        </div>
        <div class="resumo-linha">
            <span>Entrega</span>
            <span>${formatarBRL(frete)}</span>
        </div>
        <div class="resumo-linha desconto">
            <span>Desconto (5%)</span>
            <span>- ${formatarBRL(desconto)}</span>
        </div>

        <div class="resumo-total">
            <span>Total</span>
            <span>${formatarBRL(totalFinal)}</span>
        </div>

        <a href="#" class="btn-finalizar" id="btn-finalizar-compra">
            <span>Finalizar Compra</span>
            <span>→</span>
        </a>
        <a href="produtos.php" class="btn-finalizar btn-continuar">
            <span>Continuar Comprando</span>
            <span>←</span>
        </a>
    `;
}

function sincronizarEstadoCarrinho(carrinho) {
    salvarCarrinho(carrinho);
    atualizarMostradorTopo(carrinho);
    renderizarPaginaCarrinho(carrinho);
}

function iniciarCarrinhoProdutos() {
    const botoes = document.querySelectorAll('.btn-add-carrinho, .produto-card .btn-primario, .add-cart');

    botoes.forEach(function (botao) {
        botao.addEventListener('click', function () {
            const card = botao.closest('.produto-card, .product-card');
            if (!card) {
                return;
            }

            const tituloEl = card.querySelector('.produto-titulo, h4, h3');
            const precoEl = card.querySelector('.produto-preco, .price');
            const categoriaEl = card.querySelector('.produto-categoria');
            const imgEl = card.querySelector('.produto-imagem img, img');

            if (!tituloEl || !precoEl) {
                return;
            }

            const nome = tituloEl.textContent.trim();
            const preco = converterPrecoParaNumero(precoEl.textContent);
            const categoria = categoriaEl ? categoriaEl.textContent.trim() : '';
            const imagem = imgEl ? (imgEl.getAttribute('src') || '') : '';

            const carrinho = lerCarrinho();
            const indice = carrinho.findIndex((item) => item.nome === nome);

            if (indice >= 0) {
                carrinho[indice].preco = preco;
                carrinho[indice].categoria = categoria || carrinho[indice].categoria;
                carrinho[indice].imagem = imagem || carrinho[indice].imagem;
                carrinho[indice].quantidade += 1;
            } else {
                carrinho.push({
                    nome,
                    categoria,
                    preco,
                    imagem,
                    quantidade: 1
                });
            }

            sincronizarEstadoCarrinho(carrinho);

            const textoOriginal = botao.textContent;
            botao.textContent = 'Adicionado ✓';
            botao.disabled = true;

            window.setTimeout(function () {
                botao.textContent = textoOriginal;
                botao.disabled = false;
            }, 800);
        });
    });
}

function iniciarEventosPaginaCarrinho() {
    const listaEl = document.getElementById('lista-itens-carrinho');
    if (!listaEl) {
        return;
    }

    listaEl.addEventListener('click', function (evento) {
        const botao = evento.target.closest('button[data-acao]');
        if (!botao) {
            return;
        }

        const indice = parseInt(botao.dataset.index, 10);
        const acao = botao.dataset.acao;
        const carrinho = lerCarrinho();

        if (!carrinho[indice]) {
            return;
        }

        if (acao === 'aumentar') {
            carrinho[indice].quantidade += 1;
        } else if (acao === 'diminuir') {
            carrinho[indice].quantidade -= 1;
        } else if (acao === 'remover') {
            carrinho[indice].quantidade = 0;
        }

        sincronizarEstadoCarrinho(carrinho);
    });
}

function configurarTema() {
    const botaoTema = document.getElementById('theme-toggle');
    if (!botaoTema) {
        return;
    }

    function aplicarTema(tema) {
        const escuro = tema === 'dark';
        document.documentElement.dataset.theme = escuro ? 'dark' : 'light';
        botaoTema.setAttribute('aria-pressed', String(escuro));
        botaoTema.setAttribute('aria-label', escuro ? 'Ativar tema claro' : 'Ativar tema escuro');
        botaoTema.textContent = escuro ? '☀️' : '🌙';
        localStorage.setItem('pcstore-theme', tema);
    }

    aplicarTema(localStorage.getItem('pcstore-theme') === 'dark' ? 'dark' : 'light');
    botaoTema.addEventListener('click', function () {
        aplicarTema(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const carrinhoInicial = lerCarrinho();
    configurarTema();
    atualizarMostradorTopo(carrinhoInicial);
    renderizarPaginaCarrinho(carrinhoInicial);
    iniciarCarrinhoProdutos();
    iniciarEventosPaginaCarrinho();
});