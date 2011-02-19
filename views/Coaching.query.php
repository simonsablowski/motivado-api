<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<objectsequence>
	<title><? echo $Coaching->getTitle(); ?></title>
	<description><? echo $Coaching->getDescription(); ?></description>
	<objects>
<? foreach ($Objects as $Object): ?>
	<? $this->displayView('components/Object.php', array('Object' => $Object)); ?>
<? endforeach; ?>
	</objects>
</objectsequence>