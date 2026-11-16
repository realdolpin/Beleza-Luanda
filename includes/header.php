<?php
require_once __DIR__ . '/helpers.php';
// Cada página define $title e $active (home, pele, olhos...) antes de incluir este ficheiro
$cats = categories();
?><!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? SITE_NAME) ?> · <?= SITE_NAME ?></title>
  <meta name="description" content="<?= e(SITE_SLOGAN) ?>. Maquilhagem original em Luanda com entrega em 24h a 48h.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700;1,9..144,500&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="blobs" aria-hidden="true"><i></i><i></i><i></i></div>

<header class="top">
  <a class="logo" href="index.php">Beleza <em>Luanda</em></a>
  <nav id="nav" aria-label="Menu principal">
    <a href="index.php" class="<?= ($active ?? '') === 'home' ? 'on' : '' ?>">Início</a>
    <?php foreach ($cats as $c): ?>
      <a href="loja.php?cat=<?= e($c['slug']) ?>" class="<?= ($active ?? '') === $c['slug'] ? 'on' : '' ?>"><?= e($c['name']) ?></a>
    <?php endforeach; ?>
  </nav>
  <button class="cart-btn" id="openCart" aria-label="Abrir carrinho">🛍️<b id="count">0</b></button>
  <button class="burger" id="burger" aria-label="Abrir menu" aria-expanded="false">☰</button>
</header>
