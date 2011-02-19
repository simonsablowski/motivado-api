<user id="<? echo $User->getId(); ?>">
	<? foreach ($User->getData() as $field => $value): ?>
	<<? echo strtolower($field); ?>><? echo Xml::cleanProperty($value); ?></<? echo strtolower($field); ?>>
	<? endforeach; ?>
</user>
