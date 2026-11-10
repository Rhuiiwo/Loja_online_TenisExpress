"use strict";

/* ================================================================
   AÇÃO 1 - CONFIRMAR QUE O JAVASCRIPT FOI CARREGADO
   ================================================================ */
console.log("Pão & Saber: navegação v1.3.11 carregada.");

/* ================================================================
   AÇÃO 2 - MOSTRAR MENSAGENS FLUTUANTES (TOAST)
   ================================================================ */
window.mostrarToast = function (mensagem, tipo = "sucesso") {
    const toast = document.querySelector("#toast");
    if (!toast) return;

    toast.textContent = mensagem;
    toast.classList.toggle("erro", tipo === "erro");
    toast.classList.add("visivel");

    clearTimeout(window.__timerToast);
    window.__timerToast = setTimeout(() => {
        toast.classList.remove("visivel");
    }, 3000);
};

/* ================================================================
   AÇÃO 3 - ABRIR E FECHAR O MENU EM TELAS PEQUENAS
   ================================================================ */
const menuToggle = document.querySelector("#menuToggle");
const menuPrincipal = document.querySelector("#menuPrincipal");

if (menuToggle && menuPrincipal) {
    menuToggle.addEventListener("click", () => {
        menuPrincipal.classList.toggle("aberto");
        const aberto = menuPrincipal.classList.contains("aberto");
        menuToggle.setAttribute("aria-expanded", String(aberto));
    });
}

/* ================================================================
   AÇÃO 6 - ALTERNAR TEMA CLARO/ESCURO E SALVAR A PREFERÊNCIA
   ================================================================ */
const botaoTema = document.querySelector("#alternarTema");
const iconeTema = document.querySelector("#iconeTema");
const temaSalvo = localStorage.getItem("tema-padaria");

function atualizarIconeTema() {
    const escuro = document.body.classList.contains("tema-escuro");
    if (iconeTema) iconeTema.textContent = escuro ? "☀️" : "🌙";
    botaoTema?.setAttribute("aria-label", escuro ? "Ativar tema claro" : "Ativar tema escuro");
}

if (temaSalvo === "escuro") {
    document.body.classList.add("tema-escuro");
}
atualizarIconeTema();

botaoTema?.addEventListener("click", () => {
    document.body.classList.toggle("tema-escuro");
    const escuro = document.body.classList.contains("tema-escuro");
    localStorage.setItem("tema-padaria", escuro ? "escuro" : "claro");
    atualizarIconeTema();
});

/* ================================================================
   AÇÃO 7 - BOTÃO VOLTAR AO TOPO + CABEÇALHO DURANTE A ROLAGEM
   ================================================================ */
const voltarTopo = document.querySelector("#voltarTopo");
const cabecalho = document.querySelector(".cabecalho");

window.addEventListener("scroll", () => {
    voltarTopo?.classList.toggle("visivel", window.scrollY > 320);
    cabecalho?.classList.toggle("rolando", window.scrollY > 40);
});

voltarTopo?.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});

/* ================================================================
   AÇÃO 8 - ANIMAR ELEMENTOS QUANDO ENTRAM NA ÁREA VISÍVEL
   ================================================================ */
const elementosRevelar = document.querySelectorAll(".revelar");

if ("IntersectionObserver" in window) {
    const observador = new IntersectionObserver((entradas, observer) => {
        entradas.forEach((entrada) => {
            if (entrada.isIntersecting) {
                entrada.target.classList.add("visivel");
                observer.unobserve(entrada.target);
            }
        });
    }, { threshold: 0.12 });

    elementosRevelar.forEach((elemento) => observador.observe(elemento));
} else {
    elementosRevelar.forEach((elemento) => elemento.classList.add("visivel"));
}

/* ================================================================
   AÇÃO 9 - SAUDAÇÃO DA HOME DE ACORDO COM O HORÁRIO
   ================================================================ */
const saudacao = document.querySelector("#saudacao");
if (saudacao) {
    const hora = new Date().getHours();
    if (hora < 12) saudacao.textContent = "Bom dia! Fornadas fresquinhas";
    else if (hora < 18) saudacao.textContent = "Boa tarde! Hora de um café";
    else saudacao.textContent = "Boa noite! Um sabor para encerrar o dia";
}

/* ================================================================
   AÇÃO 10 - DATA E HORA EM TEMPO REAL NO DASHBOARD
   ================================================================ */
const dataDashboard = document.querySelector("#dataDashboard");
const horaDashboard = document.querySelector("#horaDashboard");

function atualizarRelogioDashboard() {
    if (!dataDashboard || !horaDashboard) return;
    const agora = new Date();
    dataDashboard.textContent = agora.toLocaleDateString("pt-BR", {
        weekday: "long", day: "2-digit", month: "long", year: "numeric"
    });
    horaDashboard.textContent = agora.toLocaleTimeString("pt-BR");
}

