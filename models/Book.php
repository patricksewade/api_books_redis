<?php
// models/Book.php

class Book {
    public $id;
    public $title;
    public $author;
    public $year;
    public $genre;
    public $status;

    public function __construct($id, $title, $author, $year, $genre, $status) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
        $this->status = $status;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'year' => $this->year,
            'genre' => $this->genre,
            'status' => $this->status,
        ];
    }
}
