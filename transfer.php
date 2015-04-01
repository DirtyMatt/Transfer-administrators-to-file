<?php

/**
 * @author DirtyMatt
 * @package Transfer administrators to file
 * @version 1.2 stable
 * @copyright (C) 2015 DirtyMatt.  Все права защищены.
 * @link https://github.com/DirtyMatt/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */
@require_once 'inc/db.config.inc.php';


try {
    $db = new PDO('mysql:host=' . $config->db_host . ';dbname=' . $config->db_db, $config->db_user, $config->db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8");

    $dbs = $db->query('SELECT * FROM '.$config->db_prefix.'_amxadmins');
    $dbs->setFetchMode(PDO::FETCH_OBJ);

// Сохраняем результат в файл 
    while ($row = $dbs->fetch()) {
        if ($row->flags == "ce")
            $cfg .= '"' . $row->steamid . '" ';

        else if ($row->flags == "ca")
            $cfg .= '"' . $row->nickname . '" ';

        else if ($row->flags == "a")
            $cfg .= '"' . $row->nickname . '" ';

        $cfg .= '"' . $row->password . '" ';
        $cfg .= '"' . $row->access . '" ';
        $cfg .= '"' . $row->flags . '" ';
        $cfg .= '; SteamID: ' . $row->steamid . ' ';
        $cfg .= 'Nickname: ' . $row->nickname . ' ' . PHP_EOL;


        $cfgFile = 'users.ini';

        if (!file_put_contents($cfgFile, $cfg)) {
            return 'Не удалось сохранить конфиг.';
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    file_put_contents('errors.txt', $e->getMessage(), FILE_APPEND);
}
$db = null;
?>