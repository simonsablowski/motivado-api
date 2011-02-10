	<usersinteraction>
	<? foreach ($UsersInteraction->getData() as $field => $value): ?>
		<<? echo strtolower($field); ?>><? echo Xml::cleanProperty($value); ?></<? echo strtolower($field); ?>>
	<? endforeach; ?>
	</usersinteraction>
