	<object id="<? echo $Object->getId(); ?>" type="<? echo $Object->getType(); ?>">
	<? foreach ($Object->getData() as $field => $value): ?>
		<<? echo strtolower($field); ?>><? echo Xml::cleanProperty($value); ?></<? echo strtolower($field); ?>>
	<? endforeach; ?>
	</object>
