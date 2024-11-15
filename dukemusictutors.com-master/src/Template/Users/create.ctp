<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Location $location
 */
?>

<?php
    echo $this->Html->css(['blue-background', 'users/form']);
    echo $this->Html->script(['users/form']);
?>
<div class="users form">
    <div class='myform user-form'>
        <h2>New Account</h2></h2>
        <?= $this->Form->create($user) ?>
        <div class='fields'>
        <?=
            $this->element('users/fields', [
                'fields' => [
                    'username', 
                    'password', 
                    'first_name', 
                    'last_name', 
                    'unique_id'
                ],
                'specialNames' => [
                    'username' => 'email'
                ],
                'fieldOptions' => [
                    'unique_id' => ['type' => 'text'],
                ],
            ])
        ?>
        </div>
        <?= $this->Form->create($location) ?>
        <div>
            <h2>What's the best address to send reimbursement checks to?</p>
        </div>
        <div class='fields'>
        <?=
            $this->element('users/fields', [
                'fields' => [
                    'number', 
                    'road', 
                    'city', 
                    'state', 
                    'zip', 
                    'po_box'
                ],
                'optionalFields' => ['po_box']
            ])
        ?>
        </div>
        <?= $this->element('users/formsubmit', ['name' => 'Create Account']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
