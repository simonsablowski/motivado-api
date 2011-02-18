	<object id="<? echo $Object->getId(); ?>" type="<? echo $Object->getType(); ?>">
	<? foreach ($Object->getData() as $field => $value): ?>
<? if ($field == 'properties'): ?>
<? echo Json::convertToXml($value, strtolower($field), 3); ?>
<? else: ?>
		<<? echo strtolower($field); ?>><? echo Xml::cleanProperty($value); ?></<? echo strtolower($field); ?>>
<? endif; ?>
	<? endforeach; ?>
	</object>
