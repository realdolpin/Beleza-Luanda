/* Beleza Luanda · comportamento do site
   Depende de window.BL (número de WhatsApp e mapa de produtos), gerado em includes/footer.php */
(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const { whatsapp, products } = window.BL;
  const kz = v => v.toLocaleString('pt-PT').replace(/\s|\u00a0/g, '.') + ' Kz';
  const esc = s => String(s).replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  /* ---------- Carrinho (guardado no navegador) ---------- */
  let cart = {};
  try { cart = JSON.parse(localStorage.getItem('bl_cart') || '{}'); } catch (e) {}
  const persist = () => { try { localStorage.setItem('bl_cart', JSON.stringify(cart)); } catch (e) {} };

  function toast(msg) {
    const t = $('#toast');
    t.textContent = msg; t.classList.add('on');
    clearTimeout(t._t); t._t = setTimeout(() => t.classList.remove('on'), 2400);
  }

  function renderCart() {
    const ids = Object.keys(cart).filter(id => products[id]);
    const qty = ids.reduce((s, id) => s + cart[id], 0);
    const total = ids.reduce((s, id) => s + cart[id] * products[id].price, 0);
    $('#count').textContent = qty;
    $('#total').textContent = kz(total);
    $('#form').style.display = ids.length ? '' : 'none';
    $('#items').innerHTML = ids.length ? ids.map(id => {
      const p = products[id];
      const pic = p.img ? `<img src="${esc(p.img)}" alt="">` : p.icon;
      return `<div class="line"><div class="mini">${pic}</div>
        <div><b>${esc(p.name)}</b><span>${kz(p.price)}</span></div>
        <div class="qty"><button data-less="${id}" aria-label="Menos">−</button><span>${cart[id]}</span><button data-more="${id}" aria-label="Mais">+</button></div></div>`;
    }).join('') : '<div class="empty-cart">🛍️<br>O carrinho está vazio.</div>';
    persist();
  }

  const drawer = o => { $('#drawer').classList.toggle('on', o); $('#veil').classList.toggle('on', o); };
  $('#openCart').onclick = () => drawer(true);
  $('#closeCart').onclick = $('#veil').onclick = () => drawer(false);
  document.addEventListener('keydown', e => e.key === 'Escape' && drawer(false));

  document.addEventListener('click', e => {
    const add = e.target.closest('[data-add]');
    if (add) {
      const id = add.dataset.add;
      cart[id] = (cart[id] || 0) + 1;
      renderCart();
      const b = $('#openCart'); b.classList.remove('bump'); void b.offsetWidth; b.classList.add('bump');
      toast(products[id].name + ' adicionado 💗');
    }
    const less = e.target.dataset.less, more = e.target.dataset.more;
    if (less) { cart[less]--; if (cart[less] <= 0) delete cart[less]; renderCart(); }
    if (more) { cart[more] = Math.min(99, cart[more] + 1); renderCart(); }
  });

  /* ---------- Enviar pedido: grava na BD e abre o WhatsApp ---------- */
  $('#form').addEventListener('submit', async e => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type=submit]');
    const data = Object.fromEntries(new FormData(e.target));
    btn.disabled = true; btn.textContent = 'A enviar…';
    try {
      const res = await fetch('api/pedido.php', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...data, cart })
      });
      const out = await res.json();
      if (!out.ok) throw new Error(out.error);

      // Mensagem pré-preenchida com o resumo do pedido
      const linhas = Object.keys(cart).filter(id => products[id])
        .map(id => `• ${cart[id]}x ${products[id].name} (${products[id].tone})`).join('\n');
      const msg = `Olá! Pedido ${out.code}\n${linhas}\nTotal: ${kz(out.total)}\nNome: ${data.name}\nZona: ${data.zone}\nTelefone: ${data.phone}`;
      cart = {}; renderCart(); e.target.reset(); drawer(false);
      window.location.href = `https://wa.me/${whatsapp}?text=${encodeURIComponent(msg)}`;
    } catch (err) {
      toast(err.message || 'Não foi possível enviar. Tenta de novo.');
    }
    btn.disabled = false; btn.textContent = 'Enviar pedido no WhatsApp';
  });

  /* ---------- Menu no telemóvel ---------- */
  $('#burger').onclick = e => {
    const open = $('#nav').classList.toggle('open');
    e.currentTarget.setAttribute('aria-expanded', open);
  };

  /* ---------- Pesquisa e ordenação (só na página da loja) ---------- */
  const grid = $('#grid'), q = $('#q'), sort = $('#sort');
  if (grid && q) {
    const cards = $$('.card', grid);
    q.addEventListener('input', () => {
      const t = q.value.trim().toLowerCase();
      let visible = 0;
      cards.forEach(c => { const ok = c.dataset.name.includes(t); c.hidden = !ok; if (ok) visible++; });
      $('#empty').hidden = visible > 0;
    });
    const keys = { 1: c => +c.dataset.price, 2: c => -c.dataset.price, 3: c => c.dataset.name, 0: c => +c.dataset.id };
    sort.addEventListener('change', () => {
      const k = keys[sort.value];
      cards.sort((a, b) => (k(a) > k(b)) - (k(a) < k(b))).forEach(c => grid.appendChild(c));
    });
  }

  /* ---------- Animação de entrada dos cartões ---------- */
  const io = new IntersectionObserver(list => list.forEach(x => {
    if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); }
  }), { threshold: .08 });
  $$('.card').forEach((c, i) => { c.classList.add('reveal'); c.style.transitionDelay = (i % 6) * 60 + 'ms'; io.observe(c); });

  renderCart();
})();
