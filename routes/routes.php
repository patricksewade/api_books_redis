<?php
	require_once __DIR__ . '/../controllers/BookController.php';

	$bookController = new BookController();

	$method = $_SERVER['REQUEST_METHOD'];
	$uri = $_SERVER['REQUEST_URI'];

	header('Content-Type: application/json');

	switch ($method) {
		case 'POST':
			if ($uri === '/api_books_redis/') {
				$data = json_decode(file_get_contents('php://input'), true);
				echo json_encode(["message" => "Livre ajouté avec succès", "data" => $bookController->create($data)]);
			}
			break;

		case 'GET':
			if ($uri === '/api_books_redis/') {
				echo json_encode(["message" => "Liste de tous les livres", "data" => $bookController->getAll()]);
			} elseif (preg_match('/\/api_books_redis\/(.+)/', $uri, $matches)) {
			   $id = $matches[1];
				$book = $bookController->read($id);
				echo json_encode($book ?: ["message" => "Livre non trouvé"]);
			}
			break;

		case 'PUT':
			if (preg_match('/\/api_books_redis\/(.+)/', $uri, $matches)) {
				$id = $matches[1];
				$data = json_decode(file_get_contents('php://input'), true);
				$updatedBook = $bookController->update($id, $data);
				if(!empty($updatedBook))
				{
					echo json_encode(["message" => "Livre modifié avec succès", "data" => $updatedBook]);
				}
				else
				{
					echo json_encode(["message" => "Livre non trouvé"]);
				}
			}
			break;

		case 'DELETE':
			if (preg_match('/\/api_books_redis\/(.+)/', $uri, $matches)) {
				$id = $matches[1];
				$deleted = $bookController->delete($id);
				echo json_encode(["message" => $deleted ? "Livre supprimé avec succès" : "Livre non trouvé"]);
			}
			break;

		default:
			http_response_code(405);
			echo json_encode(["message" => "Méthode non autorisée"]);
			break;
	}