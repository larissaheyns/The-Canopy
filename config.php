<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'The_Canopy');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function ensure_guest_identity(mysqli $conn): void {
    if (isset($_SESSION['mind_id']) || isset($_SESSION['guest_mind_id'])) {
        return;
    }

    $guestPrefixes = [
        'Drifter', 'Wanderer', 'Dreamer', 'Voyager',
        'Sleeper', 'Astral', 'Nebula', 'Eclipse',
        'Orbit', 'Comet', 'Stardust', 'Moonwalker',
    ];

    $newAlias = null;
    for ($attempt = 0; $attempt < 10; $attempt++) {
        $candidate = $guestPrefixes[array_rand($guestPrefixes)] . '-' . random_int(1000, 9999);

        $lookup = $conn->prepare("SELECT mind_id FROM minds WHERE alias = ?");
        $lookup->bind_param('s', $candidate);
        $lookup->execute();
        $exists = $lookup->get_result()->fetch_assoc();
        $lookup->close();

        if (!$exists) {
            $newAlias = $candidate;
            break;
        }
    }

    if ($newAlias !== null) {
        $cosmicSignature = 'guest-' . bin2hex(random_bytes(16));
        $insert = $conn->prepare(
            "INSERT INTO minds (cosmic_signature, alias, user_role) VALUES (?, ?, 'Drifter')"
        );
        $insert->bind_param('ss', $cosmicSignature, $newAlias);

        if ($insert->execute()) {
            $_SESSION['guest_alias']   = $newAlias;
            $_SESSION['guest_mind_id'] = $insert->insert_id;
        }
        $insert->close();
    }
}

ensure_guest_identity($conn);
?>