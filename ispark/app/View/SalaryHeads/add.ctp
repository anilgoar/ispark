
<!-- File: /app/View/UserType/index.ctp -->
<h1><?php echo $this->fetch('title'); ?></h1>
<?php echo $this->fetch('content'); ?>

<!-- app/View/Users/add.ctp -->
<div class="users form">
<?php echo $this->Form->create('Addbranch'); ?>
    <fieldset>
        <legend><?php echo __('Add Branch'); ?></legend>
        <?php echo $this->Form->input('branch_name');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>