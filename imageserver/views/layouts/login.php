<?php
use yii\bootstrap\ActiveForm;
use app\models\Siteinfo;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?= Html::csrfMetaTags() ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<title>Whitebook</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<?php $this->head() ?>
</head>

<body class="error-body no-top">
	<?php $this->beginBody() ?>
	<div class="container">
		<?= $content ?>
	</div>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
