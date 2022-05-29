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
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'all.min', 'style']) ?>
    <?= $this->Html->script(['index']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script>
        const csrfToken = "<?= h($this->request->getAttribute('csrfToken')); ?>";
    </script>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>"><span>Logi</span>Q</a>
        </div>
        <div class="top-nav-links">
            <!-- If user is logged, display logout button, if not, display login button -->
            <!-- Link to StatsController -->

            <?= $this->Html->link('<i class="fa-solid fa-arrow-trend-up"></i>', ['controller' => 'Stats', 'action' => 'index'], ['escape' => false]) ?>
            <?php if (empty($this->request->getAttribute('identity'))): ?>
                    <?= $this->Html->link('Login', ['controller' => 'Users', 'action' => 'login']) ?>
                <?php else: ?>
                    <?php
                    $unreadNotifications = array_filter($notifications->toArray(), function ($notification) {
                        return $notification->readby === null;
                    });
                    ?>
                    <a href="#" id="notifications__trigger" class="<?= count($unreadNotifications) == 0 ? "" : "active" ?>"><i class="fa-solid fa-bell"></i></a>
                    <?= $this->element('notifs/all') ?>
                    <?= $this->Html->link('<i class="fa-solid fa-envelope"></i>', ['controller' => 'Messages', 'action' => 'index'], ['id' => 'messages__button', 'escape' => false, 'title' => 'Messages', 'class' => $nbMessages > 0 ? "active" : ""]) ?>
                    <?= $this->Html->link('<i class="fa-solid fa-user"></i>', ['controller' => 'Users', 'action' => 'view', $this->request->getAttribute('identity')->id], ['escape' => false, 'title' => 'Profile']) ?>
                    <?= $this->Html->link('<i class="fa-solid fa-right-from-bracket"></i>', ['controller' => 'Users', 'action' => 'logout'], ['escape' => false, 'title' => "Logout"]) ?>
            <?php endif; ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
