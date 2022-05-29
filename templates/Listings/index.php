<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Listing[]|\Cake\Collection\CollectionInterface $listings
 */
?>

<?= $this->Html->link(__('New Listing'), ['action' => 'add'], ['class' => 'button']) ?>
<?php if (!empty($this->request->getAttribute('identity'))) : ?>
    <div class="listings index content">
        <h3><?= __('My Listings') ?></h3>
        <?php if ($privateListings->count() !== 0) : ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Owner') ?></th>
                            <th><?= __('Private') ?></th>
                            <th><?= __('Nb Tasks') ?></th>
                            <th><?= __('Style') ?></th>
                            <th><?= __('Parent') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($privateListings as $listing) : ?>
                            <tr>
                                <td><?= h($listing->name) ?></td>
                                <td><?= h($listing->user->pseudo) ?></td>
                                <td><?= empty(h($listing->private)) ? '<i class="fa-solid fa-lock-open"></i>' : '<i class="fa-solid fa-lock"></i>' ?></td>
                                <td><?= h($listing->get("nbTasks")) ?></td>
                                <td><?= ucfirst(h($listing->style)) ?></td>
                                <td><?= $listing->has('parent_listing') ? $this->Html->link($listing->parent_listing->name, ['controller' => 'Listings', 'action' => 'view', $listing->parent_listing->id]) : 'No parent' ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), ['action' => 'view', $listing->id], ['escape' => false, 'title' => 'View']) ?>
                                    <?= $this->Html->link(__('<i class="fa-solid fa-pen-to-square"></i>'), ['action' => 'edit', $listing->id], ['escape' => false, 'title' => 'Edit']) ?>
                                    <?= $this->Form->postLink(__('<i class="fa-solid fa-copy"></i>'), ['action' => 'copy', $listing->id], ['confirm' => __('Are you sure you want to copy # '.$listing->name.'?', $listing->id), 'escape' => false, 'title' => 'Copy']) ?>
                                    <?= $this->Form->postLink(__('<i class="fa-solid fa-trash"></i>'), ['action' => 'delete', $listing->id], ['confirm' => __('Are you sure you want to delete # '.$listing->name.'?', $listing->id), 'escape' => false, 'title' => 'Delete']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="message error"><?= __('You have no private listings.') ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="listings index content" style="margin-top: 20px;">
    <h3><?= __('Listings') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('owner') ?></th>
                    <th><?= $this->Paginator->sort('private') ?></th>
                    <th><?= $this->Paginator->sort('nbTasks') ?></th>
                    <th><?= $this->Paginator->sort('style') ?></th>
                    <th><?= $this->Paginator->sort('parent_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $listing) : ?>
                    <tr>
                        <td><?= h($listing->name) ?></td>
                        <td><?= h($listing->user->pseudo) ?></td>
                        <td><?= empty(h($listing->private)) ? '<i class="fa-solid fa-lock-open"></i>' : '<i class="fa-solid fa-lock"></i>' ?></td>
                        <td><?= h($listing->get("nbTasks")) ?></td>
                        <td><?= ucfirst(h($listing->style)) ?></td>
                        <td><?= $listing->has('parent_listing') ? $this->Html->link($listing->parent_listing->name, ['controller' => 'Listings', 'action' => 'view', $listing->parent_listing->id]) : 'No parent' ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), ['action' => 'view', $listing->id], ['escape' => false, 'title' => 'View']) ?>
                            <?= $this->Html->link(__('<i class="fa-solid fa-pen-to-square"></i>'), ['action' => 'edit', $listing->id], ['escape' => false, 'title' => 'Edit']) ?>
                            <?= $this->Form->postLink(__('<i class="fa-solid fa-copy"></i>'), ['action' => 'copy', $listing->id], ['confirm' => __('Are you sure you want to copy # '.$listing->name.'?', $listing->id), 'escape' => false, 'title' => 'Copy']) ?>
                            <?= $this->Form->postLink(__('<i class="fa-solid fa-trash"></i>'), ['action' => 'delete', $listing->id], ['confirm' => __('Are you sure you want to delete # '.$listing->name.'?', $listing->id), 'escape' => false, 'title' => 'Delete']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>