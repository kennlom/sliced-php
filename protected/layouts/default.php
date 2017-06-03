<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $parent->getTitle() ?></title>

<link rel="stylesheet" href="/css/style.css" type="text/css" />
</head>

<body>
<div id="wrapper">

    <?php App\Engine::partial('header.php', $parent->getVariables()); ?>
	<?php if($parent->pageTemplate) App\Engine::load($parent->pageTemplate, $parent->getVariables()); ?>
	<?php App\Engine::partial('footer.php'); ?>

</div>
</body>
</html>