if (dataDashboard && horaDashboard) {
    atualizarRelogioDashboard();
    setInterval(atualizarRelogioDashboard, 1000);
}

/* ================================================================
   AÇÃO 11 - ANIMAR OS INDICADORES NUMÉRICOS DO DASHBOARD
   ================================================================ */
function animarNumero(elemento, destino, prefixo = "") {
    const duracao = 700;
    const inicio = performance.now();

    function passo(agora) {
        const progresso = Math.min((agora - inicio) / duracao, 1);
        const atual = Math.round(destino * progresso);
        elemento.textContent = prefixo + atual.toLocaleString("pt-BR");
        if (progresso < 1) requestAnimationFrame(passo);
    }
    requestAnimationFrame(passo);
}

document.querySelectorAll("[data-contador]").forEach((elemento) => {
    animarNumero(elemento, Number(elemento.dataset.contador));
});

document.querySelectorAll("[data-moeda]").forEach((elemento) => {
    const valor = Number(elemento.dataset.moeda);
    const duracao = 700;
    const inicio = performance.now();
    function passo(agora) {
        const progresso = Math.min((agora - inicio) / duracao, 1);
        const atual = valor * progresso;
        elemento.textContent = atual.toLocaleString("pt-BR", { style: "currency", currency: "BRL", maximumFractionDigits: 0 });
        if (progresso < 1) requestAnimationFrame(passo);
    }
    requestAnimationFrame(passo);
});

/* ================================================================
   AÇÃO 12 - PESQUISAR E FILTRAR PEDIDOS NO DASHBOARD
   ================================================================ */
const buscarPedido = document.querySelector("#buscarPedido");
const filtroStatus = document.querySelector("#filtroStatus");
const linhasPedidos = [...document.querySelectorAll("#tabelaPedidos tbody tr")];
const pedidosVazios = document.querySelector("#pedidosVazios");

function filtrarPedidos() {
    if (!linhasPedidos.length) return;
    const termo = (buscarPedido?.value || "").toLowerCase().trim();
    const status = filtroStatus?.value || "todos";
    let visiveis = 0;

    linhasPedidos.forEach((linha) => {
        const combinaTexto = linha.textContent.toLowerCase().includes(termo);
        const combinaStatus = status === "todos" || linha.dataset.status === status;
        const mostrar = combinaTexto && combinaStatus;
        linha.hidden = !mostrar;
        if (mostrar) visiveis += 1;
    });

    if (pedidosVazios) pedidosVazios.hidden = visiveis !== 0;
}

buscarPedido?.addEventListener("input", filtrarPedidos);
filtroStatus?.addEventListener("change", filtrarPedidos);


/* ================================================================
   MENU ÚNICO POR PERFIL — origem: app/navegacao.php via sessao.php
   ================================================================ */
const caminhoStatusSessao = new URL("../sessao.php", document.currentScript.src).href;

function criarItemMenu(item, csrf) {
    const li = document.createElement("li");
    if (item.acao === "sair") {
        const form = document.createElement("form");
        form.method = "post";
        // sessao.php e sair.php estão no MESMO diretório /public/.
        form.action = new URL("sair.php", caminhoStatusSessao).href;
        form.className = "form-sair";
        form.setAttribute("data-form-saida", "");
        const token = document.createElement("input");
        token.type = "hidden"; token.name = "csrf"; token.value = csrf;
        const button = document.createElement("button");
        button.type = "button"; button.className = "sair-menu-botao";
        button.setAttribute("data-abrir-modal-saida", "");
        button.textContent = "Sair";
        form.append(token, button);
        li.append(form);
        return li;
    }
    if (Array.isArray(item.submenu) && item.submenu.length) {
        li.className = "tem-submenu";
        const grupo = document.createElement("div");
        grupo.className = "menu-item-com-submenu";
        const ancora = document.createElement("a");
        ancora.href = item.url; ancora.textContent = item.nome;
        const toggle = document.createElement("button");
        toggle.type = "button"; toggle.className = "submenu-toggle";
        toggle.textContent = "▾";
        toggle.setAttribute("aria-label", "Abrir submenu " + item.nome);
        toggle.setAttribute("aria-expanded", "false");
        grupo.append(ancora, toggle);
        const submenu = document.createElement("ul");
        submenu.className = "submenu";
        for (const sub of item.submenu) {
            const subli = document.createElement("li");
            const suba = document.createElement("a");
            suba.href = sub.url; suba.textContent = sub.nome;
            subli.append(suba); submenu.append(subli);
        }
        li.append(grupo, submenu);
        return li;
    }
    const link = document.createElement("a");
    link.href = item.url;
    link.textContent = item.nome;
    li.append(link);
    return li;
}

