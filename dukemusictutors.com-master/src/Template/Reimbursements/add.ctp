<?php
    echo $this->Html->css(['reimbursements/style', 'users/form']);
    echo $this->Html->script(['reimbursements/add', 'reimbursements/add-too-small']);
?>
<div class="reimbursements form content">
    <?= $this->Html->link($this->Html->div('inline blue-oval') . $this->Html->div('arrow-head') . '</div>' .  h(' Cancel') . '</div>', 
            '/reimburse', 
            ['class' => 'back-arrow', 'escape' => false])
    ?> <!-- FIXME styling -->
    <div class='center-text' style="padding-bottom: 1rem;"><h2>New Reimbursement</h2></div>
    <div class='myform'>
        <?php
            echo $this->Form->create($reimbursement, ['type' => 'file']);
            $dateString = 'date_string'; 
            $volunteerString = 'volunteer_site_id'; 
            $otherRiderString = 'other_riders.user_ids';
            echo $this->element('users/fields', [
                    'fields' => [
                        $dateString,
                        $volunteerString,
                        $otherRiderString, 
                    ],
                    'specialNames' => [
                        $dateString => 'Date',
                        $volunteerString => 'Site',
                        $otherRiderString => 'Other Riders',
                    ],
                    'fieldOptions' => [
                        $volunteerString => [
                            'type' => 'radio', 
                            'options' => $volunteerSites,
                        ],
                        $otherRiderString => [
                            'type' => 'select', 
                            'multiple' => true, 
                            'options' => $extraRiders,
                        ],

                    ],
                ]);
        ?>
        <?= $this->Form->create(null) ?>
        <div class='pad'> <!-- Stolen from field element -->
            <div class='col-md-4 col-md-offset-1'>
                <h3 style="text-align: right;">
                    Number of Receipts
                </h3>
            </div>
            <div class='col-md-5'>
            <?php 
                $values = [];
                for ($k = 1; $k <= count($documents); $k++)
                    $values[$k] = $k;
                echo $this->Form->radio('numreceipts', $values, [
                    'value' => 1,
                ]);
            ?>
            </div>
        </div>
        <?php
            for ($k = 0; $k < count($documents); $k++) {
                $num = $k + 1;
                echo $this->Html->div('document-receipt', null, ['id' => 'document-receipt-' . $num]);
                    echo $this->Form->create($documents[$k]);
                    $docString = 'documents.' . $k . '.filestuff';
                    echo $this->element('users/fields', [
                        'fields' => [
                            $docString,
                        ],
                        'specialNames' => [
                            $docString => 'Receipt ' . $num
                        ],
                        'fieldOptions' => [
                            $docString => [
                                'type' => 'file', 
                                'class' => 'document-input',
                                'id' => 'document-input-' . $num, //FIXME remove??
                                'required' => false,
                            ],
                        ],
                    ]);
                    echo $this->Form->create($receipts[$k]);
                    $receiptString = 'receipts.' . $k . '.amount';
                    echo $this->element('users/fields', [
                        'fields' => [
                            $receiptString,
                        ],
                        'specialNames' => [
                            $receiptString => 'Amount'
                        ],
                        'fieldOptions' => [
                            $receiptString => [
                                'class' => 'receipt-input', 
                                'id' => 'receipt-input-' . $num, //FIXME remove??
                                'required' => false,
                            ],
                        ],
                    ]);
                echo "</div>";
            }
        ?>
        <div class='center-text'>
            <?=  $this->Form->button(__('Submit'), ['type' => 'submit', 'class' => 'submit', 'formnovalidate' => true]); ?>
        </div>
        <?= $this->Form->end() ?>
        <div class='center-text' style='margin-bottom: 1rem;'><h4>You can submit one reimbursement with up to four receipts per purchase date.</h4></div>
    </div>
    <p id='too-small'>The browser window is too small. Please expand the window on a computer to continue.</p>
</div>