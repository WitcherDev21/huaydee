<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 8/27/2018
 * Time: 8:58 PM
 */

class m180827_205850_add_field_type_game_comminssion extends Migration
{
    const COMMISSION_BLACKRED_TABLE_NAME = '{{%commission_blackred}}';
    const COMMISSION_TRANSACTION_BLACKRED_TABLE_NAME = '{{%commission_transection_blackred}}';

    public function safeUp()
    {
        $this->execute('CREATE TABLE '.self::COMMISSION_BLACKRED_TABLE_NAME.' (
  `id` int(11) NOT NULL,
  `no` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `create_by` int(11) NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commission`
--
ALTER TABLE '.self::COMMISSION_BLACKRED_TABLE_NAME.'
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE '.self::COMMISSION_BLACKRED_TABLE_NAME.'
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commission`
--
ALTER TABLE '.self::COMMISSION_BLACKRED_TABLE_NAME.'
  ADD CONSTRAINT `commission_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;');

        $this->execute('CREATE TABLE '.self::COMMISSION_TRANSACTION_BLACKRED_TABLE_NAME.' (
  `id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `reciver_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `create_by` int(11) NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `reason_action_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commission_transection`
--
ALTER TABLE '.self::COMMISSION_TRANSACTION_BLACKRED_TABLE_NAME.'
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commission_transection`
--
ALTER TABLE '.self::COMMISSION_TRANSACTION_BLACKRED_TABLE_NAME.'
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
    }

    public function safeDown()
    {
        $this->dropTable(self::COMMISSION_BLACKRED_TABLE_NAME);
        $this->dropTable(self::COMMISSION_TRANSACTION_BLACKRED_TABLE_NAME);
    }
}