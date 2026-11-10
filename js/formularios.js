"use strict";

/* ================================================================
   AÇÃO 1 - MOSTRAR OU ESCONDER A SENHA NO LOGIN
   ================================================================ */
const campoSenha = document.querySelector("#senha");
const mostrarSenha = document.querySelector("#mostrarSenha");

mostrarSenha?.addEventListener("click", () => {
    if (!campoSenha) return;
    const estaOculta = campoSenha.type === "password";
    campoSenha.type = estaOculta ? "text" : "password";
    mostrarSenha.textContent = estaOculta ? "Ocultar" : "Mostrar";
});

/* ================================================================
   AÇÃO 2 - RECUPERAR O E-MAIL LEMBRADO NO LOGIN
   ================================================================ */
const campoEmailLogin = document.querySelector("#email");
const lembrarEmail = document.querySelector("#lembrarEmail");
const emailSalvo = localStorage.getItem("email-padaria");

if (campoEmailLogin && emailSalvo) {
    campoEmailLogin.value = emailSalvo;
    if (lembrarEmail) lembrarEmail.checked = true;
}

/* ================================================================
   AÇÃO 3 - VALIDAR CAMPOS ANTES DO LOGIN REAL (SEM SIMULAR ACESSO)
   ================================================================ */
const formLogin = document.querySelector("#formLogin");
formLogin?.addEventListener("submit", (event) => {
    const email = campoEmailLogin?.value.trim() || "";
    const senha = campoSenha?.value || "";
    const erroEmail = document.querySelector("#erroEmail");
    const erroSenha = document.querySelector("#erroSenha");
    if (erroEmail) erroEmail.textContent = "";
    if (erroSenha) erroSenha.textContent = "";
    let valido = true;
    if (!email || !campoEmailLogin?.checkValidity()) {
        if (erroEmail) erroEmail.textContent = "Digite um e-mail válido.";
        valido = false;
    }
    if (senha.length < 10) {
        if (erroSenha) erroSenha.textContent = "A senha deve ter pelo menos 10 caracteres.";
        valido = false;
    }
    if (!valido) {
        event.preventDefault();
        window.mostrarToast?.("Revise os dados do acesso.", "erro");
        return;
    }
    if (lembrarEmail?.checked) localStorage.setItem("email-padaria", email);
    else localStorage.removeItem("email-padaria");
});

/* ================================================================
   AÇÃO 4 - CONTAR OS CARACTERES DA MENSAGEM DE CONTATO
   ================================================================ */
const mensagem = document.querySelector("#mensagem");
const contadorMensagem = document.querySelector("#contadorMensagem");

function atualizarContadorMensagem() {
    if (mensagem && contadorMensagem) contadorMensagem.textContent = String(mensagem.maxLength);
}
mensagem?.addEventListener("input", atualizarContadorMensagem);
atualizarContadorMensagem();

/* ================================================================
   AÇÃO 5 - ALTERAR A DICA DO FORMULÁRIO CONFORME O ASSUNTO
   ================================================================ */
const assunto = document.querySelector("#assunto");
const dicaAssunto = document.querySelector("#dicaAssunto");
const dicas = {
    duvida: "Conte como podemos ajudar.",
    encomenda: "Informe produto, quantidade e data desejada na mensagem.",
    sugestao: "Sua opinião ajuda a melhorar nosso atendimento.",
    outro: "Descreva o assunto com o máximo de clareza possível."
};

assunto?.addEventListener("change", () => {
    if (dicaAssunto) dicaAssunto.textContent = dicas[assunto.value] || dicas.duvida;
});

/* ================================================================
   AÇÃO 6 - VALIDAR E SIMULAR O ENVIO DO FORMULÁRIO DE CONTATO
   ================================================================ */
const formContato = document.querySelector("#formContato");

formContato?.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!formContato.checkValidity()) {
        formContato.reportValidity();
        window.mostrarToast?.("Preencha os campos obrigatórios.", "erro");
        return;
    }

    window.mostrarToast?.("Mensagem enviada com sucesso!");
    formContato.reset();
    atualizarContadorMensagem();
    if (dicaAssunto) dicaAssunto.textContent = dicas.duvida;
});
