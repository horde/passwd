<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($topbar['appName']) ?> - Horde</title>

    <!-- Base responsive styles (includes topbar) -->
    <link rel="stylesheet" href="<?= htmlspecialchars($hordeThemesUri) ?>/default/responsive.css">

    <!-- Passwd-specific styles -->
    <link rel="stylesheet" href="<?= htmlspecialchars($passwdThemesUri) ?>/default/passwd-responsive.css">
</head>
<body>
    <?php
    // Include topbar component
    extract($topbar); // Makes $appName, $portalUrl, etc. available
    $hordeTemplates = $registry->get('templates', 'horde');
    include $hordeTemplates . '/responsive/topbar.html.php';
    ?>

    <main class="container passwd-container">
        <div class="card passwd-card">
            <div class="card-header">
                <h2 class="card-title"><?= htmlspecialchars($header) ?></h2>
            </div>

            <?php if ($status): ?>
                <?= $status ?>
            <?php endif; ?>

            <form method="post" action="" id="passwd-form">
                <?= $formInput ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <?php if (!empty($url)): ?>
                    <input type="hidden" name="return_to" value="<?= htmlspecialchars($url) ?>">
                <?php endif; ?>
                <?php if (!$showlist): ?>
                    <input type="hidden" name="backend" value="<?= htmlspecialchars($backend) ?>">

                    <div class="info-message">
                        <span class="info-icon">ℹ️</span>
                        <span class="info-text">
                            <?php
                            // Special wording for hordeauth backend
                            if ($backend === 'hordeauth') {
                                echo _("You are changing your Horde login password.");
                            } else {
                                echo sprintf(
                                    _("You are changing the password for: %s"),
                                    '<strong>' . htmlspecialchars($backends[$backend]['name']) . '</strong>'
                                );
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($userChange): ?>
                    <div class="form-group">
                        <label for="passwd-userid" class="form-label">
                            <?= _("Username") ?>
                        </label>
                        <input type="text"
                               id="passwd-userid"
                               name="userid"
                               class="form-input"
                               value="<?= htmlspecialchars($userid) ?>"
                               required>
                    </div>
                <?php endif; ?>

                <?php if ($showlist): ?>
                    <div class="form-group">
                        <label for="passwd-backend" class="form-label">
                            <?= _("Change password for") ?>
                        </label>
                        <select id="passwd-backend"
                                name="backend"
                                class="form-input"
                                required>
                            <?php foreach ($backends as $key => $val): ?>
                                <?php if (substr($key, 0, 1) != '_'): ?>
                                    <option value="<?= htmlspecialchars($key) ?>"
                                            <?= $key == $backend ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($val['name']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="passwd-oldpassword" class="form-label">
                        <?= _("Old password") ?>
                    </label>
                    <input type="password"
                           id="passwd-oldpassword"
                           name="oldpassword"
                           class="form-input"
                           autocomplete="current-password"
                           required>
                </div>

                <div class="form-group">
                    <label for="passwd-newpassword0" class="form-label">
                        <?= _("New password") ?>
                    </label>
                    <input type="password"
                           id="passwd-newpassword0"
                           name="newpassword0"
                           class="form-input"
                           autocomplete="new-password"
                           required>
                </div>

                <div class="form-group">
                    <label for="passwd-newpassword1" class="form-label">
                        <?= _("Confirm new password") ?>
                    </label>
                    <input type="password"
                           id="passwd-newpassword1"
                           name="newpassword1"
                           class="form-input"
                           autocomplete="new-password"
                           required>
                </div>

                <button type="submit" name="submit" value="1" class="btn btn-primary btn-block">
                    <?= _("Change Password") ?>
                </button>
            </form>
        </div>
    </main>

    <!-- Validation messages for JavaScript -->
    <script>
    window.passwdValidationMessages = <?= json_encode($validationMessages) ?>;
    </script>

    <!-- Topbar behavior -->
    <script src="<?= htmlspecialchars($hordeJsUri) ?>/responsive-topbar.js"></script>

    <!-- Password form validation -->
    <script src="<?= htmlspecialchars($passwdJsUri) ?>/passwd-responsive.js"></script>
</body>
</html>
