<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<userinformation>
	<? include 'User.php'; ?>
	<coachings>
<? foreach ($User->getCoachings() as $Coaching): ?>
	<? include 'Coaching.php'; ?>
<? endforeach; ?>
	</coachings>
	<interaction>
<? foreach ($User->getInteraction() as $UserInteraction): ?>
	<? include 'UserInteraction.php'; ?>
<? endforeach; ?>
	</interaction>
</userinformation>