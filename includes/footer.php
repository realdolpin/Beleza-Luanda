<?php
// Mapa mínimo dos produtos para o carrinho em JavaScript
$map = [];
foreach (products() as $p) {
    $map[$p['id']] = ['name' => $p['name'], 'tone' => $p['tone'], 'price' => (int)$p['price'], 'icon' => $p['icon'], 'img' => product_image($p['slug'])];
}
?>
<footer>
  <div class="foot-grid">
    <div><h4>Beleza Luanda</h4><p><?= e(SITE_SLOGAN) ?>.</p></div>
    <div><h4>Contactos</h4>
      <p>Luanda, Angola</p>
      <p>WhatsApp: <a href="<?= e(whatsapp_link('Olá! Vim pelo site.')) ?>">+<?= WHATSAPP_NUMBER ?></a></p>
      <p>Instagram: <?= e(INSTAGRAM) ?></p>
      <p>Horário: <?= e(HORARIO) ?></p></div>
    <div><h4>Ajuda</h4><p><a href="como-comprar.php">Como comprar</a></p><p><a href="sobre.php">Sobre nós</a></p></div>
  </div>
  <p class="copy">© <?= date('Y') ?> Beleza Luanda · Luanda, Angola</p>
</footer>

<div class="veil" id="veil"></div>
<aside class="drawer" id="drawer" aria-label="Carrinho">
  <div class="d-head"><h2>O teu carrinho</h2><button id="closeCart" aria-label="Fechar">✕</button></div>
  <div id="items" class="items"></div>
  <form id="form" class="d-foot">
    <div class="total"><span>Total</span><strong id="total">0 Kz</strong></div>
    <input name="name" placeholder="O teu nome" required autocomplete="name">
    <input name="phone" placeholder="Telefone" required inputmode="tel" autocomplete="tel">
    <input name="zone" placeholder="Bairro / zona de entrega" required>
    <button class="btn" type="submit">Enviar pedido no WhatsApp</button>
    <small>A localização exacta envias-nos no WhatsApp.</small>
  </form>
</aside>
<div class="toast" id="toast" role="status"></div>

<script>window.BL = { whatsapp: "<?= WHATSAPP_NUMBER ?>", products: <?= json_encode($map, JSON_UNESCAPED_UNICODE) ?> };</script>
<script src="assets/js/app.js"></script>
</body>
</html>
