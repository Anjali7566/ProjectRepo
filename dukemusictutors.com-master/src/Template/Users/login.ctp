<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php
    echo $this->Html->css(['blue-background', 'users/form']);
    echo $this->Html->script(['users/form']);
?>
<div class="users form">
<?= $this->Flash->render('auth') ?>
    <div class='myform user-form'>
        <?= $this->Form->create() ?>
        <div class='fields'>
        <?= 
            $this->element('users/fields', [
                'fields' => [
                    'username', 
                    'password'
                ]
            ])
        ?>
        </div>
        <?= $this->element('users/formsubmit', ['name' => 'Login']) ?>
        <?= $this->Form->end() ?>
        <p class='no-account pad'>Don’t have an account? Talk to our president about getting an invitation</p>
    </div>
</div>
