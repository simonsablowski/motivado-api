<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<objectsequence>
	<title><? echo $Coaching->getTitle(); ?></title>
	<description><? echo $Coaching->getDescription(); ?></description>
	<objects>
<? foreach ($Objects as $Object): ?>
		<object id="<? echo $Object->getId(); ?>" type="<? echo $Object->getType(); ?>">
<? foreach ($Object->getData() as $field => $value): ?>
<? if ($field == 'properties'): ?>
<? echo Json::convertToXml($value, strtolower($field), 3); ?>
<? else: ?>
			<<? echo strtolower($field); ?>><? echo Xml::cleanProperty($value); ?></<? echo strtolower($field); ?>>
<? endif; ?>
<? endforeach; ?>
		</object>
<? endforeach; ?>
	</objects>
</objectsequence>