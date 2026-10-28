<?php
require_once 'includes/config.php'; require_once 'includes/functions.php'; $page_title='Destinations';
$q=trim($_GET['q']??'');
$sql="SELECT * FROM destinations"; $params=[];
if($q!==''){ $sql.=" WHERE destination_name LIKE ? OR city LIKE ? OR country LIKE ?"; $like="%$q%"; $params=[$like,$like,$like];}
$sql.=" ORDER BY destination_name";
$stmt=$pdo->prepare($sql);$stmt->execute($params);$destinations=$stmt->fetchAll(); require 'includes/header.php';
?>
<section class="page-hero"><div class="container"><div class="breadcrumb">Home / Destinations</div><span class="eyebrow">Find your kind of beautiful</span><h1>Places that stay with you.</h1><p>From Sri Lankan hill country to tropical islands and iconic world cities, find a destination for every kind of traveller.</p></div></section>
<section class="section"><div class="container"><form class="filter-row" method="get"><div class="field" style="flex:1"><label for="q">Search destinations</label><input id="q" name="q" value="<?= e($q) ?>" placeholder="Destination, city or country"></div><button class="btn" type="submit">Search</button><a class="btn btn-light" href="destinations.php">Clear</a></form>
<?php if(!$destinations): ?><div class="empty-state"><h3>No destinations found</h3><p>Try another destination name.</p></div><?php else: ?><div class="grid-4 destination-grid"><?php foreach($destinations as $d): ?><article class="destination-card"><div class="card-image"><img loading="lazy" src="<?= e(travel_image_src($d['destination_name'], $d['image_url'])) ?>" alt="<?= e($d['destination_name']) ?>" onerror="this.onerror=null;this.src='assets/images/tropical-coast.jpg'"><span class="image-tag"><?= e($d['country']) ?></span></div><div class="card-body"><h3><?= e($d['destination_name']) ?></h3><p class="muted">⌖ <?= e($d['city']) ?>, <?= e($d['country']) ?></p><p><?= e($d['description']) ?></p><a class="text-link" href="packages.php?q=<?= urlencode($d['destination_name']) ?>">Find a tour ↗</a></div></article><?php endforeach; ?></div><?php endif; ?>
</div></section>
<?php require 'includes/footer.php'; ?>
