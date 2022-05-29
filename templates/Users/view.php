<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Pseudo') ?></th>
                    <td><?= h($user->pseudo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
            </table>
            <div class="related" style="margin-top: 30px;">
                <h4><?= __('My Listings') ?></h4>
                <?php if (!empty($user->listings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Private') ?></th>
                            <th><?= __('Nb Tasks') ?></th>
                            <th><?= __('Style') ?></th>
                            <th><?= __('Parent') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->listings as $listings) : ?>
                        <tr>
                            <td><?= h($listings->name) ?></td>
                            <td><?= empty(h($listings->private)) ? '<i class="fa-solid fa-lock-open"></i>' : '<i class="fa-solid fa-lock"></i>' ?></td>
                            <td><?= h($listings->get("nbTasks")) ?></td>
                            <td><?= h($listings->style) ?></td>
                            <td><?= $listings->has('parent_listing') ? $this->Html->link($listings->parent_listing->name, ['controller' => 'Listings', 'action' => 'view', $listings->parent_listing->id]) : 'No parent' ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Listings', 'action' => 'view', $listings->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Listings', 'action' => 'edit', $listings->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Listings', 'action' => 'delete', $listings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $listings->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
