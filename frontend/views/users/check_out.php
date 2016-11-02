<?php

use yii\helpers\Url;

$this->title = 'Delivery Address | Whitebook';
?>
<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1><?php echo Yii::t('frontend', 'Delivery Address'); ?></h1>
        </div>

        <div class="account_setings_sections">
            <div class="col-md-2 hidde_res"></div>
            <div class="col-md-8">
                <div class="accont_informations">
                    <div class="accont_info">
                        <div class="account_title">
                            <div id="acc_status"></div>
                            <h4><?php echo Yii::t('frontend', 'Address Info'); ?></h4>
                        </div>
                        <div class="account_form">
                            <div class="bs-example" data-example-id="basic-forms">
                                <form method="POST" action="<?php echo Yii::$app->homeUrl; ?>/delivery" name="delivery" id="delivery" name="account_setting">


                                    <div class="address_informations">

                                        <div data-example-id="basic-forms" class="bs-example">


                                            <div class="col-md-6 paddingright0">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Address Type</label>
                                                    <div class="select_boxes">
                                                        <select class="selectpicker " data-style="btn-primary" id="address_type" name="address_type" >
                                                            <option value="">Select Address Type</option>
                                                            <?php
                                                            foreach ($addresstype as $key => $val) {
                                                                echo '<option value="' . $key . '">' . $val . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <div id="address_type_err" class="error"></div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6 paddingleft0">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Country</label>
                                                    <select class="selectpicker" data-style="btn-primary" style="display: none;" id="country" name="country">
                                                        <option value="">Select country</option>
                                                        <?php
                                                        foreach ($loadcountry as $key => $val) {
                                                            echo '<option value="' . $key . '">' . $val . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="country_er"  class="error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 paddingright0">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Area</label>
                                                    <div class="select_boxes">
                                                        <select class="selectpicker " data-style="btn-primary" id="city" name="city" >
                                                            <option value="">Select city</option>
                                                            <?php
                                                            foreach ($loadcity as $key => $val) {

                                                                echo '<option value="' . $key . '">' . $val . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <div id="city_er" class="error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 paddingright0">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Address Area</label>
                                                    <div class="select_boxes">
                                                        <select class="selectpicker " data-style="btn-primary" id="area" name="area">
                                                            <option value="">Select Area</option>
<?php
foreach ($area as $key => $val) {
    echo '<option value="' . $key . '">' . $val . '</option>';
}
?>
                                                        </select>
                                                        <div id="area_er" class="error"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 paddingleft0">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Address Details</label>
                                                    <input type="text" placeholder="Enter your address name here" id="address_data" name="address_data" class="form-control required" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="submitt_buttons">

                                        <button class="btn btn-default" type="button" title="Save Changes" id="delivery_address" name="delivery_address"> Delivery Address</button>
                                    </div>
                                </form>
                            </div>
                            <div id="login_loader" style="display: none;"><img src="<?php echo Url::to("@web/images/ajax-loader.gif"); ?>" title="Loader"></div>
                            <div class="save_address">


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 hidde_res"></div>
            </div>
        </div>

</section>



<!-- continer end -->

<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
<script type="text/javascript">
    $('#delivery_address').on('click', function () {

        jQuery.noConflict();
        var address_type = jQuery('#address_type').val().length;
        var country = jQuery('#country').val().length;
        var city = jQuery('#city').val().length;
        var area = jQuery('#area').val().length;
        var address_data = jQuery('#address_data').val();
        var i = j = K = l = m = 0;
        if (address_type == 0)
        {
            jQuery('#address_type_err').show();
            jQuery('#address_type_err').html('Select Address Type');
        }
        else
        {
            jQuery('#address_type_err').hide();
            i = 1;
        }
        if ((country == 0) && (country == ''))
        {
            jQuery('#country_er').html('Select country');
        }
        else
        {
            jQuery('#country_er').hide();
            l = 1;
        }
        if ((city == 0) && (city == ''))
        {
            jQuery('#city_er').html('Select City');
        }
        else
        {
            jQuery('#city_er').hide();
            j = 1;
        }
        if ((area == 0) && (area == ''))
        {
            jQuery('#area_er').html('Select Area');
        }
        else
        {
            jQuery('#area_er').hide();
            m = 1;
        }

        if (jQuery('#delivery').valid() && i == 1 && j == 1 && l == 1 && m == 1)
        {
            jQuery('#login_loader').show();

            var area1 = jQuery('#area').val();

            jQuery.ajax({
                url: "<?php echo Yii::$app->urlManager->createAbsoluteUrl('users/delivery_address'); ?>",
                type: "post",
                data: "country=" + jQuery('#country').val() + "&city=" + jQuery('#city').val() + "&area=" + jQuery('#area').val() + "&address_type=" + jQuery('#address_type').val() + "&address_data=" + address_data,
                async: false,
                success: function (data)
                {
                    if (data == 1)
                    {
                        jQuery('#login_loader').hide();
                        jQuery('#login_success').modal('show');
                        jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" >Delivery informastion added successfully!</span>');
                        window.setTimeout(function () {
                            window.location.replace("<?php echo Yii::$app->homeUrl . '/basket'; ?>")
                        }, 2000)
                    }
                }
            });

        }
    });


    function validateEmail(mail)
    {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form1.useremail.value))
        {
            return (1);
        }

        return (0);
    }
    jQuery("#phone_detail1").keypress(function (e) {
//if the letter is not digit then display error and don't type anything
        if (e.which != 43 && e.which != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
//display error message
            jQuery("#phone_detail1").find('.error').html('Contact number digits only+.');
            return false;
        }
    });

    jQuery("#mobile_number_detail").keypress(function (e) {
//if the letter is not digit then display error and don't type anything
        if (e.which != 43 && e.which != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
//display error message
            jQuery("#mobile_number_detail").find('.error').html('Contact number digits only+.');
            return false;
        }
    });

</script>


<script type="text/javascript">
    $(function () {
        $("#country").change(function () {
            jQuery('#country_er').html('');
            var csrfToken = jQuery('#_csrf').val();
            var country_id = jQuery('#country').val();
            var path = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('/admin/location/city'); ?> ";
            jQuery.ajax({
                type: 'POST',
                asynch: false,
                url: path, //url to be called
                data: {country_id: country_id, _csrf: csrfToken}, //data to be send
                success: function (data) {
                    jQuery('#city').html(data);
                    jQuery('#city').selectpicker('refresh');
                }
            })
        });



        $("#city").change(function () {
            alert(2);
            jQuery('#city_er').html('');
            var csrfToken = jQuery('#_csrf').val();
            var city_id = jQuery('#city').val();
            var path = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('/admin/location/area'); ?> ";
            jQuery.ajax({
                type: 'POST',
//async: false,
                url: path, //url to be called
                data: {city_id: city_id, _csrf: csrfToken}, //data to be send
                success: function (data) {
                    alert(989);
                    alert(data);
                    jQuery('#area').html(data);
                    jQuery('#area').selectpicker('refresh');
                }
            })
        });



        $("#area").change(function () {
            jQuery('#area_er').html('');
        });

    });
</script>


<?php $this->registerCss("
#acc_status{color:green;margin-bottom: 10px;}
#login_loader{text-align:center;margin-bottom: 10px;}
.msg-success{margin-top: 5px; width: 320px; float: left; text-align: left;}
");