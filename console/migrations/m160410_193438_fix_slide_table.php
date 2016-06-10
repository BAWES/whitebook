<?php

use yii\db\Migration;

class m160410_193438_fix_slide_table extends Migration
{
    public function up()
    {
        $this->execute("UPDATE whitebook_slide SET modified_datetime=NOW()");
        $this->alterColumn("whitebook_slide", "slide_image", $this->string());
        $this->alterColumn("whitebook_slide", "slide_video_url", $this->string());
    }

    public function down()
    {
        echo "m160410_193438_fix_slide_table cannot be reverted.\n";

        return false;
    }
}
