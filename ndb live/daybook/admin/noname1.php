<?php
  ALTER TABLE `payment_plan` ADD `update_date` DATETIME NOT NULL AFTER `create_date` ;
  ALTER TABLE `attach_file` ADD `old_id` INT( 10 ) NOT NULL ;
?>
