<?php
/*
 *
 * OXID mobile order overview
 * https://github.com/tabsl/oxid-mobile-order-overview
 * 
 * GET key = authentication (add your personal key!)
 * GET limit = oder limit (default 20)
 * GET folder = order folder (default all folders)
 *
*/
if($_GET['key'] != 'your_key_here') {
	exit;
}

require_once dirname(__FILE__) . "/bootstrap.php";
$shop = oxRegistry::getConfig()->getActiveShop();
$orders = getOrders();
$status = oxRegistry::getConfig()->getConfigParam('aOrderfolder');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $shop->oxshops__oxname->value ?> Bestellungen</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<style>
	th, td { font-size: 14px; }
	.details { display: none; }
</style>

<body>

<div class="container-fluid" style="margin: 10px 0;">

<div class="text-center">
	<b><?php echo $shop->oxshops__oxname->value ?> Bestellungen</b><br><br>
</div>

<?php if(count($orders) > 0) { ?>
	<table class="table table-sm">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col">Name</th>
		  <th scope="col">Summe</th>
		</tr>
	  </thead>
	  <tbody>
		<?php foreach ($orders as $key => $order) { ?>
			<tr onClick="$('#details_<?php echo $key; ?>').fadeToggle();" style="color: <?php echo $status[$order->oxorder__oxfolder->value]; ?>">
			  <th scope="row"><?php echo $order->oxorder__oxordernr->value ?></th>
			  <td><?php echo $order->oxorder__oxbillfname->value ?> <?php echo $order->oxorder__oxbilllname->value ?></td>
			  <td><?php echo $order->getFormattedTotalBrutSum() ?> <?php echo $order->oxorder__oxcurrency->value ?></td>
			</tr>
			<tr class="details" id="details_<?php echo $key; ?>">
			  <td></th>
			  <td colspan="2">
			  	<br>
			  	<?php foreach ($order->getOrderArticles() as $article) { ?>
			  		<?php echo $article->oxorderarticles__oxamount->value ?>x <?php echo $article->oxorderarticles__oxtitle->value ?><br>
			  	<?php } ?>
			  	<hr>
			  	<?php echo $order->oxorder__oxbillfname->value ?> <?php echo $order->oxorder__oxbilllname->value ?><br>
			  	<?php echo $order->oxorder__oxbillstreet->value ?> <?php echo $order->oxorder__oxbillstreetnr->value ?><br>
			  	<?php echo $order->oxorder__oxbillzip->value ?> <?php echo $order->oxorder__oxbillcity->value ?><br>
			  	<?php echo $order->oxorder__oxbillcountry->value ?><br>
			  	<?php echo $order->oxorder__oxbillemail->value ?>
			  	<hr>
			  	Datum: <i><?php echo $order->oxorder__oxorderdate->value ?></i><br>
			  	Status: <i style="color: <?php echo $status[$order->oxorder__oxfolder->value]; ?>"><?php echo $order->oxorder__oxfolder->value ?></i><br>
			  	Zahlart: <i><?php echo $order->oxorder__oxpaymenttype->value ?></i><br>
			  	Versandart: <i><?php echo $order->oxorder__oxdeltype->value ?></i>
			  	<br><br>
			  </td>
			</tr>
		<?php } ?>	    
	  </tbody>
	</table>
<?php } ?>

</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>

<?php
function getOrders($limit = 20) {
	if($_GET['limit'] > 0) {
		$limit = strip_tags($_GET['limit']);
	}
	if(!empty($_GET['folder'])) {
		$folder = strip_tags($_GET['folder']);
	}
	$sSelect = 'SELECT * FROM oxorder '.(!empty($folder) ? 'WHERE oxfolder = "'.$folder.'"' : '').' ORDER BY oxorderdate DESC LIMIT 0,'.(int)$limit;
	$oOrderlist = oxNew('oxlist');
	$oOrderlist->init('oxorder');
	$oOrderlist->selectString($sSelect);
	if (!$oOrderlist->count()) {
		return null;
	}

	return $oOrderlist;
}
?>
