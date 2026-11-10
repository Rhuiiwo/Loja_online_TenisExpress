"use strict";
(function(){
 const area=document.getElementById('linhas-pdv');const add=document.getElementById('adicionar-linha-pdv');const total=document.getElementById('subtotal-pdv');const desconto=document.getElementById('desconto-pdv');if(!area)return;
 function recalc(){let valor=0;area.querySelectorAll('.linha-pdv').forEach(l=>{const s=l.querySelector('.seletor-produto');const o=s?.selectedOptions[0];l.querySelector('.campo-local').value=o?.dataset.local||'';const q=l.querySelector('.campo-quantidade');q.step=o?.dataset.precisao==='0'?'1':'0.001';if(o?.dataset.preco){const cents=Math.round(Number(o.dataset.preco)*100);const mil=Math.round(Number(q.value||0)*1000);valor+=Math.round(cents*mil/1000)}});let d=Number((desconto?.value||'0').replace(',','.'));d=Number.isFinite(d)?Math.round(d*100):0;total.textContent=(Math.max(0,valor-d)/100).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});}
 function indexar(){area.querySelectorAll('.linha-pdv').forEach((l,i)=>{l.querySelector('.seletor-produto').name=`itens[${i}][produto]`;l.querySelector('.campo-local').name=`itens[${i}][local]`;l.querySelector('.campo-quantidade').name=`itens[${i}][quantidade]`});}
 add?.addEventListener('click',()=>{if(area.children.length>=40)return;const l=area.firstElementChild.cloneNode(true);l.querySelector('.seletor-produto').value='';l.querySelector('.campo-local').value='';l.querySelector('.campo-quantidade').value='1';area.appendChild(l);indexar();recalc();});
 area.addEventListener('click',e=>{if(e.target.closest('.remover-pdv')&&area.children.length>1){e.target.closest('.linha-pdv').remove();indexar();recalc();}});
 area.addEventListener('input',recalc);area.addEventListener('change',recalc);desconto?.addEventListener('input',recalc);recalc();
 const busca=document.getElementById('busca-codigo-pdv'),resultado=document.getElementById('resultado-busca-pdv');
 busca?.addEventListener('keydown',e=>{
   if(e.key!=='Enter')return;e.preventDefault();
   const codigo=busca.value.trim().toLowerCase();if(!codigo)return;
   const candidatos=Array.from(area.querySelector('.seletor-produto')?.options||[]);
   const achado=candidatos.find(o=>o.value && [o.dataset.barras,o.dataset.sku,o.dataset.nome].some(x=>x?.toLowerCase()===codigo));
   if(!achado){resultado.textContent='Produto não encontrado ou indisponível neste estoque.';return;}
   let linha=Array.from(area.children).find(l=>!l.querySelector('.seletor-produto').value);
   if(!linha){add?.click();linha=area.lastElementChild;}
   linha.querySelector('.seletor-produto').value=achado.value;
   // O código pode existir em vários locais: manter o local da opção encontrada.
   linha.querySelector('.campo-local').value=achado.dataset.local||'';
   linha.querySelector('.campo-quantidade').focus();resultado.textContent='Produto localizado: '+achado.dataset.nome;recalc();busca.value='';
 });
})();
