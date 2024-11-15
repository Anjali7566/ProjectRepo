<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Duke Music Tutors';
?>
<!DOCTYPE html>
<html id="myhtml">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?> 
    <?= 
        $this->Html->css([
            'base.css', 
            //'style.css',
            'mystyle.css', 
            'nav.css',
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css',
            "https://fonts.googleapis.com/css?family=Encode+Sans:400,700&display=swap",
        ]) 
    ?> 
    <?= 
        $this->Html->script([
            'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js',
            'nav',
            'resize',
        ])
    ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="nav-height white">
		<div id="nav-logo-wrapper" class="transparent">
			<a href=<?= $this->Url->build(['controller' => 'Home', 'action' => 'index']) ?>><img id="nav-logo" src=<?= $this->Url->image('dmt-logo.jpg') ?> alt="Logo"></a>
        </div>
        <div class='nav-links'>
            <!-- <div class="nav-link-wrapper transparent col-xs-3">
                <= $this->Html->link('Team',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <!-- <div class="nav-link-wrapper transparent col-xs-3">
                <= $this->Html->link('Photos',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <!-- <div class="nav-link-wrapper transparent col-xs-3">
                <= $this->Html->link('Blogs',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <div class="nav-link-wrapper transparent col-xs-4">
                <?= $this->Html->link('Home',
                        ['controller' => 'Home', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
            <div class="nav-link-wrapper transparent col-xs-4">
                <?= $this->Html->link('Reimbursements',
                        ['controller' => 'Reimbursements', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
            <div class="nav-link-wrapper transparent col-xs-4">
                <?= $this->Html->link('Account',
                        ['controller' => 'Users', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
         </div>
	</nav>
    <div id="nav-space-placeholder" class="nav-height"></div>
    <?= $this->Flash->render() ?>
    <div class="container clearfix main-content"> <!-- not sure if main-content will clash with container and clearfix--> 
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
