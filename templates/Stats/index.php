<div class="content">
  <h3><?= __('Top 5 Users with the most Listings') ?></h3>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th><?= __('Rank') ?></th>
          <th><?= __('Pseudo') ?></th>
          <th><?= __('Nb Listings') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($topFiveUsersMostListings as $key => $listing) : ?>
          <tr>
            <td><?= $key+1 ?></td>
            <td><?= h($listing->user->pseudo) ?></td>
            <td><?= h($listing->count_list) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="content" style="margin-top: 30px;">
  <h3><?= __('Top 5 most copied Listings') ?></h3>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th><?= __('Rank') ?></th>
          <th><?= __('Name') ?></th>
          <th><?= __('Nb Copied') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($topFiveListings as $key => $listing) : ?>
          <tr>
            <td><?= $key+1 ?></td>
            <td><?= h($listing->parent_listing->name) ?></td>
            <td><?= h($listing->count_copy) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="content" style="margin-top: 30px;">
  <h3><?= __('Top 5 Users that copy the most') ?></h3>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th><?= __('Rank') ?></th>
          <th><?= __('Pseudo') ?></th>
          <th><?= __('Nb Copied') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($topFiveUsersCopyTheMost as $key => $listing) : ?>
          <tr>
            <td><?= $key+1 ?></td>
            <td><?= h($listing->user->pseudo) ?></td>
            <td><?= h($listing->count_copy) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>