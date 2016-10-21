<?php

use yii\db\Migration;

/**
 * Handles the creation for table `whitebook_contacts`.
 */
class m161021_063312_create_whitebook_contacts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        return Yii::$app->db->createCommand("
        CREATE TABLE `whitebook_contacts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `contact_name` varchar(50) CHARACTER SET latin1 NOT NULL,
        `contact_email` varchar(50) CHARACTER SET latin1 NOT NULL,
        `contact_phone` varchar(25) CHARACTER SET latin1 NOT NULL,
        `subject` varchar(250) CHARACTER SET latin1 NOT NULL,
        `message` text CHARACTER SET latin1 NOT NULL,
        `created_by` int(11) NOT NULL,
        `modified_by` int(11) NOT NULL,
        `created_datetime` datetime NOT NULL,
        `modified_datetime` datetime NOT NULL,
        `trash` enum('Default','Deleted') CHARACTER SET latin1 NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('whitebook_contacts');
    }
}
