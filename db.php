<?php 

function make_pdo(){
    $db = parse_url(getenv('DATABASE_URL'));

    $pdo = new PDO('pgsql:' . sprintf(
        'host=%s;port=%s;user=%s;password=%s;dbname=%s',
        $db['host'],
        $db['port'],
        $db['user'],
        $db['pass'],
        ltrim($db['path'], '/')
    ));
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function create_table()
{
    $pdo = make_pdo();
    $sql = 'CREATE TABLE IF NOT EXISTS photobook(
            id     CHAR( 15 )  PRIMARY KEY NOT NULL,
            photo  TEXT        NOT NULL);';
    $pdo->exec($sql);
}

function get_ids()
{
    $pdo = make_pdo();
    $sql = 'SELECT id FROM photobook;';
    $sth = $pdo->prepare($sql);
    $sth->execute();

    $result = $sth->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}

function get_photo($id)
{
    $pdo = make_pdo();
    $sql = 'SELECT photo FROM photobook
            WHERE id = :id;';
    $sth = $pdo->prepare($sql);
    $sth->bindValue(':id', $id);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_COLUMN);
    return $result;
}

function add_photo($id, $photo)
{
    $pdo = make_pdo();
    $sql = 'INSERT INTO photobook (id, photo)
            VALUES (:id, :photo);';
    $sth = $pdo->prepare($sql);
    $sth->bindValue(':id', $id);
    $sth->bindValue(':photo', $photo);
    $sth->execute();
    
    // $result = $sth->fetch(PDO::FETCH_ASSOC);
    // return $result;
}

function update_photo($id, $photo)
{
    $pdo = make_pdo();
    $sql = 'UPDATE photobook SET photo = :photo
            WHERE id = :id;';
    $sth = $pdo->prepare($sql);
    $sth->bindValue(':id', $id);
    $sth->bindValue(':photo', $photo);
    $sth->execute();
    
    // $result = $sth->fetch(PDO::FETCH_ASSOC);
    // return $result;
}

function delete_photo($id)
{
    $pdo = make_pdo();
    $sql = 'DELETE FROM photobook 
            WHERE id = :id;';
    $sth = $pdo->prepare($sql);
    $sth->bindValue(':id', $id);
    $sth->execute();
}
