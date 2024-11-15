<div class='accounts-container'>
    <h2><?= h($title) ?></h2>
    <?php foreach ($buttons as $k => $button): ?>
    <?php 
        $classes = 'big-wrapper col-md-6';
        if ($k === count($buttons) - 1 && $k % 2 === 0)
            $classes = 'big-wrapper col-md-6 col-md-offset-3';
        echo $this->Html->div($classes);
    ?>
        <a href=<?= $this->Url->build(['controller' => $button['controller'], 'action' => $button['action']]); ?> class='mybutton'>
            <div class='button-wrapper'><p><?= $button['name'] ?></p></div>
        </a>
    </div>
    <?php endforeach; ?>
</div>