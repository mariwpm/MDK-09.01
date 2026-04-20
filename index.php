<?php
session_start();
require_once 'db.php';
require_once 'csrf.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getConnection();
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
}

if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $tagsString = trim($_POST['tags'] ?? '');
    
    if ($title !== '') {
        $stmt = $pdo->prepare('INSERT INTO notes (user_id, title, body) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $title, $body]);
        $noteId = $pdo->lastInsertId();
        
        if ($noteId && $tagsString !== '') {
            $tags = array_map('trim', explode(',', $tagsString));
            foreach ($tags as $tagName) {
                if ($tagName !== '') {
                    $stmt = $pdo->prepare('SELECT id FROM tags WHERE name = ? AND user_id = ?');
                    $stmt->execute([$tagName, $userId]);
                    $tag = $stmt->fetch();
                    
                    if (!$tag) {
                        $stmt = $pdo->prepare('INSERT INTO tags (name, user_id) VALUES (?, ?)');
                        $stmt->execute([$tagName, $userId]);
                        $tagId = $pdo->lastInsertId();
                    } else {
                        $tagId = $tag['id'];
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)');
                    $stmt->execute([$noteId, $tagId]);
                }
            }
        }
    }
    header('Location: index.php');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $tagsString = trim($_POST['tags'] ?? '');
    
    $stmt = $pdo->prepare('UPDATE notes SET title = ?, body = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$title, $body, $id, $userId]);
    
    $stmt = $pdo->prepare('DELETE FROM note_tags WHERE note_id = ?');
    $stmt->execute([$id]);
    
    if ($tagsString !== '') {
        $tags = array_map('trim', explode(',', $tagsString));
        foreach ($tags as $tagName) {
            if ($tagName !== '') {
                $stmt = $pdo->prepare('SELECT id FROM tags WHERE name = ? AND user_id = ?');
                $stmt->execute([$tagName, $userId]);
                $tag = $stmt->fetch();
                
                if (!$tag) {
                    $stmt = $pdo->prepare('INSERT INTO tags (name, user_id) VALUES (?, ?)');
                    $stmt->execute([$tagName, $userId]);
                    $tagId = $pdo->lastInsertId();
                } else {
                    $tagId = $tag['id'];
                }
                
                $stmt = $pdo->prepare('INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)');
                $stmt->execute([$id, $tagId]);
            }
        }
    }
    
    header('Location: index.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    header('Location: index.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'toggle_pin') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('UPDATE notes SET is_pinned = 1 - is_pinned WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    header('Location: index.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('
    SELECT n.*, 
           GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ",") AS tags
    FROM notes n
    LEFT JOIN note_tags nt ON nt.note_id = n.id
    LEFT JOIN tags t ON t.id = nt.tag_id
    WHERE n.user_id = ?
    GROUP BY n.id
    ORDER BY n.is_pinned DESC, n.updated_at DESC
');
$stmt->execute([$userId]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заметки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Заметки</h1>
            <div class="user-menu">
                <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="?action=logout" class="logout">Выйти</a>
            </div>
        </div>
        
        <div class="create-form">
            <h3>Новая заметка</h3>
            <form method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="create">
                <input type="text" name="title" placeholder="Заголовок" required>
                <textarea name="body" placeholder="Текст заметки..." rows="4"></textarea>
                <input type="text" name="tags" placeholder="Теги (через запятую)">
                <button type="submit">Сохранить</button>
            </form>
        </div>
        
        <hr>
        
        <div class="notes-list">
            <?php if (empty($notes)): ?>
                <div class="empty">Нет заметок</div>
            <?php endif; ?>
            
            <?php foreach ($notes as $note): ?>
                <div class="note <?= $note['is_pinned'] ? 'pinned' : '' ?>">
                    <div class="note-head">
                        <h3><?= htmlspecialchars($note['title'] ?? '') ?></h3>
                        <div class="note-actions">
                            <a href="?action=toggle_pin&id=<?= $note['id'] ?>" class="pin-btn">Закрепить</a>
                            <a href="?action=delete&id=<?= $note['id'] ?>" class="delete-btn" onclick="return confirm('Удалить?')">Удалить</a>
                        </div>
                    </div>
                    
                    <div class="note-body">
                        <?= nl2br(htmlspecialchars($note['body'] ?? '')) ?>
                    </div>
                    
                    <?php if (!empty($note['tags'])): ?>
                        <div class="note-tags">
                            <?= htmlspecialchars($note['tags']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="note-date">
                        <?= date('d.m.Y H:i', strtotime($note['updated_at'])) ?>
                    </div>
                    
                    <div class="edit-form">
                        <form method="POST">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $note['id'] ?>">
                            <input type="text" name="title" value="<?= htmlspecialchars($note['title'] ?? '') ?>" required>
                            <textarea name="body" rows="3"><?= htmlspecialchars($note['body'] ?? '') ?></textarea>
                            <input type="text" name="tags" value="<?= htmlspecialchars($note['tags'] ?? '') ?>" placeholder="Теги">
                            <button type="submit">Сохранить изменения</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>