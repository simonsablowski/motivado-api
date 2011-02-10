<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<? echo '<?xml-stylesheet type="text/xsl" href="' . $this->getApplication()->getConfiguration('baseDirectory') . 'xsl/User.showInformation.xsl"?>'; ?>

<usersinformation>
	<? include 'User.php'; ?>
	<coachings>
<? foreach ($User->getCoachings() as $Coaching): ?>
	<? include 'Coaching.php'; ?>
<? endforeach; ?>
	</coachings>
	<interactions>
<? foreach ($User->getInteractions() as $UsersInteraction): ?>
	<? include 'UsersInteraction.php'; ?>
<? endforeach; ?>
	</interactions>
</usersinformation>