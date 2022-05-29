<div class="users form content">
    <?= $this->Form->create($user, ['controller' => 'Users' ,'action' => 'login']) ?>
    <fieldset>
        <legend><?= __('Please enter your pseudo and password to log in') ?></legend>
        <?= $this->Form->control('pseudo') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>

<div class="users form content" style="margin-top: 30px;">
    <?= $this->Form->create($user, ['controller' => 'Users', 'action' => 'add']) ?>
    <fieldset>
        <legend><?= __('Register') ?></legend>
        <?php
        echo $this->Form->control('pseudo');
        echo $this->Form->control('password');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>