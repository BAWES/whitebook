<?php

use yii\web\view;

$this->title = 'Contact us | Whitebook';

?>

<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1><?= Yii::t('frontend', 'About and Contact') ?></h1>
        </div>
        <div class="contact_us">
            <div class="col-md-4 pull-left paddingleft0">
                <div class="contact_us_sec">
                    <h3 class="inner_title"><?= Yii::t('frontend', 'Ask a Question') ?></h3>
                    <div data-example-id="basic-forms" class="bs-example">
                        <form id="form1" name="form1" method="post">

                            <div class="form-group">
                                <input type="text" placeholder="<?= Yii::t('frontend', 'Sender Name *') ?>" id="username" name="username" class="form-control" data-msg-required="<?= Yii::t('frontend', 'Enter name!'); ?>" />
                                <div id="chkname" class="error"></div>
                            </div>

                            <div class="form-group">
                                <input type="email" placeholder="<?= Yii::t('frontend', 'Sender Email *') ?>" id="useremail" name="useremail" class="form-control" data-msg-required="<?= Yii::t('frontend', 'Enter valid Email!'); ?>" />
                                <div id="chkemail" class="error"></div>
                            </div>

                            <div class="form-group">
                                <textarea placeholder="<?= Yii::t('frontend', 'Message *'); ?>" rows="3" id="usermessgae" name="usermessgae" class="form-control" data-msg-required="<?= Yii::t('frontend', 'Enter message!'); ?>"></textarea>
                                <div id="chkmessage" class="error"></div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-default" type="button" id="send" name="send" title="Send Email"><?= Yii::t('frontend', 'Send Email') ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 pull-left">
            <div class="contact_center_sec">
                <h3 class="inner_title"><?= Yii::t('frontend', 'Find an Answer') ?></h3>
                <ul class="inner_contents">
                    <?php foreach($faq_details as $group) { ?>
                    <li>
                        <div class="left_books">
                            <?=\common\components\LangFormat::format($group['group_name'],$group['group_name_ar']); ?>
                        </div>
                        <div class="left_books_lis">
                            <ul>
                                <?php foreach ($group['faq_list'] as $f) { ?>
                                    <li>
                                        <p>
                                            <a class="show_content" href="javascript:void(0);">
                                                <?=\common\components\LangFormat::format(ucfirst(stripcslashes($f['question'])),ucfirst(stripcslashes($f['question_ar']))); ?>
                                            </a>
                                        </p>
                                        <div class="toogle_botom">
                                            <?= ucfirst(stripslashes($f['answer'])) ?>
                                            <?=\common\components\LangFormat::format(ucfirst(stripcslashes($f['answer'])),ucfirst(stripcslashes($f['answer_ar']))); ?>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="col-md-1 pull-left"></div>
    </div>
</div>
</section>

<?php

$this->registerJs("

    jQuery('.show_content').click(function () {
        jQuery(this).parent().next('.toogle_botom').toggle('slow');
    });

    jQuery('#send').on('click', function () {
        var thanks = '<span class=\'sucess_close\'>&nbsp;</span>'+'<span class=\'success-msg\'>".Yii::t('frontend', 'Thanks, we willll be in touch soon')."</span>';
        jQuery('#chkname,#chkemail,#chkmessage').html('');

        var i = 0;
        var j = 0;

        if (jQuery('#username').val() == '')
        {
            jQuery('#chkname').html('".Yii::t('frontend', 'Enter name!')."');
            i = 1;
        } else
        {
            jQuery('#chkname').html('');
            i = 0;
        }

        if (validateEmail(jQuery('#useremail').val())) {
            jQuery('#chkemail').html('');
            i = 0;
            j = 0;
        }
        else
        {
            jQuery('#chkemail').html('".Yii::t('frontend', 'Enter valid Email!')."');
            i = 1;
            j = 1;
        }

        if (jQuery('#usermessgae').val() == '')
        {
            jQuery('#chkmessage').html('".Yii::t('frontend', 'Enter message!')."');
            return false;
            i = 1;
            j = 1;
        } else
        {
            jQuery('#chkmessage').html('');
            var msg = jQuery('#usermessgae').val();
            i = 0;
        }

        /* fine till now*/

        if (i == 1)
        {
            return false;
        }

        if ((i == 0) && (j == 0))
        {
            var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');

            $('#send').html('Sending...').attr('disabled','disabled');

            jQuery.ajax({
                type: 'POST',
                url: '".Yii::$app->urlManager->createAbsoluteUrl('site/contact')."',
                data: {
                    username: jQuery('#username').val(),
                    useremail: jQuery('#useremail').val(),
                    msg: jQuery('#usermessgae').val(),
                    csrf: csrfToken
                },
                success: function (data) {
                    if (data == 1)
                    {
                        jQuery('#login_success').modal('show');
                        jQuery('#success').html(thanks);
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000)
                    }
                    $('#send').html('".Yii::t('frontend', 'Send Email')."').removeAttr('disabled');
                }
            });

            return true;
        }

    });


", View::POS_READY);

$this->registerCss("
    .success-msg {margin-top: 5px; width: 320px; float: left; text-align: left;}
");