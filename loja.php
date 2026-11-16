<?php
require_once __DIR__ . '/includes/helpers.php';
// ?cat=pele | olhos | labios | pinceis | kits ; sem parâmetro mostra tudo
$slug  = $_GET['cat'] ?? '';
$atual = null;
foreach (categories() as $c) if ($c['slug'] === $slug) $atual = $c;
$lista = products($atual['slug'] ?? null);
$title = $atual['name'] ?? 'Todos os produtos';
$active = $atual['slug'] ?? '';
require __DIR__ . '/includes/header.php';
?>
<section class="page-head">
  <p class="crumb"><a href="index.php">Início</a> › <?= e($title) ?></p>
  <h1><?= e($title) ?></h1>
  <p><?= count($lista) ?> produtos disponíveis · preços em Kz</p>
</section>

<main class="wrap">
  <div class="toolbar">
    <input id="q" type="search" placeholder="Procurar produto ou marca…" aria-label="Procurar">
    <select id="sort" aria-label="Ordenar">
      <option value="0">Destaques</option><option value="1">Menor preço</option><option value="2">Maior preço</option><option value="3">Nome A–Z</option>
    </select>
  </div>
  <p id="empty" hidden>Não encontrámos nada com essa pesquisa.</p>
  <div class="grid" id="grid"><?php foreach ($lista as $p) render_card($p); ?></div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
