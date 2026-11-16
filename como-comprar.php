<?php
$title = 'Como comprar'; $active = '';
require __DIR__ . '/includes/header.php';
$passos = [
  ['Escolhe o produto', 'Navega pelas categorias e vê a cor, o tom e o preço de cada produto.'],
  ['Clica em "Comprar no WhatsApp"', 'A conversa abre já com o nome do produto. Se levares vários, usa o carrinho.'],
  ['Envia a tua localização', 'Partilha o teu ponto no WhatsApp para calcularmos a entrega.'],
  ['Paga como preferires', 'TPA, Multicaixa Express ou dinheiro.'],
  ['Recebe em casa', 'Entrega em Luanda de 24h a 48h.'],
];
?>
<section class="page-head"><p class="crumb"><a href="index.php">Início</a> › Como comprar</p><h1>Como comprar</h1></section>
<section class="wrap narrow">
  <ol class="steps">
    <?php foreach ($passos as $i => [$t, $d]): ?>
      <li><span><?= $i + 1 ?></span><div><h3><?= $t ?></h3><p><?= $d ?></p></div></li>
    <?php endforeach; ?>
  </ol>
  <p class="center"><a class="btn" target="_blank" rel="noopener" href="<?= e(whatsapp_link('Olá! Quero fazer uma encomenda.')) ?>">Começar no WhatsApp</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
