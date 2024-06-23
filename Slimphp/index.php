<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
require 'vendor/autoload.php';
require_once './config.php';



// class Database {
//     private $pdo;

//     public function __construct() {
//         $host = 'localhost';
//         $db   = 'wt_labtest3';
//         $user = 'root';
//         $pass = '';
//         $charset = 'utf8mb4';

//         $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//         $options = [
//             PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//             PDO::ATTR_EMULATE_PREPARES   => false,
//         ];

//         try {
//             $this->pdo = new PDO($dsn, $user, $pass, $options);
//         } catch (\PDOException $e) {
//             throw new \PDOException($e->getMessage(), (int)$e->getCode());
//         }
//     }

//     public function getPdo() {
//         return $this->pdo;
//     }
// }

$app = new \Slim\App;
// $app->add(function ($request, $handler) {
//     if ($request->getMethod() === 'OPTIONS') {
//         $response = new \Slim\Http\Response(200);
//     } else {
//         $response = $handler->handle($request);
//     }

//     return $response
//         ->withHeader('Access-Control-Allow-Origin', '*')
//         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
//         ->withHeader('Access-Control-Allow-Credentials', 'true'); // If you're using credentials
// });
// Instantiate Database
$database = new Database();

$app->get('/users', function ($request, $response, $args) use ($database) {
    $stmt = $database->getPdo()->query('SELECT * FROM users');
    $users = [];
    while ($row = $stmt->fetch()) {
        $users[] = $row;
    }
    return $response->withJson($users)->withStatus(200);
});

$app->get('/user/{userid}', function ($request, $response, $args) use ($database) {
    $userid = $args['userid'];
    $sql = 'SELECT * FROM users WHERE id = :userid';
    $stmt = $database->getPdo()->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();

    $user = $stmt->fetch();
    if (!$user) {
        return $response->withJson(['message' => 'User not found'], 404);
    }

    return $response->withJson($user);
});

$app->post('/users', function ($request, $response, $args) use ($database) {
    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];

    // Check if name or email is empty
    if (empty($name) || empty($email)) {
        return $response->withJson(['message' => 'Name or email is empty'], 400);
    }

    $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $database->getPdo()->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $result = $stmt->execute();

    if ($result) {
        return $response->withJson(['message' => 'User added successfully'], 201);
    } else {
        return $response->withJson(['message' => 'Failed to add user'], 500);
    }
});

$app->put('/user/{userid}', function ($request, $response, $args) use ($database) {
    $data = $request->getParsedBody();
    $userid = $args['userid'];
    $name = $data['name'];
    $email = $data['email'];

    // Check if name or email is empty
    if (empty($name) || empty($email)) {
        return $response->withJson(['message' => 'Name or email is empty'], 400);
    }

    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :userid";
    $stmt = $database->getPdo()->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $result = $stmt->execute();

    if ($result) {
        return $response->withJson(['message' => 'User updated successfully'], 200);
    } else {
        return $response->withJson(['message' => 'Failed to update user'], 500);
    }
});

$app->delete('/user/{userid}', function ($request, $response, $args) use ($database) {
    $userid = $args['userid'];
    $sql = 'DELETE FROM users WHERE id = :userid';
    $stmt = $database->getPdo()->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $response->withJson(['message' => 'User has been deleted'], 204);
    } else {
        return $response->withJson(['message' => 'User not found'], 404);
    }
});

$app->get('/', function ($request, $response, $args) {
    return $response->write("Hello, World!");
});

$app->run();