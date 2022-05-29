<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Listing $listing
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Listing'), ['action' => 'edit', $listing->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Listing'), ['action' => 'delete', $listing->id], ['confirm' => __('Are you sure you want to delete # {0}?', $listing->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Listings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Listing'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <p id="error_message" class="message error hidden">You are not authorized to do that.</p>
        <div class="listings view content">
            <h3><?= h($listing->name) ?> <?= empty(h($listing->private)) ? '<i class="fa-solid fa-lock-open"></i>' : '<i class="fa-solid fa-lock"></i>' ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($listing->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Owner') ?></th>
                    <td><?= !empty($listing->user->pseudo) ? $this->Html->link($listing->user->pseudo, ['controller' => 'Users', 'action' => 'view', $listing->user->id]) : '' ?></td>
                </tr>
                <?php if (!empty($listing->parent_listing->name)) : ?>
                    <tr>
                        <th><?= __('Parent Listing') ?></th>
                        <td><?= $this->Html->link($listing->parent_listing->name, ['controller' => 'Listings', 'action' => 'view', $listing->parent_listing->id]) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (strtolower($listing->style) == "checkbox") : ?>
                    <tr>
                        <th><?= __('Advancement') ?></th>
                        <td><span id="advancement"><?= h($listing->get("percentage")) ?></span>%</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Si la liste n'est pas vide on les affiche -->
        <div style="margin-top: 20px;">
            <?php if (count($listing->child_listings) == 0) : ?>
                <p class="message error">La liste est vide</p>
            <?php else : ?>
                <!-- Affichage des éléments de la liste -->
                <div class="listings view content">
                    <ol>
                        <?php foreach ($listing->child_listings as $childListings) : ?>
                            <li data-id="<?= $childListings->id ?>">
                                <span class="element_text"><?= $childListings->element ?></span>
                                <?= $this->Form->input("Element", ["type" => "text", "class" => "element_input hidden", "value" => $childListings->element]) ?>
                                <?= $this->Form->input("Deadline", ["type" => "datetime-local", "class" => "deadline_input hidden", "value" => $childListings->get('deadlineInput')]) ?>
                                <span class="deadline <?= strtotime($childListings->deadline) < time() ? "outdated" : "" ?>"><?= $childListings->get("deadlineFr") ?></span>
                                <div class="items_actions">
                                    <?= $listing->style == "checkbox" ? $this->Form->checkbox("Completed", ["onchange" => "saveItemStatus(this)", "checked" => $childListings->completed]) : "" ?>
                                    <a class="edit_items">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?= $this->Html->link('<i class="fa-solid fa-trash"></i>', ['controller' => 'Items', 'action' => 'delete', $childListings->id], ['escape' => false]) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>
        </div>
        <!-- Formulaire d'ajout des items dans une liste via la méthode add -->
        <div class="content view" style="margin-top: 20px;">
            <h3>Add an item</h3>
            <?= $this->Form->create($item, ['url' => ['controller' => 'Items', 'action' => 'add', $listing->id]]) ?>
            <?= $this->Form->control('element') ?>
            <?= $this->Form->control('deadline') ?>
            <?= $this->Form->button(__('Add')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

</div>