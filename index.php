<?php include 'db.php';
error_log('Hello PHP');
header('Content-Type: text/html; charset=utf-8');

function action_response($action, $id, $status)
{
    if (empty($status))
        $status = 200;
        
    http_response_code($status);
    echo 'HTTP ' . $status . ' - Photo ' . $action . ': <a href="' . $_SERVER['PHP_SELF'] . '?id=' . $id . '">' . $id . '</a>';
}

// Handle GET requests to fetch a photo
if (!empty($_GET['id'])) {
    error_log('Detected a valid GET request');
    
    try {
        $photo = get_photo($_GET['id']);
        
        if (!empty($photo)) { 
            list($type, $photo) = explode(';', $photo);
            list(, $photo) = explode(',', $photo);
            $photo = base64_decode($photo);
            header('Content-Type: ' . ltrim($tyoe, 'data:'));
            echo $photo;
        } else {
            action_response('not found', $_GET['id'], 404);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Database error occurred: ' . $e->getMessage();
    } finally {
        exit();
    }
}

// Handle POST request for adding/updating a photo
if (!empty($_POST['id']) && !empty($_POST['photo'])) {
    error_log('Detected a valid POST request');
    
    try {
        if (!empty(get_photo($_POST['id']))) {
            update_photo($_POST['id'], $_POST['photo']);
            action_response('updated', $_POST['id'], 200);
        } else {
            add_photo($_POST['id'], $_POST['photo']);
            action_response('added', $_POST['id'], 201);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Database error occurred: ' . $e->getMessage();
    } finally {
        exit();
    }
}

// If request to root (or any other request really), list photobook
try {
    error_log('Detected a non-defined-above request');
    
    create_table();
    $ids = get_ids();

    if (!empty($ids)) {
        echo '<ul>';
        foreach ($ids as &$id) {
            echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?id=' . $id . '">' . $id . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo 'No photos have been added to the book yet.';
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database error occurred: ' . $e->getMessage();
} finally {
    exit();
}
