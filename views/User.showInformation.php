<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<? echo '<?xml-stylesheet type="text/xsl" href="' . $this->getApplication()->getConfiguration('baseDirectory') . 'xsl/User.showInformation.xsl"?>'; ?>

<usersinformation>
	<? $this->displayView('components/User.php', array('User' => $User)); ?>
	<coachings>
<? foreach ($User->getCoachings() as $Coaching): ?>
	<? $this->displayView('components/Coaching.php', array('Coaching' => $Coaching)); ?>
<? endforeach; ?>
	</coachings>
	<interactions>
<? foreach ($User->getInteractions() as $UsersInteraction): ?>
	<? $this->displayView('components/UsersInteraction.php', array('UsersInteraction' => $UsersInteraction)); ?>
<? endforeach; ?>
	</interactions>
</usersinformation>