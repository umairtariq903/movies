<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\validator As v;

$app->get('/movie-list/fetch', function (Request $request, Response $response, array $args) {
 
    $token = $request->getHeader('token');
    $token = $token[0];
    if(empty($token)){
        $error = array(
         "message" => 'Empty Token'
       );

       $response->getBody()->write(json_encode($error));
       return $response
         ->withHeader('content-type', 'application/json')
         ->withStatus(500);
    }
    else{
        $authQuery = "SELECT * FROM sessions WHERE status='active' AND token ='".$token."'";
        $db = new DB();
        $conn = $db->connect();
        $Authstmt = $conn->query($authQuery);
        $session = $Authstmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if(empty($session)){
            $error = array(
             "message" => 'Invalid Token'
           );

           $response->getBody()->write(json_encode($error));
           return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(500);
        }
           
    }

  
     $sql = "SELECT * FROM movielist";

     try {
       $db = new DB();
       $conn = $db->connect();
       $stmt = $conn->query($sql);
       $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
       $db = null;
      
       $response->getBody()->write(json_encode($customers));
       return $response
         ->withHeader('content-type', 'application/json')
         ->withStatus(200);
     } catch (PDOException $e) {
       $error = array(
         "message" => $e->getMessage()
       );

       $response->getBody()->write(json_encode($error));
       return $response
         ->withHeader('content-type', 'application/json')
         ->withStatus(500);
     }
});

$app->get('/movie-list/fetch/{id}', function (Request $request, Response $response, array $args) {
    $token = $request->getHeader('token');
    $token = $token[0];
    if(empty($token)){
        $error = array(
         "message" => 'Empty Token'
       );

       $response->getBody()->write(json_encode($error));
       return $response
         ->withHeader('content-type', 'application/json')
         ->withStatus(500);
    }
    else{
        $authQuery = "SELECT * FROM sessions WHERE status='active' AND token ='".$token."'";
        $db = new DB();
        $conn = $db->connect();
        $Authstmt = $conn->query($authQuery);
        $session = $Authstmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if(empty($session)){
            $error = array(
             "message" => 'Invalid Token'
           );

           $response->getBody()->write(json_encode($error));
           return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(500);
        }
           
    } 

 $id = $args['id'];
 $sql = "SELECT * FROM movielist WHERE id=".$id;

 try {
   $db = new DB();
   $conn = $db->connect();
   $stmt = $conn->query($sql);
   $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
   $db = null;
  
   $response->getBody()->write(json_encode($customers));
   return $response
     ->withHeader('content-type', 'application/json')
     ->withStatus(200);
 } catch (PDOException $e) {
   $error = array(
     "message" => $e->getMessage()
   );

   $response->getBody()->write(json_encode($error));
   return $response
     ->withHeader('content-type', 'application/json')
     ->withStatus(500);
 }
});


$app->post('/movie-list/add', function (Request $request, Response $response, array $args) {
 
    $token = $request->getHeader('token');
    $token = $token[0];
    if(empty($token)){
        $error = array(
         "message" => 'Empty Token'
       );

       $response->getBody()->write(json_encode($error));
       return $response
         ->withHeader('content-type', 'application/json')
         ->withStatus(500);
    }
    else{
        $authQuery = "SELECT * FROM sessions WHERE status='active' AND token ='".$token."'";
        $db = new DB();
        $conn = $db->connect();
        $Authstmt = $conn->query($authQuery);
        $session = $Authstmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if(empty($session)){
            $error = array(
             "message" => 'Invalid Token'
           );

           $response->getBody()->write(json_encode($error));
           return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(500);
        }
           
    }
 $validator = new validator();
 $data = $request->getParsedBody();

 $validation = $validator->validate($data,[
    'name' => v::stringVal()->notEmpty(),
    'casts' => v::arrayVal()->notEmpty(),
    'release_date' => v::Date()->notEmpty(),
    'director' => v::stringVal()->notEmpty(),
    'ratings' => v::arrayVal()->notEmpty(),
 ]);

 if ($validation->failed()) {
    $error = array(
     "message" => $validation->errors
   );

   $response->getBody()->write(json_encode($error));
   return $response
     ->withHeader('content-type', 'application/json')
     ->withStatus(500);  
 }
 

 $name = $data["name"];
 $casts = serialize($data["casts"]);
 $release_date = $data["release_date"];
 $director = $data["director"];
 $ratings = serialize($data["ratings"]);

 $sql = "INSERT INTO movielist (name, casts, release_date, director, ratings) VALUES (:name, :casts, :release_date, :director, :ratings)";

 try {
   $db = new DB();
   $conn = $db->connect();
   $stmt = $conn->prepare($sql);
   $stmt->bindParam(':name', $name);
   $stmt->bindParam(':casts', $casts);
   $stmt->bindParam(':release_date', $release_date);
   $stmt->bindParam(':director', $director);
   $stmt->bindParam(':ratings', $ratings);

   $result = $stmt->execute();

   $db = null;
   $response->getBody()->write(json_encode($result));
   return $response
     ->withHeader('content-type', 'application/json')
     ->withStatus(200);
 } catch (PDOException $e) {
   $error = array(
     "message" => $e->getMessage()
   );

   $response->getBody()->write(json_encode($error));
   return $response
     ->withHeader('content-type', 'application/json')
     ->withStatus(500);
 }
});