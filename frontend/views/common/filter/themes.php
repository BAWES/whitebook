<div class="panel panel-default" id="top_panel_theme">
    <div class="panel-heading">
        <p>
            <?= Yii::t('frontend', 'Themes') ?>
            <a href="javascript:void(0)" style="<?=(sizeof($selected_themes) > 0)?'display:inline':'';?>" class="filter-clear" id="filter-clear-date" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a>        
        </p>
    </div>
    <div class="panel-collapse">
        <div class="panel-body">
            <select name="themes[]" id="theme_filter" class="selectpicker" data-title="<?= Yii::t('frontend', 'All') ?>" data-live-search="true" data-size="10" multiple>

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
