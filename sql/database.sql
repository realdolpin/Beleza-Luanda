-- Beleza Luanda · MySQL / MariaDB
CREATE DATABASE IF NOT EXISTS beleza_luanda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE beleza_luanda;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(30) NOT NULL UNIQUE,
  name VARCHAR(60) NOT NULL,
  icon VARCHAR(16) NOT NULL,
  sort TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- A foto de cada produto vem de assets/img/produtos/<slug>.jpg (ver LEIA-ME.txt)
CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  slug VARCHAR(90) NOT NULL UNIQUE,
  name VARCHAR(120) NOT NULL,
  brand VARCHAR(60) NOT NULL,
  tone VARCHAR(160) NOT NULL COMMENT 'Cor/tom (nos kits, o que inclui)',
  price INT UNSIGNED NOT NULL COMMENT 'Kz',
  badge VARCHAR(30) NOT NULL DEFAULT '',
  active TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(12) NOT NULL UNIQUE,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  zone VARCHAR(160) NOT NULL,
  total INT UNSIGNED NOT NULL,
  status ENUM('Pendente','Confirmado','Enviado','Entregue','Cancelado') NOT NULL DEFAULT 'Pendente',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  product_name VARCHAR(120) NOT NULL,
  qty SMALLINT UNSIGNED NOT NULL,
  unit_price INT UNSIGNED NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

INSERT INTO categories (id, slug, name, icon, sort) VALUES
(1, 'pele', 'Pele', '🧴', 1),
(2, 'olhos', 'Olhos', '👁️', 2),
(3, 'labios', 'Lábios', '💋', 3),
(4, 'pinceis', 'Pincéis e Acessórios', '🖌️', 4),
(5, 'kits', 'Kits', '🎁', 5);

INSERT INTO products (category_id, slug, name, brand, tone, price, badge) VALUES
(1, 'primer-facial', 'Primer Facial', 'Beleza Luanda', 'Transparente', 4000, ''),
(1, 'base-liquida-hd-jackelin', 'Base Líquida HD Jackelin', 'Jackelin', 'Bege Natural', 2500, 'Mais vendida'),
(1, 'base-colour-director-hd', 'Base Colour Director HD', 'Colour Director', 'Bege Médio', 3500, ''),
(1, 'corretivo-liquido', 'Corretivo Líquido', 'Beleza Luanda', 'Natural 03', 3000, ''),
(1, 'po-compacto', 'Pó Compacto', 'Beleza Luanda', 'Bege Claro', 4000, ''),
(1, 'po-solto-flawless-ivy', 'Pó Solto Flawless Ivy', 'Flawless Ivy', 'Translúcido', 5000, 'Favorito'),
(1, 'po-banana', 'Pó Banana', 'Beleza Luanda', 'Banana', 5000, ''),
(1, 'blush', 'Blush', 'Beleza Luanda', 'Rosa Pêssego', 3500, ''),
(1, 'contorno', 'Contorno', 'Beleza Luanda', 'Castanho Suave', 4000, ''),
(1, 'iluminador', 'Iluminador', 'Beleza Luanda', 'Dourado Pérola', 4000, ''),
(1, 'spray-fixador-kiss-beauty', 'Spray Fixador Kiss Beauty', 'Kiss Beauty', 'Transparente', 3500, ''),
(2, 'paleta-de-sombras', 'Paleta de Sombras', 'Beleza Luanda', 'Tons Terra e Rosé', 8000, 'Novo'),
(2, 'delineador-em-gel-miss-rose', 'Delineador em Gel Miss Rose', 'Miss Rose', 'Preto', 2000, ''),
(2, 'lapis-de-olho', 'Lápis de Olho', 'Beleza Luanda', 'Preto', 500, ''),
(2, 'mascara-de-cilios-rimel', 'Máscara de Cílios / Rímel', 'Beleza Luanda', 'Preto Intenso', 3500, 'Favorito'),
(2, 'cilios-posticos', 'Cílios Postiços', 'Beleza Luanda', 'Natural', 2500, ''),
(2, 'cola-para-cilios', 'Cola para Cílios', 'Beleza Luanda', 'Transparente', 2000, ''),
(2, 'lapis-de-sobrancelha', 'Lápis de Sobrancelha', 'Beleza Luanda', 'Castanho', 1000, ''),
(2, 'gel-de-sobrancelha-sheglam', 'Gel de Sobrancelha SHEGLAM', 'SHEGLAM', 'Castanho Escuro', 2500, ''),
(3, 'batom-liquido-matte-miss-look', 'Batom Líquido Matte Miss Look', 'Miss Look', 'Rosa Nude', 750, 'Mais vendido'),
(3, 'batom-cremoso', 'Batom Cremoso', 'Beleza Luanda', 'Rosa Chá', 2000, ''),
(3, 'lip-gloss', 'Lip Gloss', 'Beleza Luanda', 'Rosé Transparente', 2500, 'Novo'),
(3, 'lapis-labial', 'Lápis Labial', 'Beleza Luanda', 'Nude', 500, ''),
(3, 'lip-balm-hidratante', 'Lip Balm Hidratante', 'Beleza Luanda', 'Incolor', 1500, ''),
(4, 'kit-10-pinceis', 'Kit 10 Pincéis', 'Beleza Luanda', 'Rosa e Dourado', 3500, 'Mais vendido'),
(4, 'pincel-kabuki', 'Pincel Kabuki', 'Beleza Luanda', 'Rosa', 2000, ''),
(4, 'pincel-duplo-corretivo', 'Pincel Duplo Corretivo', 'Beleza Luanda', 'Rosa', 1500, ''),
(4, 'esponja-beauty-blender', 'Esponja Beauty Blender', 'Beleza Luanda', 'Rosa', 2000, ''),
(4, 'kit-esponjas-puff', 'Kit Esponjas + Puff', 'Beleza Luanda', 'Rosa Pastel', 8000, ''),
(5, 'kit-iniciante', 'Kit Iniciante', 'Beleza Luanda', 'Base + Corretivo + Pó + Batom + Pincel', 10000, 'Promoção'),
(5, 'kit-completo-profissional', 'Kit Completo Profissional', 'Beleza Luanda', 'Maquilhagem completa para o dia a dia e trabalho', 25000, 'Promoção'),
(5, 'kit-noiva', 'Kit Noiva', 'Beleza Luanda', 'Base HD + Pó + Paleta + Cílios + Fixador', 20000, 'Promoção');
