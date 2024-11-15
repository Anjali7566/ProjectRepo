<div class='pad'>
    <div class='col-md-4 col-md-offset-1'>
        <h3 class='field-name'>
            <?= $prettyName ?>
        </h3>
    </div>
    <div class='col-md-5'>
    <?php 
        $options['label'] = false;
        echo $this->Form->control($name, $options);
    ?>
    </div>
</div>