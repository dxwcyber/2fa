<?php if (!twofa_user_enabled(get_current_user_id())) : ?>
    <p>You cannot use 2FA because it has not been set up for your account yet.</p>
<?php elseif (!twofa_user_activated(get_current_user_id())) : ?>
    <p>You don't have any devices activated yet. Please <a href="?page=2fa&step=setup">activate one</a></p>
<?php else : ?>
    <?php $devices = twofa_user_devices(get_current_user_id()) ?>

    <p>Your activated devices</p>

    <table>
        <thead>
            <tr><th>Device</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $device) : ?>
                <tr>
                    <td>
                        <?php if ($device['mode'] === 'email') : ?>
                            Email (<strong><?php echo esc_html(wp_get_current_user()->user_email) ?></strong>)
                        <?php else : ?>
                            <?php echo esc_html($device['name']) ?>
                        <?php endif ?>
                    </td>
                    <td><a href="?page=2fa&step=deactivate&device_id=<?php echo esc_attr($device['id']) ?>">Deactivate</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <p>You have activated <?php echo sprintf(_n('%d of %d allowed device', '%d of %d allowed devices', TWOFA_MAX_DEVICES), count($devices), TWOFA_MAX_DEVICES) ?>.</p>
    <?php if (count($devices) < TWOFA_MAX_DEVICES) : ?>
        <p>You may <a href="?page=2fa&step=setup">activate another</a>.</p>
    <?php endif ?>
<?php endif ?>
