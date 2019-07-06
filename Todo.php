<?php

namespace MyApp;

class Task {
  private $_db;

  public function __construct() {
    $this->_createToken();

    #DB接続
    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  #トークン作成
  private function _createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  # 一覧取得
  public function getAll() {
    $stmt = $this->_db->query("select * from tasks order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  #動作判定(更新、作成、削除)
  public function post() {
    $this->_validateToken();

    if (!isset($_POST['mode'])) {
      throw new \Exception('mode not set!');
    }

    switch ($_POST['mode']) {
      case 'update':
        return $this->_update();
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
    }
  }

  #トークンのバリデーション
  private function _validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }

  #更新
  private function _update() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[update] id not set!');
    }

    $this->_db->beginTransaction();

    $sql = sprintf("update tasks set state = (state + 1) %% 2 where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    $sql = sprintf("select state from tasks where id = %d", $_POST['id']);
    $stmt = $this->_db->query($sql);
    $state = $stmt->fetchColumn();

    $this->_db->commit();

    return [
      'state' => $state
    ];
  }

  #作成
  private function _create() {
    if (!isset($_POST['title']) || $_POST['title'] === '') {
      throw new \Exception('[create] title not set!');
    }

    $sql = "insert into tasks (title) values (:title)";
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([':title' => $_POST['title']]);

    return [
      'id' => $this->_db->lastInsertId()
    ];
  }

  #削除
  private function _delete() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("delete from tasks where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    return [];
  }
}
