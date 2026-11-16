<?php
$title = 'Início'; $active = 'home';
require __DIR__ . '/includes/header.php';
$destaques = array_slice(array_filter(products(), fn($p) => $p['badge'] !== '' && $p['cat_slug'] !== 'kits'), 0, 4);
$kits = products('kits');
?>
<section class="hero">
  <div>
    <p class="pill">Maquilhagem original · Luanda</p>
    <h1>Bem-vinda à<br><em>Beleza Luanda!</em></h1>
    <p class="lead">Aqui encontras tudo para a tua maquilhagem, com preços acessíveis e entrega rápida em Luanda. Encomenda hoje pelo WhatsApp!</p>
    <div class="cta">
      <a class="btn" href="loja.php">Ver Produtos</a>
      <a class="btn ghost" href="<?= e(whatsapp_link('Olá! Vim pelo site e gostava de fazer uma encomenda.')) ?>" target="_blank" rel="noopener">Falar no WhatsApp</a>
    </div>
  </div>
  <div class="hero-art" aria-hidden="true">
    <span class="orb o1">💄</span><span class="orb o2">💅</span><span class="orb o3">🌷</span><span class="orb o4">✨</span><span class="orb o5">🪞</span>
  </div>
</section>

<section class="perks">
  <div>🚚 Entrega em 24h a 48h</div><div>✅ Produtos originais</div><div>💳 TPA · Multicaixa Express · Dinheiro</div>
</section>

<section class="wrap">
  <h2 class="sec">Explore por categoria</h2>
  <div class="tiles">
    <?php foreach ($cats as $c): ?>
      <a class="tile cat-<?= e($c['slug']) ?>" href="loja.php?cat=<?= e($c['slug']) ?>">
        <span><?= $c['icon'] ?></span><b><?= e($c['name']) ?></b><small>Ver tudo →</small>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="wrap">
  <h2 class="sec">As mais pedidas</h2>
  <div class="grid"><?php foreach ($destaques as $p) render_card($p); ?></div>
</section>

<section class="wrap promo">
  <div class="promo-head"><p class="pill">Promoção</p><h2 class="sec">Kits que poupam dinheiro</h2></div>
  <div class="grid"><?php foreach ($kits as $p) render_card($p); ?></div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
