<?php
if (!defined('_INCODE')) die('Access Deined...');

function query($sql, $data = [], $statementStatus = false) {
    global $conn;
    $query = false;
    try {
        $statement = $conn->prepare($sql);

        if (empty($data)) {
            $query = $statement->execute();
        } else {
            $query = $statement->execute($data);
        }

        if ($statementStatus && $query) {
            return $statement;
        }

        return $query;
    } catch (Exception $exception) {
        require_once 'modules/errors/database.php';
        die();
    }
}

function insert($table, $dataInsert) {

    $keyArray = array_keys($dataInsert);
    $fieldString = implode(', ', $keyArray);
    $valueString = ':'.implode(', :', $keyArray);

    $sql = "INSERT INTO $table($fieldString) VALUES ($valueString)";

    return query($sql, $dataInsert);
}

function update($table, $dataUpdate, $condition='') {

    $updateString = '';
    foreach($dataUpdate as $key => $item) {
        $updateString .= $key.'=:'.$key.', ';
    }
    $updateString = rtrim($updateString, ', ');

    if(!empty($condition)) {
        $sql = "UPDATE $table SET $updateString WHERE $condition";
    } else {
        $sql = "UPDATE $table SET $updateString";
    }

    return query($sql, $dataUpdate);
}

function delete($table, $condition='') {
    if (!empty($condition)) {
        $sql = "DELETE FROM $table WHERE $condition";
    } else {
        $sql = "DELETE FROM $table";
    }

    return query($sql);
}

// Lấy giữ liệu từ câu lệnh sql - Lấy tất cả
function getRaw($sql) {
    $statement = query($sql, [], true);
    if (is_object($statement)) {
        $dataFetch = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $dataFetch;
    }

    return false;
}

// Lấy giữ liệu từ câu lệnh sql - Lấy 1 record
function firstRaw($sql) {
    $statement = query($sql, [], true);
    if (is_object($statement)) {
        $dataFetch = $statement->fetch(PDO::FETCH_ASSOC);
        return $dataFetch;
    }

    return false;
}

function get($table, $field='*', $condition='') {
   $sql = "SELECT $field FROM $table";

    if (!empty($condition)) {
        $sql.= " WHERE $condition";
    }

   return getRaw($sql);
}

function first($table, $field='*', $condition='') {
    $sql = "SELECT $field FROM $table";
 
     if (!empty($condition)) {
         $sql.= " WHERE $condition";
     }
 
    return firstRaw($sql);
}

// Lấy số record
function getRaws($sql) {
    $statement = query($sql, [], true);
    if (!empty($statement)) {
        return $statement->rowCount();
    }
}

function insertId() {

}