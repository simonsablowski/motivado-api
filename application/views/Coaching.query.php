<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<objectsequence>
	<title><? echo $Coaching->getTitle(); ?></title>
	<description><? echo $Coaching->getDescription(); ?></description>
	<objects>
<? foreach ($Objects as $Object): ?>
	<? include 'components/Object.php'; ?>
<? endforeach; ?>
	</objects>
</objectsequence>