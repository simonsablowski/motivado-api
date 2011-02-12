<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<response>
	<status>Failure</status>
	<error>
		<type><? echo $Error->getType(); ?></type>
		<message><? echo $Error->getMessage(); ?></message>
<? if ($this->getApplication()->getConfiguration('debugMode')): ?>
		<details>
<? print_r($Error->getDetails()); ?>
		</details>
		<trace>
<? print_r($Error->getTrace()); ?>
		</trace>
<? endif; ?>
	</error>
</response>