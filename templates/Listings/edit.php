<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Listing $listing
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $parentListings
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $listing->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $listing->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Listings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="listings form content">
            <?= $this->Form->create($listing) ?>
            <fieldset>
                <legend><?= __('Edit Listing') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('private');
                    echo $this->Form->control('style', ['options' => ['checkbox' => 'Checkbox', 'bullet' => 'Bullet']]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
