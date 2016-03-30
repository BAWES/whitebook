<?php $this->title = 'Directory | Whitebook'; ?>
<link href="<?php echo Yii::$app->params['FONT_PATH']; ?>/flaticon/plan/flaticon.css" rel="stylesheet">
<section id="directory_whitebook">
    <div class="top_sections_titles">
        <div class="container">
            <div class="col-md-12">
                <div class="common_titles">
                    <div class="text-center"><span class="yellow_top"></span> </div> 
                    <h1> <b><?php echo Yii::t('frontend', 'directory'); ?></b></h1>
                    <p class="col-md-12 text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry,
                        <br> Lorem Ipsum has been the industry's</p>
                </div>
            </div>
        </div>
    </div>   
    <!-- Directory --> 
    <div class="top_sections_titles_new">
        <div class="container">
            <div class="col-md-12">
                <div class="common_titles">
                    <div class="text-center"><span class="yellow_top"></span> </div> 
                    <h1>MY <b> WHITE </b>Book</h1>
                    <div class="botton_dwon"><button type="button" class="btn btn-default btn-lg active"><i class="glyphicon glyphicon-chevron-down"></i></button></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container ">
        <div class="col-md-12 col-xs-12 common_directry">
            <!-- Filter left -->
            <div class="direct_left col-md-3 col-xs-3 padding8">
                <div class="filter_section_left">
                    <div class="filter_section_categories">
                        <span class="cat_titles"><?php echo Yii::t('frontend', 'categories'); ?></span>
                        <ul class="directory_menu">
                            <?php foreach ($category as $c) { ?>
                                <li><a href="<?php echo Yii::$app->params['BASE_URL']; ?>/directory/<?php echo $c['category_url']; ?>.html" title="<?php echo $c['category_name']; ?>"><p class="venue_icon"></p><span><?php echo $c['category_name']; ?></span></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Filter left end -->

            <div class="direct_ryt col-md-9 col-xs-9  padding8">
                <div class="col-md-4 col-xs-4">
                    <?php foreach ($first_letter as $f) {
                        ?>
                        <div class="directory_list_cat">
                            <h2><?php echo $f; ?></h2>
                            <ul><?php
                                foreach ($directory as $d) {
                                    $first_letter = strtoupper(substr($d['vendor_name'], 0, 1));
                                    if ($first_letter == $f) {
                                        ?>
                                        <li><a href="<?php echo Yii::$app->params['BASE_URL']; ?>/vendor/<?php echo $d['vendor_contact_name']; ?>.html" title="<?php echo strtoupper($d['vendor_name']); ?>"><?php echo strtoupper($d['vendor_name']); ?></a></li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    <?php } ?>

                </div> <!-- 3 ends -->
            </div>
            <!-- directory ends -->
        </div>
    </div> <!-- container ends-->  

</section>
