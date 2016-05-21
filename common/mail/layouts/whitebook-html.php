<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="margin:0; padding:0;">
    <?php $this->beginBody() ?>

    <table border="0" style="width:600px; background:#fff; border:none; border-radius:6px; border-left:1px solid #ccc;border-right:1px solid #ccc;" cellpadding="0" cellspacing="0">
        <!-- header_content start -->
        <tr>
            <td valign="top">
                <table style="width:600px; background:#fff; height:110px; border-bottom:2px solid #ccc;">
                    <td width="20"></td>
                    <td width="140">
                        <?= Html::img('@web/uploads/app_img/logo.png',['class'=>"logo",'style'=>"height:33px; width:233px;margin-left:125px;"]) ?>
                    </td>
                    <td width="20"></td>
                </table>
            </td>
        </tr>
        <!-- header_content end -->
        <!-- middle_content_content start -->
        <tr>
            <td valign="top">
                <table style="width:600px; background:#fff;">
                    <tr height="5"></tr>

                    <?= $content ?>

                    <tr>
                        <td width="20"></td>
                        <td>
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="130"></td>
                                    <td width="320px">
                                    </td>
                                    <td width="130"></td>
                                </tr>
                            </table>
                        </td>
                        <td width="20"></td>
                    </tr>
                    <tr height="30"></tr>
                </table>
            </td>
        </tr>
        <!-- middle_content_content end -->
        <tr>
            <td valign="top">
                <table style="width:600px; background:#fff;">
                    <tbody><tr height="5"></tr>
                        <tr>
                            <td width="20"></td>
                            <td style=" font:normal 14px/21px arial; color:#333333;">
                                For any further assistance or queries, please email us at <?= Yii::$app->params['supportEmail']; ?> or call us on
                            </td>
                            <td width="20"></td>
                        </tr>
                        <tr height="5"></tr>
                        <tr>
                            <td width="20"></td>
                            <td style=" font:normal 20px arial; color:#666;">

                            </td>
                            <td width="20"></td>
                        </tr>
                        <tr height="5"></tr>
                        <tr>
                            <td width="20"></td>
                        </tr>
                        <tr>
                            <td width="20"></td>
                            <td style=" font:normal 14px/23px arial; color:#666;">
                                Best Regards,<br>Whitebook Team
                            </td>
                            <td width="20"></td>
                        </tr>
                        <tr height="20"></tr>
                    </tbody></table>
                </td>
            </tr>
        </table>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
