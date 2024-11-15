<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement $reimbursement
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $reimbursement->id],
                ['confirm' => __('Are you sure you want to delete this reimbursement?')]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Reimbursements'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Volunteer Sites'), ['controller' => 'VolunteerSites', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Volunteer Site'), ['controller' => 'VolunteerSites', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reimbursements form large-9 medium-8 columns content">
    <?= $this->Form->create($reimbursement) ?>
    <fieldset>
        <legend><?= __('Edit Reimbursement') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('volunteer_site_id', ['options' => $volunteerSites]);
            echo $this->Form->control('date');
            echo $this->Form->control('submitted');
            echo $this->Form->control('deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
