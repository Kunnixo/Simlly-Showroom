<?php
// includes/db.php - Database connection & query helpers using SQLite PDO

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $db_dir = __DIR__ . '/../data';
        if (!is_dir($db_dir)) {
            mkdir($db_dir, 0777, true);
        }
        $db_path = $db_dir . '/showroom.db';
        $is_new = !file_exists($db_path);

        $pdo = new PDO('sqlite:' . $db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Initialize schema
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                images TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Seed initial posts if empty
        if ($is_new || count_posts($pdo) === 0) {
            seed_initial_posts($pdo);
        }
    }
    return $pdo;
}

function count_posts(PDO $pdo): int {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

function get_latest_posts(int $limit = 3): array {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM posts ORDER BY created_at DESC, id DESC LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) {
        $row['images'] = json_decode($row['images'], true) ?: [];
    }
    return $rows;
}

function get_all_posts(): array {
    $db = get_db();
    $stmt = $db->query("SELECT * FROM posts ORDER BY created_at DESC, id DESC");
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) {
        $row['images'] = json_decode($row['images'], true) ?: [];
    }
    return $rows;
}

function add_post(string $title, string $content, array $images): int {
    $db = get_db();
    $stmt = $db->prepare("INSERT INTO posts (title, content, images) VALUES (:title, :content, :images)");
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':images' => json_encode(array_values($images), JSON_UNESCAPED_SLASHES)
    ]);
    return (int)$db->lastInsertId();
}

function delete_post(int $id): bool {
    $db = get_db();
    // Fetch images to optionally unlink
    $stmt = $db->prepare("SELECT images FROM posts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $post = $stmt->fetch();
    if ($post) {
        $images = json_decode($post['images'], true) ?: [];
        foreach ($images as $img) {
            // Delete only if inside uploads
            $fullPath = __DIR__ . '/../' . ltrim($img, '/\\');
            if (file_exists($fullPath) && strpos(realpath($fullPath), realpath(__DIR__ . '/../uploads')) === 0) {
                @unlink($fullPath);
            }
        }
    }
    $del = $db->prepare("DELETE FROM posts WHERE id = :id");
    return $del->execute([':id' => $id]);
}

function seed_initial_posts(PDO $pdo): void {
    // Seed initial posts using copies from Foto karuzelka to uploads
    $source_dir = __DIR__ . '/../Foto karuzelka';
    $upload_dir = __DIR__ . '/../uploads';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $source_files = glob($source_dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    $copied_images = [];

    foreach ($source_files as $idx => $src) {
        $ext = pathinfo($src, PATHINFO_EXTENSION);
        $dest_name = 'seed_post_' . ($idx + 1) . '.' . $ext;
        $dest_path = $upload_dir . '/' . $dest_name;
        if (!file_exists($dest_path)) {
            @copy($src, $dest_path);
        }
        $copied_images[] = 'uploads/' . $dest_name;
    }

    // Default posts reflecting the Simlly rally theme
    $posts = [
        [
            'title' => 'Simlly Rally Spec V1 – Złote ramiona w specyfikacji WRC',
            'content' => "Nowy projekt customowej kierownicy do symulatora rajdowego wzorowany na legendarnej rajdowej geometrii WRC. Złote, frezowane ramiona z lotniczego aluminium, obszycie z miękkiej Alcantary z żółtym paskiem na godzinie 12 oraz ergonomiczny rozstaw przycisków i magnetycznych łopatek zmiany biegów. Zapewnia niesamowitą precyzję prowadzenia w Dirt Rally 2.0 i EA Sports WRC. Każdy element przygotowany z dbałością o detale pod kątem maksymalnej sztywności na bazach Direct Drive.",
            'images' => array_slice($copied_images, 0, 3) ?: []
        ],
        [
            'title' => 'Button Plate Pro & Custom Hub do baz Fanatec i Simucube',
            'content' => "Indywidualna płyta czołowa wykonana z prawdziwego włókna węglowego (carbon 4mm twill) z enkoderami CTS oraz podświetlanymi przyciskami APEM. Zintegrowany port USB ze spiralnym kablem o podwyższonej odporności na przeciążenia. Zaprojektowany specjalnie pod wymagające oesy i agresywne manewry kierownicą. Moduł kompatybilny z rozstawem śrub 6x70mm standardowo stosowanym w autach wyścigowych.",
            'images' => array_slice($copied_images, 3, 2) ?: []
        ],
        [
            'title' => 'Replika rajdowego koła Grupy A z dedykowanym mocowaniem QR',
            'content' => "Klasyczny klimat lat 90. w nowoczesnym wydaniu simracingowym. Zwieńczenie wielotygodniowej pracy nad wiernym odwzorowaniem układu ramion i głębokości osadzenia koła (dish 65mm). Wzmocniona konstrukcja, matowe złote wykończenie proszkowe oraz dedykowany adapter Quick Release. Projekt stworzony na specjalne zamówienie klienta budującego stanowisko w stylu rajdowego Subaru Impreza 555.",
            'images' => array_slice($copied_images, 1, 3) ?: []
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, images, created_at) VALUES (:title, :content, :images, :created_at)");
    foreach ($posts as $i => $post) {
        $timestamp = date('Y-m-d H:i:s', strtotime("-" . ($i * 2) . " days"));
        $stmt->execute([
            ':title' => $post['title'],
            ':content' => $post['content'],
            ':images' => json_encode($post['images'], JSON_UNESCAPED_SLASHES),
            ':created_at' => $timestamp
        ]);
    }
}
