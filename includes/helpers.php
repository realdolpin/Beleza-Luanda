<?php
require_once __DIR__ . '/db.php';

function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// 2500 -> "2.500 Kz"
function kz(int $v): string { return number_format($v, 0, ',', '.') . ' Kz'; }

function whatsapp_link(string $msg): string {
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($msg);
}

function categories(): array {
    return db()->query('SELECT * FROM categories ORDER BY sort')->fetchAll();
}

// Produtos activos, opcionalmente filtrados pelo slug da categoria
function products(?string $cat = null): array {
    $sql = 'SELECT p.*, c.slug AS cat_slug, c.name AS cat_name, c.icon
            FROM products p JOIN categories c ON c.id = p.category_id
            WHERE p.active = 1' . ($cat ? ' AND c.slug = ?' : '') . ' ORDER BY c.sort, p.id';
    $st = db()->prepare($sql);
    $st->execute($cat ? [$cat] : []);
    return $st->fetchAll();
}

// A foto de cada produto é procurada em assets/img/produtos/<slug>.(jpg|png|webp)
// Se ainda não existir, o cartão mostra um fundo suave com o ícone da categoria.
function product_image(string $slug): ?string {
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        $rel = "assets/img/produtos/$slug.$ext";
        if (is_file(__DIR__ . '/../' . $rel)) return $rel;
    }
    return null;
}

function render_card(array $p): void {
    $img   = product_image($p['slug']);
    $label = $p['cat_slug'] === 'kits' ? 'Inclui' : 'Cor/Tom';
    $msg   = "Olá! Quero comprar: {$p['name']} ({$p['tone']}) - " . kz((int)$p['price']);
    ?>
    <article class="card" data-id="<?= $p['id'] ?>" data-name="<?= e(mb_strtolower($p['name'] . ' ' . $p['brand'])) ?>" data-price="<?= $p['price'] ?>">
      <div class="thumb cat-<?= e($p['cat_slug']) ?>">
        <?php if ($img): ?><img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
        <?php else: ?><span class="ph"><?= $p['icon'] ?></span><?php endif; ?>
        <?php if ($p['badge']): ?><span class="tag"><?= e($p['badge']) ?></span><?php endif; ?>
      </div>
      <div class="info">
        <small><?= e($p['brand']) ?></small>
        <h3><?= e($p['name']) ?></h3>
        <p class="tone"><b><?= $label ?>:</b> <?= e($p['tone']) ?></p>
        <strong class="price"><?= kz((int)$p['price']) ?></strong>
        <div class="actions">
          <a class="wa" href="<?= e(whatsapp_link($msg)) ?>" target="_blank" rel="noopener">Comprar / WhatsApp</a>
          <button class="add" data-add="<?= $p['id'] ?>" aria-label="Adicionar ao carrinho">+</button>
        </div>
      </div>
    </article><?php
}
