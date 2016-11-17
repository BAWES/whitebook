<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\base;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Location;
use frontend\models\AddressType;

$this->title ='Address Book | Whitebook';

?>

<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1><?php echo Yii::t('frontend','Address Book'); ?></h1>
        </div>

        <div class="account_setings_sections">
            <?=$this->render('_sidebar_menu');?>
            <div class="col-md-9 border-left">
                <div class="accont_informations">
                    <div class="accont_info">
                        <table class="table table-bordered">
                            <tr>
                            	<td>
                            		<div class="address_box">

                                        <?php if($address['address_name']) { ?>
                                            <b><?=Yii::t('frontend','Address Name:')?></b> <br />
                                            <?= $address['address_name'] ?>
                                            <br />
                                            <br />
                                        <?php } ?>

                                        <!-- address type -->
                                        <b><?=Yii::t('frontend','Address Type:')?></b> <br />
                                        <?= AddressType::type_name($address['address_type_id']); ?>

                                        <br />
                                        <br />

                                        <!-- address -->
                                        <b><?=Yii::t('frontend','Address:')?></b> <br />
                                        <?= $address['address_data']?nl2br($address['address_data']).'<br />':'' ?>

                                        <!-- address question response -->
                                        <ul>
                                            <?php if ($questions) { ?>
                                                <?php foreach ($questions as $row) { ?>
                                            <li>
                                                <br />
                                                <b><?= $row['question'] ?></b>
                                                <br />
                                                <?= $row['response_text'] ?>
                                            </li>
                                        <?php } ?>
                                        <?php }
                                            ?>
                                        </ul>

                                        <br />

                                        <b><?=Yii::t('frontend','Area:')?></b> <br />
                                        <?=\common\components\LangFormat::format($address->location->location,$address->location->location_ar); ?><br/>

                                        <br />

                                        <b><?=Yii::t('frontend','City:')?></b> <br />
                                        <?=\common\components\LangFormat::format($address->city->city_name,$address->city->city_name_ar); ?><br/>
                            		</div>
                            	</td>
                            </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>    
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");

    