async function atualizarNavegacaoSessao() {
    const menu = document.querySelector("#menuPrincipal .menu");
    if (!menu || menu.hasAttribute("data-menu-servidor")) return;
    try {
        const resposta = await fetch(caminhoStatusSessao, {
            method: "GET", credentials: "same-origin", cache: "no-store",
            headers: { "Accept": "application/json" }
        });
        if (!resposta.ok) throw new Error("Falha ao consultar a sessão");
        const estado = await resposta.json();
        if (!Array.isArray(estado.menu)) throw new Error("Menu não disponível");
        menu.replaceChildren(...estado.menu.map(item => criarItemMenu(item, estado.csrf || "")));
        const botaoCarrinho = document.querySelector("#abrirCarrinho");
        if (botaoCarrinho) botaoCarrinho.hidden = estado.carrinho !== true;
    } catch (_) {
        const verificar = document.createElement("a");
        verificar.textContent = "Verificar sessão";
        verificar.href = new URL("painel.php", caminhoStatusSessao).href;
        const li = document.createElement("li");
        li.append(verificar);
        const inicio = criarItemMenu({ nome: "Início", url: new URL("index.html", caminhoStatusSessao).href });
        menu.replaceChildren(inicio, li);
    }
}

document.addEventListener("click", (evento) => {
    const toggle = evento.target.closest("#menuPrincipal .submenu-toggle");
    if (toggle) {
        const item = toggle.closest(".tem-submenu");
        if (item) {
            item.classList.toggle("submenu-aberto");
            toggle.setAttribute("aria-expanded", String(item.classList.contains("submenu-aberto")));
        }
    }
    const link = evento.target.closest("#menuPrincipal a");
    if (link && window.innerWidth <= 700 && menuPrincipal) {
        menuPrincipal.classList.remove("aberto");
        menuToggle?.setAttribute("aria-expanded", "false");
    }
});

/* ================================================================
   CONFIRMAÇÃO DE SAÍDA — JANELA MODAL SOBRE A PÁGINA
   ================================================================ */
function criarModalSaida() {
    let modal = document.getElementById("modalConfirmarSaida");
    if (modal) return modal;
    modal = document.createElement("dialog");
    modal.id = "modalConfirmarSaida";
    modal.className = "modal-saida";
    modal.setAttribute("aria-labelledby", "tituloModalSaida");
    modal.setAttribute("aria-describedby", "textoModalSaida");
    modal.innerHTML = `
        <div class="modal-saida-icone" aria-hidden="true">↪</div>
        <h2 id="tituloModalSaida">Deseja sair da sua conta?</h2>
        <p id="textoModalSaida">Você precisará entrar novamente para acessar as áreas da sua conta.</p>
        <div class="modal-saida-acoes">
            <button class="btn btn-secundario" type="button" data-cancelar-saida>Continuar no sistema</button>
            <button class="btn modal-saida-confirmar" type="button" data-aceitar-saida>Sim, sair</button>
        </div>`;
    document.body.append(modal);
    return modal;
}

let formularioSaidaPendente = null;
let focoAntesDaSaida = null;
function abrirModalSaida(formulario) {
    const modal = criarModalSaida();
    formularioSaidaPendente = formulario;
    focoAntesDaSaida = document.activeElement;
    if (typeof modal.showModal === "function") {
        if (!modal.open) modal.showModal();
    } else {
        window.location.assign(formulario.action);
        return;
    }
    modal.querySelector("[data-cancelar-saida]")?.focus();
}

document.addEventListener("click", (evento) => {
    const botao = evento.target.closest?.("[data-abrir-modal-saida]");
    if (!botao) return;
    evento.preventDefault();
    const formulario = botao.closest("form[data-form-saida]");
    if (formulario) abrirModalSaida(formulario);
});

document.addEventListener("click", (evento) => {
    const modal = document.getElementById("modalConfirmarSaida");
    if (!modal?.open) return;
    if (evento.target.closest("[data-cancelar-saida]")) {
        modal.close();
        return;
    }
    if (evento.target.closest("[data-aceitar-saida]")) {
        if (!formularioSaidaPendente) return;
        const campoConfirmacao = document.createElement("input");
        campoConfirmacao.type = "hidden";
        campoConfirmacao.name = "confirmado";
        campoConfirmacao.value = "sim";
        formularioSaidaPendente.append(campoConfirmacao);
        HTMLFormElement.prototype.submit.call(formularioSaidaPendente);
    }
});

document.addEventListener("click", (evento) => {
    const modal = document.getElementById("modalConfirmarSaida");
    if (!modal?.open || evento.target !== modal) return;
    const limites = modal.getBoundingClientRect();
    if (evento.clientX < limites.left || evento.clientX > limites.right ||
        evento.clientY < limites.top || evento.clientY > limites.bottom) modal.close();
});
document.addEventListener("close", (evento) => {
    if (evento.target.id !== "modalConfirmarSaida") return;
    formularioSaidaPendente = null;
    if (focoAntesDaSaida?.isConnected) focoAntesDaSaida.focus();
    focoAntesDaSaida = null;
}, true);


atualizarNavegacaoSessao();
window.addEventListener("pageshow", () => atualizarNavegacaoSessao());
document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") atualizarNavegacaoSessao();
});
