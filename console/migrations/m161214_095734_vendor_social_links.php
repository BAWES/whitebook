<?php

use yii\db\Migration;

class m161214_095734_vendor_social_links extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor', 'vendor_googleplus');
        $this->dropColumn('whitebook_vendor', 'vendor_skype');

        $this->addColumn('whitebook_vendor', 'vendor_instagram_text', $this->string(100)->after('vendor_instagram'));
        $this->addColumn('whitebook_vendor', 'vendor_twitter_text', $this->string(100)->after('vendor_twitter'));
        $this->addColumn('whitebook_vendor', 'vendor_facebook_text', $this->string(100)->after('vendor_facebook'));
        $this->addColumn('whitebook_vendor', 'vendor_youtube', $this->string(100)->after('vendor_instagram_text'));
        $this->addColumn('whitebook_vendor', 'vendor_youtube_text', $this->string(100)->after('vendor_youtube'));
    }
}
