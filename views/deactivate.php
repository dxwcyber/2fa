<?php

$devices = twofa_user_devices(get_current_user_id());

if (isset($_POST['device_id'])) {
  $id = absint($_POST['device_id']);
  $new_devices = [];
  foreach ($devices as $device) {
    if ($device['id'] !== $id) {
      $new_devices[] = $device;
    }
  }
  update_user_meta(get_current_user_id(), '2fa_devices', $new_devices);

  ?>
  <p>The device has been deactivated.</p>
  <?php

} else {

  $missing_device = true;

  $id = 0;
  if (isset($_GET['device_id'])) {
    $id = absint($_GET['device_id']);

    foreach ($devices as $device) {
      if ($device['id'] === $id) {
        $missing_device = false;
        break;
      }
    }
  }

  if ($missing_device) {
    ?>
    <p>No such device.</p>
    <?php
  } elseif (count($devices) < 1) {
    ?>
    <p>You cannot get rid of your only device. Activate another first.</p>
    <?php
  } else {
    ?>
    <p>Are you sure you want to deactivate device with ID <?php echo esc_html($id) ?>?</p>
    <p>You won't be able to use it to log in with from now on.</p>
    <form method="POST">
      <?php wp_nonce_field('2fa_deactivate-'.$id) ?>
      <input type="hidden" name="device_id" value="<?php echo esc_attr($id) ?>">
      <input type="submit" value="Deactivate the device">
    </form>
    <?php
  }
}