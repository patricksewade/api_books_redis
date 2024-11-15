<?php

require_once __DIR__ . '/../config/redis.php';
require_once __DIR__ . '/../models/Book.php';

class BookController {
    private $redis;

    public function __construct() {
        $this->redis = require __DIR__ . '/../config/redis.php';
    }

    public function create($data) {
        $id = uniqid();
        $book = new Book($id, $data['title'], $data['author'], $data['year'], $data['genre'], $data['status']);
        $this->redis->hMSet("book:$id", $book->toArray());
        return $book->toArray();
    }

    public function read($id) {
        $bookData = $this->redis->hGetAll("book:$id");
        return $bookData ?: null;
    }

    public function update($id, $data) {
        if (!$this->redis->exists("book:$id")) {
            return null;
        }
        $bookData = array_merge($this->redis->hGetAll("book:$id"), $data);
        $this->redis->hMSet("book:$id", $bookData);
        return $bookData;
    }

    public function delete($id) {
        return $this->redis->del("book:$id");
    }

    public function getAll() {
        $keys = $this->redis->keys("book:*");
        $books = [];
        foreach ($keys as $key) {
            $books[] = $this->redis->hGetAll($key);
        }
        return $books;
    }
}
