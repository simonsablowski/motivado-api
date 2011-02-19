<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<response>
	<status><? echo $this->localize('Failure'); ?></status>
	<error>
		<type><? echo $this->localize($Error->getType()); ?></type>
		<message><? echo $this->localize($Error->getMessage()); ?></message>
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