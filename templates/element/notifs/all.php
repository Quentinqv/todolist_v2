<dialog id="notifications">
  <div class="notifications__header">
    <h2>Notifications</h2>
    <form method="dialog">
      <button><i class="fa-solid fa-xmark fa-2xl"></i></button>
    </form>
  </div>
  <p class="message error notifs_empty <?= $notifications->count() == 0 ? "" : "hidden" ?>">No notifications</p>
  <ul>
    <?php foreach ($notifications as $key => $notif) :?>
      <li class="notification <?= $notif->readby == null ? "" : "seen" ?>" data-id="<?= $notif->id ?>">
        <p><?= h($notif->content) ?></p>

        <div class="notification__actions">
            <span class="readby <?= $notif->readby != null ? "" : "hidden" ?>"><?= $notif->readby != null ? "Seen on ".$notif->readby : "" ?></span>
          <a class="seen_trigger"><i class="fa-solid <?= $notif->readby == null ? "fa-eye" : "fa-eye-slash" ?>"></i></a>
          <a class="delete_trigger"><i class="fa-solid fa-trash"></i></a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</dialog>