<?php

use yii\helpers\Url;
use common\models\Vendor;
use common\models\Country;
use common\models\City;
use common\models\Location;
use common\models\Area;
use common\models\AddressType;
use yii\helpers\Html;
use common\models\VendorItemPricing;
use common\models\ItemType;
use common\models\Category;
use yii\widgets\Breadcrumbs;

$this->title = 'Whitebook - Checkout';
?>

<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com">
    <div class="container paddng0">
        <!-- Events slider start -->
<?php require(__DIR__ . '/../browse/events_slider.php'); ?>
        <!-- Events slider end -->
        <div class="breadcrumb_common">
            <div class="bs-example">

                <h2>Order Summary</h2>
            </div>
        </div>
        <!-- Mobile start Here-->

        <div class="product_detail_section responsive-detail-section"><!--product detail start-->
            <div class="col-md-12 padding0">
                <div class="product_detials_common normal_tables">
                    <table border="2">
                        <tr>
                            <td><?=Yii::t('frontend','S.No')?></td>
                            <td><?=Yii::t('frontend','Image')?></td>
                            <td><?=Yii::t('frontend','Vendor name')?></td>
                            <td><?=Yii::t('frontend','Item Name')?></td>
                            <td><?=Yii::t('frontend','Price')?></td>
                            <td><?=Yii::t('frontend','Quantity')?></td>
                            <td><?=Yii::t('frontend','Shipping / Delivery')?></td>
                            <td><?=Yii::t('frontend','Delete')?></td>
                            <td><?=Yii::t('frontend','Total Price')?></td>
                        </tr>

                        <?php
                        if (!empty($basketData)) {
                            $i = 1;
                            $total = 0;
                            foreach ($basketData as $basket) {
                                ?>
                                <tr><td><?= $i ?></td><td><img src="<?php echo $basket['image_path']; ?>" width="150px" height="150px"></td><td><h2><?= $basket['vendor_name']; ?></h2></td><td><?= $basket['item_name']; ?></td><td><b><?php echo number_format($basket['item_price_per_unit'], 2) . " " . Yii::$app->params['CURRENCY_CODE']; ?></b></td><td><select id="basket_quantity" name="basket_quantity" onChange="quantity_check(this,<?= $basket['item_id'] ?>)"><?php for ($x = 1; $x <= 20; $x++) { ?>
                                                <option value="<?= $x ?>"<?= $x == $basket['basket_quantity'] ? ' selected="selected"' : '' ?>><?= $x ?></option><?php } ?>
                                        </select></td><td>15.00</td><td><a href="#" onclick="delete_basket(<?= $basket['item_id'] ?>)">Delete</a></td><td><?php echo calculate_total1($basket['basket_quantity'], $basket['item_price_per_unit']); ?></td></tr>
                                <?php
                                $calc = $basket['basket_quantity'] * $basket['item_price_per_unit'];
                                $total = $total + $calc;
                                $i++;
                            }
                        }
                        ?>
                        <tr><td colspan="8" class="td-update">Total</td><td><?= number_format($total, 2) . " " . Yii::$app->params['CURRENCY_CODE']; ?></td></tr>
                        <tr><td colspan="9" class="td-update"><div id="quantity_error"></div><a href="#" onclick="validateDeliveryArea();" id="check_out">Check out</a></td></tr>
                    </table>

                    <table>
                        <tr><td><label class="label_radio delivery_details" for="radio-0" id="0"><input class="dvy_otn" name="delivery_option" id="radio-0" value="0" type="radio">
                                    <span>Delivery <?= $customer_details['customer_id']; ?></span></label>
                            </td></tr>
                        <tr><td><?= $customer_details['customer_email'] ?></td></tr>
                        <tr><td><?= $customer_details['block'] ?></td></tr>
                        <tr><td><?= $customer_details['street'] ?></td></tr>
                        <tr><td><?= $customer_details['juda'] ?></td></tr>
                        <tr><td><?= $customer_details['customer_address'] ?></td></tr>
                        <tr><td><?= City::getCity($customer_details['area']) ?></td></tr>
                        <tr><td><?= Country::getCountry($customer_details['country']) ?></td></tr>
                        <tr><td><?= $customer_details['phone'] ?></td></tr></table>
                    <table><div class="form-group radio_chkout">
