"use strict";
(function () {
  const destino = document.getElementById('itens-importados');
  const lista = document.getElementById('lista-importacao');
  const botao = document.getElementById('confirmar-importacao');
  if (!destino || !lista || !botao) return;
  let itens = [];
  try {
    const l = JSON.parse(localStorage.getItem('carrinho-padaria') || '[]');
    if (Array.isArray(l)) itens = l.filter(x => x && typeof x.id === 'string' && Number.isInteger(x.quantidade) && x.quantidade > 0).map(x => ({ id: x.id, quantidade: x.quantidade }));
  } catch (e) { }
  if (!itens.length) { lista.textContent = 'Não há itens salvos no carrinho público.'; return; }
  destino.value = JSON.stringify(itens);
  lista.textContent = `${itens.length} produto(s) aguardando importação. O PHP verificará cadastro e quantidades.`;
  botao.disabled = false;
})();
