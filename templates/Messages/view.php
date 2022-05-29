<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message $message
 */
?>
<div class="row">
    <aside class="column">
        <h4 class="heading"><?= __('Messages') ?></h4>
        <div class="messages_list">
            <?php foreach ($messages as $key => $m) : ?>
                <?php
                    $class = 'message_each' . ($m->id == $message->id ? ' active ' : '') . ($m->sender_id == $this->request->getAttribute('identity')->id ? ' sent ' : ' received ');
                    $isSent = $m->sender_id == $this->request->getAttribute('identity')->id;
                    $content = '
                        <span class="object">' . ($isSent ? '<i class="fa-solid fa-share"></i> ' : "") .h($m->subject).'</span>
                        <p>'.h($m->content).'</p>
                        <span class="message_footer">'.h($m->created).' | '. ($isSent ? $m->receiver->pseudo : $m->sender->pseudo ) .'</span>
                    ';
                ?>
                <?= $this->Html->link($content, ['action' => 'view' , $m->id], ['class' => $class, 'escape' => false]) ?>
            <?php endforeach; ?>
        </div>
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Message'), ['action' => 'edit', $message->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Message'), ['action' => 'delete', $message->id], ['confirm' => __('Are you sure you want to delete # {0}?', $message->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Messages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Message'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="messages view content">
            <h3>From : <?= h($message->sender->pseudo) ?>, To : <?= h($message->receiver->pseudo) ?></h3>
            <table>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($message->subject) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sent on') ?></th>
                    <td><?= h($message->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Read by') ?></th>
                    <td><?= h($message->readby) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Content') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($message->content)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