<?php foreach ($address as $key) { ?>


                                <label class="label_radio delivery_details" for="radio-<?= $key['address_id']; ?>" id="<?= $key['address_id']; ?>"><input class="dvy_otn" name="delivery_option" id="radio-<?= $key['address_id']; ?>" value="<?= $key['address_id']; ?>" type="radio">
                                    <span>Delivery <?= $key['address_id']; ?></span></label>
                                <tr><td><?= AddressType::getAddresstype($key['address_type_id']) ?></td></tr>
                                <tr><td><?= Location::getlocation($key['area_id']) ?></td></tr>
                                <tr><td><?= City::getCity($key['city_id']) ?></td></tr>
                                <tr><td><?= Country::getCountry($key['country_id']) ?></td></tr>
<?php } ?>

                            <a href="<?php echo Yii::$app->homeUrl; ?>/checkout" id="check_out"><?=Yii::t('frontend','Add Delivery Address')?></a></div>

                    </table>

                    <!-- Mobile end Here-->

                    <div class="similar_product_listing">
                        <div class="feature_product_title">
                            <h2>Similar products</h2>
                        </div>
                        <div class="feature_product_slider">
                            <div class="most_popular_slider">
                                <div class="slider_new_up">
                                    <div class="flexslider4">
                                        <div id="demo">
                                            <div class="owl-carousel" id="similar-products-slider">
                                                <?php
                                                foreach ($similiar_item as $s) {
													$out=\admin\models\image::find()
														->select(['image_path'])
														->where(['item_id'=>$s['gid']])
														->andwhere(['module_type'=>'vendor_item'])
														->orderby(['vendorimage_sort_order'])
														->asArray()
														->all();
                                                    if (!empty($out)){
                                                        $imglink = Yii::getAlias('@vendor_image/') . $out[0]['image_path'];
                                                        $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_image/') . $out[0]['image_path'];
                                                    } else {
                                                        $imglink = Yii::getAlias('@vendor_image/no_image.jpg');
                                                        $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_image/no_image.jpg');
                                                    }
                                                    ?>
                                                    <div class="item">
                                                        <div class="fetu_product_list">
                                                            <a href="<?php echo Yii::$app->homeUrl; ?>/product/<?php echo $s['slug']; ?>" title="Products" class="similar">
                                                                <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219">
    <?php if (file_exists($imglink)) { ?>
                                                                    <img src="<?php echo $baselink; ?>" alt="Slide show images" width="208" height="219">
    <?php } ?>
                                                                <div class="deals_listing_cont">
    <?= $s['vname']; ?>
                                                                    <h3><?= $s['iname']; ?></h3>
                                                                    <p><?= $s['price']; ?>KD</p>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
<?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!--product detail end-->
            </div>
            <!-- one end -->
        </div>
</section>
<!-- continer end -->
<!-- end -->


<?php

function calculate_total1($quantity, $price) {
    $total = $quantity * $price;
    return number_format($total, 2) . " " . Yii::$app->params['CURRENCY_CODE'];
}
?>
<script>

    jQuery('.label_radio').click(function () {
        setupLabel();
        deliveryid = jQuery(this).attr('id');
        jQuery.ajax({
            url: "<?php echo Yii::$app->urlManager->createAbsoluteUrl('users/customerdeliveryaddress'); ?>",
            type: "post",
            data: "deliveryid=" + deliveryid + "&_csrf=" + _csrf,
            async: false,
            success: function (data)
            {
                if (data == 1)
                {
                }
            }
        });

    });
    function setupLabel() {
        if (jQuery('.label_radio input').length) {
            jQuery('.label_radio').each(function () {
                jQuery(this).removeClass('r_on');
            });
            jQuery('.label_radio input:checked').each(function () {
                jQuery(this).parent('label').addClass('r_on');
            });
        }
        ;
    }


    function validateDeliveryArea() {
        var radios = document.getElementsByName("delivery_option");
        var formValid = false;

        var i = 0;
        while (!formValid && i < radios.length) {
            if (radios[i].checked)
                formValid = true;
            i++;
        }

        if (!formValid) {
            alert("Must check some Delivery address!");
            return formValid;
        }
        else
        {
            window.location.replace("<?php echo Yii::$app->homeUrl . '/payment'; ?>");

        }
    }

</script>

<?php
$this->registerCss("
.td-update{border:5px; text-align:right;}
");
?>
