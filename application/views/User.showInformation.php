<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<? echo '<?xml-stylesheet type="text/xsl" href="' . $this->getApplication()->getConfiguration('baseDirectory') . 'xsl/User.showInformation.xsl"?>'; ?>

<usersinformation>
	<? include 'components/User.php'; ?>
	<coachings>
<? foreach ($User->getCoachings() as $Coaching): ?>
	<? include 'components/Coaching.php'; ?>
<? endforeach; ?>
	</coachings>
	<interactions>
<? foreach ($User->getInteractions() as $UsersInteraction): ?>
	<? include 'components/UsersInteraction.php'; ?>
<? endforeach; ?>
	</interactions>
</usersinformation>