<?php $this->title = 'Plan | Whitebook'; ?>
<link href="<?php echo Yii::$app->params['FONT_PATH']; ?>/flaticon/plan/flaticon.css" rel="stylesheet">
<section id="events_whitebook">
    <div class="top_sections_titles">
        <div class="container">
            <div class="col-md-12">
                <div class="common_titles">
                    <div class="text-center"><span class="yellow_top"></span> </div> 
                    <h1> <b><?php echo Yii::t('frontend', 'PLAN'); ?></b></h1>
                    <p class="col-md-12 text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry,<br>  Lorem Ipsum has been the industry's</p>
                </div>
            </div>
        </div>
    </div>          
    <div id="exTab1" class="container event_middle_tab"> 
        <div class="col-md-12">
            <div class="event_detials_plan">
                <?php foreach ($category as $c) { ?>
                    <div class="col-md-3">
                        <div class="paln_item2">
                            <?php $category_url = $c['category_url']; ?>
                            <a href="<?php echo Yii::$app->params['BASE_URL'] . '/category/' . $category_url . '.html'; ?>" title="Venue">
                                <span class="plan">
                                    <?php if (file_exists(Yii::$app->params['DOCROOT'])) { ?>
                                        <img src="">
                                    <?php } else {
                                        
                                    } ?>
                                </span>
                                <span><?php echo $c['category_name']; ?></span>
                            </a>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>
    </div>
</section>
