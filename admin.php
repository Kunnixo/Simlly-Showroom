<?php
// admin.php - Management Panel for adding and removing posts
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/post_card.php';

// Enforce authentication
require_admin();

$success_msg = '';
$error_msg = '';

// Handle Delete Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $delete_id = (int)($_POST['post_id'] ?? 0);
    if ($delete_id > 0) {
        if (delete_post($delete_id)) {
            $success_msg = 'Post został pomyślnie usunięty.';
        } else {
            $error_msg = 'Nie udało się usunąć postu.';
        }
    }
}

// Handle Add Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title)) {
        $error_msg = 'Proszę podać krótki tytuł postu.';
    } elseif (empty($content)) {
        $error_msg = 'Proszę wprowadzić treść opisu.';
    } else {
        $uploaded_images = [];
        $upload_dir = __DIR__ . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!empty($_FILES['images']['name'][0])) {
            $total_files = count($_FILES['images']['name']);
            if ($total_files > 5) {
                $error_msg = 'Możesz dodać maksymalnie 5 zdjęć do jednego postu.';
            } else {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['images']['tmp_name'][$i];
                        $original_name = $_FILES['images']['name'][$i];
                        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                        if (in_array($ext, $allowed_extensions)) {
                            $new_file_name = 'simlly_' . uniqid() . '_' . ($i + 1) . '.' . $ext;
                            $destination = $upload_dir . '/' . $new_file_name;

                            if (move_uploaded_file($tmp_name, $destination)) {
                                $uploaded_images[] = 'uploads/' . $new_file_name;
                            }
                        }
                    }
                }
            }
        }

        if (empty($error_msg)) {
            $new_id = add_post($title, $content, $uploaded_images);
            if ($new_id) {
                $success_msg = 'Nowy post został opublikowany pomyślnie!';
            } else {
                $error_msg = 'Wystąpił błąd podczas zapisywania do bazy danych.';
            }
        }
    }
}

// Fetch all posts for list
$all_posts = get_all_posts();

$page_title = 'Panel Administratora – Simlly Showroom';
$active_page = 'admin';
$extra_js = 'assets/js/admin.js';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content admin-page">
    <div class="site-container">
        <!-- Admin Header Bar -->
        <div class="admin-top-bar">
            <div class="admin-top-left">
                <span class="admin-role-badge"><i class="fa-solid fa-user-shield"></i> Administrator Simlly</span>
                <h1 class="admin-heading">Panel Dodawania Postów</h1>
            </div>
            <div class="admin-top-actions">
                <a href="index.php" class="btn btn-secondary btn-sm" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Zobacz stronę
                </a>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Wyloguj
                </a>
            </div>
        </div>

        <!-- Notifications -->
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($success_msg) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= htmlspecialchars($error_msg) ?></span>
            </div>
        <?php endif; ?>

        <div class="admin-grid-layout">
            <!-- Left: Add Post Form -->
            <section class="admin-form-section">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <i class="fa-solid fa-plus"></i>
                        <h2>Dodaj nową pracę / post</h2>
                    </div>

                    <form method="POST" action="admin.php" enctype="multipart/form-data" class="admin-form" id="createPostForm">
                        <input type="hidden" name="action" value="create">

                        <!-- Title Input -->
                        <div class="form-group">
                            <label for="postTitle" class="form-label">
                                <span class="label-title">Krótki, wytłuszczony tytuł</span>
                                <span class="label-hint">Wprowadź zwięzły tytuł projektu</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="postTitle" 
                                   class="form-control form-control-bold" 
                                   placeholder="np. Simlly Custom Wheel Rim V2" 
                                   required 
                                   maxlength="150">
                        </div>

                        <!-- 1:1 Images Input (Max 5) -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-title">Zdjęcia projektu (Max: 5 zdjęć w proporcji 1:1)</span>
                                <span class="label-hint">Zdjęcia zostaną automatycznie dopasowane do kwadratu 1:1</span>
                            </label>

                            <div class="file-dropzone" id="fileDropzone">
                                <input type="file" 
                                       name="images[]" 
                                       id="postImages" 
                                       class="file-input" 
                                       multiple 
                                       accept="image/jpeg,image/png,image/webp,image/jpg">
                                <div class="dropzone-visual">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p class="dropzone-text">Przeciągnij i upuść zdjęcia tutaj lub <strong>kliknij, aby wybrać</strong></p>
                                    <span class="dropzone-limit">Maksymalnie 5 zdjęć (JPG, PNG, WEBP)</span>
                                </div>
                            </div>

                            <!-- Preview Grid for Selected Images -->
                            <div class="selected-images-preview" id="imagesPreviewGrid"></div>
                        </div>

                        <!-- Description Input -->
                        <div class="form-group">
                            <label for="postContent" class="form-label">
                                <span class="label-title">Dłuższy tekst / opis prac (drobny druk)</span>
                                <span class="label-hint">Tekst dłuższy niż 3 linie zostanie zwinięty na stronie z przyciskiem <strong>"..."</strong></span>
                            </label>
                            <textarea name="content" 
                                      id="postContent" 
                                      rows="6" 
                                      class="form-control form-control-smallprint" 
                                      placeholder="Wpisz szczegółowy opis prac, specyfikację materiałów, przeznaczenie rig'a itp..." 
                                      required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitPostBtn">
                            <i class="fa-solid fa-paper-plane"></i> Opublikuj post w Showroomie
                        </button>
                    </form>
                </div>
            </section>

            <!-- Right: Manage Existing Posts -->
            <section class="admin-posts-list-section">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <i class="fa-solid fa-list-check"></i>
                        <h2>Opublikowane prace (<?= count($all_posts) ?>)</h2>
                    </div>

                    <?php if (!empty($all_posts)): ?>
                        <div class="admin-posts-stack custom-scrollbar">
                            <?php foreach ($all_posts as $post): ?>
                                <div class="admin-post-item">
                                    <div class="admin-post-thumb aspect-square">
                                        <?php if (!empty($post['images'][0])): ?>
                                            <img src="<?= htmlspecialchars($post['images'][0]) ?>" alt="Miniatura">
                                        <?php else: ?>
                                            <div class="admin-no-img"><i class="fa-solid fa-image"></i></div>
                                        <?php endif; ?>
                                        <span class="admin-img-badge"><?= count($post['images']) ?> fot.</span>
                                    </div>

                                    <div class="admin-post-details">
                                        <h4 class="admin-post-title"><?= htmlspecialchars($post['title']) ?></h4>
                                        <span class="admin-post-date"><i class="fa-regular fa-calendar"></i> <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
                                        <p class="admin-post-snippet"><?= htmlspecialchars(mb_substr($post['content'], 0, 100)) ?>...</p>
                                    </div>

                                    <div class="admin-post-action">
                                        <form method="POST" action="admin.php" onsubmit="return confirm('Czy na pewno chcesz bezpowrotnie usunąć ten post?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                                            <button type="submit" class="btn-icon-danger" title="Usuń post">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-box">
                            <i class="fa-solid fa-folder-open"></i>
                            <p>Brak postów. Dodaj swój pierwszy wpis obok w formularzu.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
