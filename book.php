<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/tour_routes.php';
require_tourist();
$id=(int)($_GET['package_id']??$_POST['package_id']??0);
$s=$pdo->prepare("SELECT p.*,d.destination_name AS destination,d.city,d.country FROM tour_packages p LEFT JOIN destinations d ON d.destination_id=p.destination_id WHERE p.package_id=? AND p.is_active=1");
$s->execute([$id]); $pkg=$s->fetch();
if(!$pkg){flash('Please select an available package.','error');header('Location: packages.php');exit;}
$route=travelhub_route_for_package($pkg['package_name'],$pkg['destination']??'');
$errors=[];
$profileStmt=$pdo->prepare('SELECT first_name,last_name,email,mobile_number,address FROM tourists WHERE tourist_id=?');
$profileStmt->execute([$_SESSION['tourist_id']]);
$profile=$profileStmt->fetch() ?: [];
$form=[
  'contact_name'=>trim($_POST['contact_name']??trim(($profile['first_name']??'').' '.($profile['last_name']??''))),
  'contact_email'=>trim($_POST['contact_email']??($profile['email']??'')),
  'contact_phone'=>trim($_POST['contact_phone']??($profile['mobile_number']??'')),
  'pickup_location'=>trim($_POST['pickup_location']??($profile['address']??'')),
  'travel_date'=>$_POST['travel_date']??'',
  'number_of_persons'=>$_POST['number_of_persons']??'1',
  'notes'=>trim($_POST['notes']??'')
];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $date=$form['travel_date']; $count=filter_var($form['number_of_persons'], FILTER_VALIDATE_INT); $notes=$form['notes'];
  if($form['contact_name']==='') $errors[]='Please enter the lead traveller’s full name.'; elseif(!preg_match('/^[\p{L}\p{M} .\x27-]+$/u',$form['contact_name'])) $errors[]='Full name must contain letters only (spaces, apostrophes and hyphens are allowed).';
  if($form['contact_email']==='' || !filter_var($form['contact_email'],FILTER_VALIDATE_EMAIL)) $errors[]='Please enter a valid email address.';
  if($form['contact_phone']==='' || !preg_match('/^[0-9+() .-]{7,25}$/',$form['contact_phone'])) $errors[]='Please enter a valid contact phone number (7–25 characters).';
  if($form['pickup_location']==='') $errors[]='Please enter your pickup location or hotel address.'; elseif(preg_match('/\d/u',$form['pickup_location'])) $errors[]='Pickup location must use text only; please remove numbers.';
  if(!$date || $date<date('Y-m-d')) $errors[]='Choose a travel date today or later.';
  if($count===false || $count<1 || $count>20) $errors[]='Traveller count must be between 1 and 20.';
  if(mb_strlen($notes)>1000) $errors[]='Special requests must be 1,000 characters or fewer.';
  if(!$errors){
    $bookingNotes="Lead traveller: {$form['contact_name']}\nEmail: {$form['contact_email']}\nPhone: {$form['contact_phone']}\nPickup location: {$form['pickup_location']}";
    if($notes!=='') $bookingNotes.="\nSpecial requests: {$notes}";
    try {
      $pdo->beginTransaction();
      $total=$count*(float)$pkg['price'];
      $s=$pdo->prepare("INSERT INTO bookings(tourist_id,package_id,booking_date,travel_date,booking_status,number_of_persons,total_amount,payment_status,notes) VALUES(?,?,CURDATE(),?,'Pending',?,?,'Unpaid',?)");
      $s->execute([$_SESSION['tourist_id'],$id,$date,$count,$total,$bookingNotes]); $bid=(int)$pdo->lastInsertId();
      $reference='TH-CASH-'.$bid.'-'.strtoupper(bin2hex(random_bytes(3)));
      $s=$pdo->prepare("INSERT INTO payments(booking_id,payment_date,amount,payment_method,payment_status,transaction_reference) VALUES(?,NOW(),?,'Cashier counter','Pending',?)");
      $s->execute([$bid,$total,$reference]);
      $pdo->commit();
      header('Location: booking_receipt.php?booking_id='.$bid); exit;
    } catch(Throwable $ex) { if($pdo->inTransaction()) $pdo->rollBack(); error_log('TravelHub booking failed: '.$ex->getMessage()); $errors[]='We could not save your booking. Please try again or contact the administrator.'; }
  }
}
$page_title='Book '.$pkg['package_name']; require 'includes/header.php';
$points=travelhub_all_route_points($route);
?>
<section class="section section-soft booking-page-section"><div class="container booking-page-container"><form class="form-shell booking-form-shell" method="post" novalidate><div class="booking-package-banner"><img src="<?= e(travel_image_src($pkg['package_name'].' '.$pkg['destination'], $pkg['image_url'])) ?>" alt="<?= e($pkg['package_name']) ?>" onerror="this.onerror=null;this.src='assets/images/tropical-coast.jpg'"><div class="booking-package-banner-overlay"><span class="eyebrow">Reserve now · Pay at cashier</span><h1>Book your journey</h1><p><?= e($pkg['package_name']) ?> · <?= e($pkg['destination']) ?> · <?= money($pkg['price']) ?> per person</p></div></div>
<?php if($errors): ?><div class="alert error" role="alert" aria-live="assertive"><strong>Please correct the following:</strong><ul><?php foreach($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<input type="hidden" name="package_id" value="<?= (int)$id ?>">
<div class="form-grid booking-details-grid">
<div class="field"><label for="contact_name">Lead traveller full name <span aria-hidden="true">*</span></label><input id="contact_name" type="text" name="contact_name" autocomplete="name" maxlength="180" required value="<?= e($form['contact_name']) ?>" aria-describedby="contact_name_error"><small class="field-error" id="contact_name_error" hidden>Enter a full name using letters only. Numbers are not allowed.</small></div>
<div class="field"><label for="contact_email">Email address <span aria-hidden="true">*</span></label><input id="contact_email" type="email" name="contact_email" autocomplete="email" maxlength="190" required value="<?= e($form['contact_email']) ?>" aria-describedby="contact_email_error"><small class="field-error" id="contact_email_error" hidden>Enter a valid email address.</small></div>
<div class="field"><label for="contact_phone">Contact phone number <span aria-hidden="true">*</span></label><input id="contact_phone" type="tel" name="contact_phone" autocomplete="tel" minlength="7" maxlength="25" pattern="[0-9+() .-]{7,25}" required value="<?= e($form['contact_phone']) ?>" aria-describedby="contact_phone_error"><small class="field-error" id="contact_phone_error" hidden>Enter a valid phone number (7–25 characters).</small></div>
<div class="field"><label for="pickup_location">Pickup location / hotel address <span aria-hidden="true">*</span></label><input id="pickup_location" type="text" name="pickup_location" autocomplete="street-address" maxlength="255" required value="<?= e($form['pickup_location']) ?>" aria-describedby="pickup_location_error"><small class="field-error" id="pickup_location_error" hidden>Enter the pickup location using text only; numbers are not allowed.</small></div>
<div class="field"><label for="travel_date">Travel date <span aria-hidden="true">*</span></label><input id="travel_date" type="date" name="travel_date" min="<?= date('Y-m-d') ?>" required value="<?= e($form['travel_date']) ?>" aria-describedby="travel_date_error"><small class="field-error" id="travel_date_error" hidden>Choose a travel date today or later.</small></div>
<div class="field"><label for="number_of_persons">Number of travellers <span aria-hidden="true">*</span></label><input id="number_of_persons" type="number" name="number_of_persons" min="1" max="20" step="1" required value="<?= e($form['number_of_persons']) ?>" aria-describedby="travellers_error"><small class="field-error" id="travellers_error" hidden>Enter a number from 1 to 20.</small></div>
<div class="field full"><label for="notes">Special requests (optional)</label><textarea id="notes" name="notes" maxlength="1000" placeholder="Accessibility, pickup preferences or other notes."><?= e($form['notes']) ?></textarea><small>Optional · Maximum 1,000 characters.</small></div>
</div>
<div class="cashier-instruction"><strong>Cashier payment only</strong><p>After you submit this booking, a printable reservation receipt will be generated. Take it to the TravelHub cashier and pay the displayed amount. Your booking remains unpaid until the cashier confirms the payment.</p></div>
<section class="route-plan-box"><div class="route-plan-heading"><span class="eyebrow">Your route preview</span><h2>Where the tour starts, stops and ends</h2><p>Review every planned stop before reserving. This is a sample itinerary map; exact pickup details are confirmed by the tour operator.</p></div>
<div class="route-endpoints"><div><span class="route-point-label">START POINT</span><strong><?= e($route['start']['name']) ?></strong></div><div><span class="route-point-label">END POINT</span><strong><?= e($route['end']['name']) ?></strong></div></div>
<div id="bookingRouteMap" class="travelhub-route-map" role="img" aria-label="Map showing the tour start point, sightseeing stops and end point"></div>
<ol class="route-stop-list"><?php foreach($points as $i=>$point): ?><li class="<?= ($i===0?'route-start':($i===count($points)-1?'route-end':'route-stop')) ?>"><span class="route-stop-number"><?= $i===0?'S':($i===count($points)-1?'E':(string)$i) ?></span><div><strong><?= e($point['name']) ?></strong><?php if(!empty($point['description'])): ?><p><?= e($point['description']) ?></p><?php endif; ?></div></li><?php endforeach; ?></ol>
<a class="btn btn-light" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/<?= rawurlencode($route['start']['name']) ?>/<?= rawurlencode($route['end']['name']) ?>">Open route in Google Maps ↗</a></section>
<div class="form-actions"><button class="btn" type="submit">Confirm booking &amp; print receipt ↗</button></div></form></div></section>
<script>
(function(){
 const form=document.querySelector('.booking-form-shell'); if(!form)return;
 let summary=form.querySelector('#booking-validation-summary');
 if(!summary){summary=document.createElement('div');summary.id='booking-validation-summary';summary.className='booking-validation-summary';summary.setAttribute('role','alert');summary.setAttribute('aria-live','assertive');summary.hidden=true;const banner=form.querySelector('.booking-package-banner');if(banner)banner.insertAdjacentElement('afterend',summary);else form.prepend(summary);}
 const fields=[
  ['contact_name',v=>v.trim().length>0 && /^[\p{L}\p{M} .'-]+$/u.test(v.trim()),'Enter a full name using letters only. Numbers are not allowed.'],
  ['contact_email',v=>/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),'Enter a valid email address.'],
  ['contact_phone',v=>/^[0-9+() .-]{7,25}$/.test(v.trim()),'Enter a valid phone number (7–25 characters).'],
  ['pickup_location',v=>v.trim().length>0 && !/\d/.test(v),'Enter the pickup location using text only; numbers are not allowed.'],
  ['travel_date',v=>v!=='' && v>=new Date().toISOString().slice(0,10),'Choose a travel date today or later.'],
  ['number_of_persons',v=>Number.isInteger(Number(v)) && Number(v)>=1 && Number(v)<=20,'Enter a number from 1 to 20.']
 ];
 function check(id,valid,message){
  const input=document.getElementById(id),hint=document.getElementById(id+'_error');
  const ok=valid(input.value);
  input.setCustomValidity(ok?'':message);
  if(hint){
    hint.textContent=message;
    hint.hidden=ok;
    if(!ok){
      /* Inline styles guarantee a visible red message box even if an older CSS file is cached. */
      hint.style.setProperty('display','block','important');
      hint.style.setProperty('box-sizing','border-box','important');
      hint.style.setProperty('width','100%','important');
      hint.style.setProperty('margin','8px 0 0','important');
      hint.style.setProperty('padding','10px 12px','important');
      hint.style.setProperty('border','1px solid #f0a6a1','important');
      hint.style.setProperty('border-left','5px solid #d92d20','important');
      hint.style.setProperty('border-radius','8px','important');
      hint.style.setProperty('background-color','#fff0ef','important');
      hint.style.setProperty('color','#a61b14','important');
      hint.style.setProperty('font-size','.86rem','important');
      hint.style.setProperty('font-weight','700','important');
      hint.style.setProperty('line-height','1.45','important');
      hint.style.setProperty('box-shadow','0 3px 10px rgba(217,45,32,.10)','important');
    } else {
      hint.style.removeProperty('display');
    }
  }
  input.setAttribute('aria-invalid',String(!ok));
  return ok;
}
 fields.forEach(([id,valid,msg])=>{const input=document.getElementById(id);input.addEventListener('input',()=>{check(id,valid,msg);updateSummary();});input.addEventListener('change',()=>{check(id,valid,msg);updateSummary();});input.addEventListener('blur',()=>{check(id,valid,msg);updateSummary();});});
 function updateSummary(){const invalid=fields.filter(([id,valid])=>!valid(document.getElementById(id).value));if(invalid.length){summary.hidden=false;summary.innerHTML='<strong>Please correct the following fields:</strong><ul>'+invalid.map(([id,valid,msg])=>'<li>'+msg.replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]))+'</li>').join('')+'</ul>';}else{summary.hidden=true;summary.innerHTML='';}}
 form.addEventListener('submit',e=>{let first=null;fields.forEach(([id,valid,msg])=>{if(!check(id,valid,msg)&&!first)first=document.getElementById(id);});updateSummary();if(first){e.preventDefault();first.focus();first.reportValidity();summary.scrollIntoView({behavior:'smooth',block:'center'});}});
})();
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function(){const el=document.getElementById('bookingRouteMap');if(!el)return;const points=<?= json_encode($points, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;if(!window.L){el.innerHTML='<p class="map-fallback">Map preview requires an internet connection. The complete route and every stop are listed below.</p>';return;}const map=L.map(el,{scrollWheelZoom:false});L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; OpenStreetMap contributors'}).addTo(map);const latlngs=[];points.forEach((p,i)=>{const ll=[Number(p.lat),Number(p.lng)];latlngs.push(ll);const label=i===0?'START':(i===points.length-1?'END':'STOP '+i);L.marker(ll).addTo(map).bindPopup('<strong>'+label+': '+String(p.name).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]))+'</strong>');});L.polyline(latlngs,{color:'#087f8c',weight:4,dashArray:'8 7'}).addTo(map);map.fitBounds(latlngs,{padding:[28,28]});setTimeout(()=>map.invalidateSize(),250);})();
</script>
<?php require 'includes/footer.php'; ?>