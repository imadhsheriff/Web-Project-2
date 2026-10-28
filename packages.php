<?php
require_once 'includes/config.php'; require_once 'includes/functions.php'; $page_title='Tour packages';
$q=trim($_GET['q']??'');$type=trim($_GET['type']??'');
$sql="SELECT p.*, d.destination_name AS destination FROM tour_packages p LEFT JOIN destinations d ON p.destination_id=d.destination_id WHERE p.is_active=1";$params=[];
if($q!==''){$sql.=" AND (p.package_name LIKE ? OR d.destination_name LIKE ? OR p.short_description LIKE ?)";$like="%$q%";array_push($params,$like,$like,$like);}
if($type!==''){$sql.=" AND p.package_type=?";$params[]=$type;}
$sql.=" ORDER BY p.package_id";$stmt=$pdo->prepare($sql);$stmt->execute($params);$packages=$stmt->fetchAll();require 'includes/header.php';
?>
<section class="page-hero"><div class="container"><div class="breadcrumb">Home / Tour packages</div><span class="eyebrow">Your next chapter starts here</span><h1>Find a journey that feels like yours.</h1><p>Explore 12 curated Sri Lankan and international travel experiences, compare details and choose the adventure that suits your pace.</p></div></section>
<section class="section"><div class="container"><form class="filter-row" method="get"><div class="field" style="flex:1"><label for="packageSearch">Search packages</label><input id="packageSearch" name="q" value="<?= e($q) ?>" placeholder="Search by package or destination"></div><div style="display:flex;align-items:end;gap:9px"><div class="field"><label for="type">Travel style</label><select id="type" name="type"><option value="">All types</option><?php foreach(['Adventure','Culture','Nature','Beach','Family'] as $t): ?><option <?= $type===$t?'selected':'' ?>><?= e($t) ?></option><?php endforeach; ?></select></div><button class="btn" type="submit">Filter</button></div></form><?php if(!$packages): ?><div class="empty-state"><h3>No packages found</h3><p>Try a different search or clear your filters.</p><a class="btn" href="packages.php">Show all packages</a></div><?php else: ?><div class="grid-4 package-grid"><?php require 'includes/package_cards.php'; ?></div><?php endif; ?></div></section>
<?php require 'includes/footer.php'; ?>
