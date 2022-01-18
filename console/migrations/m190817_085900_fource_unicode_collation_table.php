<?php
use yii\db\Migration;
class m190817_085900_fource_unicode_collation_table extends Migration
{
    public function up()
    {
        $db = Yii::$app->getDb();
        // get the db name
        $schema = $db->createCommand('select database()')->queryScalar();
        // get all tables
        $tables = $db->createCommand(
            'SELECT table_name FROM information_schema.tables WHERE table_schema=:schema AND table_type = "BASE TABLE"',
            [':schema' => $schema]
        )->queryAll();
        $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
        // Alter the encoding of each table
        foreach ($tables as $table) {
            $tableName = $table['table_name'];
            $db->createCommand("ALTER TABLE `$tableName` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        }
        $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
    }
    public function down()
    {
        // We're not converting encoding back...
        return true;
    }
}