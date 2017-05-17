<div class="panel panel-default" id="top_panel_theme">
    <div class="panel-heading">
        <p>
            <?= Yii::t('frontend', 'Themes') ?>     
        </p>
    </div>
    <div class="panel-collapse">
        <div class="panel-body">
            <select name="themes[]" id="theme_filter" class="selectpicker" data-title="<?= Yii::t('frontend', 'All') ?>" data-live-search="true" data-size="10">

            <option value="all" <?= in_array('all', $selected_themes)?'selected':'' ?>><?= Yii::t('frontend', 'All') ?></option>

            <?php 
            foreach ($themes as $key => $value) 
            { 
                $value = (isset($value['themeDetail'])) ? $value['themeDetail'] : $value;
                
                $theme_name = \common\components\LangFormat::format(strtolower($value['theme_name']),strtolower($value['theme_name_ar'])); 

                $selected = (in_array($value['slug'], $selected_themes)) ? 'selected' : '';
                
                ?>
                <option value="<?= $value['slug'] ?>" <?= $selected ?>><?= $theme_name ?></option>
            <?php 
            } ?>
            </select>
        </div>
    </div>
</div>
