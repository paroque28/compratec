<?php
use GraphQL\Error\ClientAware;
use Zend\Config\Factory;
use Firebase\JWT\JWT;
class UserException extends \Exception implements ClientAware
{
    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'UserError';
    }
}
class DBException extends \Exception implements ClientAware
{
    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'DBError';
    }
}
interface Resolver {
    public function resolve($root, $args, $context);
}
class CreateUser implements Resolver
{
    public function resolve($root, $args, $context)
    {   
        if (!array_key_exists('username', $args)){
            throw new UserException('Field username is empty!');
        }
        if (!array_key_exists('password', $args)){
            throw new UserException('Field password is empty!');
        }
        $username = $args['username'];
        $password = $args['password'];
        $config = Factory::fromFile('config/config.php', true);
        /*
            * Connect to database to validate credentials
            */
        $dsn = 'pgsql:host=' . $config->get('database')->get('host') . ';dbname=' . $config->get('database')->get('name') . ';port=' . $config->get('database')->get('port');
        $db = new PDO($dsn, $config->get('database')->get('user'), $config->get('database')->get('password'));
        

        $sql = 'SELECT id FROM   users WHERE  username = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $rs = $stmt->fetch();
        
        if ($rs) {
            throw new UserException("User already exists");
        }
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try{
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'INSERT INTO users (username, password) VALUES (?, ?)';
            $stmt = $db->prepare($sql);
            $stmt->execute([$username, $password_hash]);
            return "New record created successfully";
        } catch(PDOException $e){
            throw new DBException( "Error: " . $sql . $e->getMessage());
        }
        
        $db->close();
    

    }
    
}
class CreateToken implements Resolver
{
    public function resolve($root, $args, $context)
    {   
        if (!array_key_exists('username', $args)){
            throw new UserException('Field username is empty!');
        }
        if (!array_key_exists('password', $args)){
            throw new UserException('Field password is empty!');
        }
        $username = $args['username'];
        $password = $args['password'];
        $config = Factory::fromFile('config/config.php', true);
        /*
            * Connect to database to validate credentials
            */
        $dsn = 'pgsql:host=' . $config->get('database')->get('host') . ';dbname=' . $config->get('database')->get('name') . ';port=' . $config->get('database')->get('port');
        $db = new PDO($dsn, $config->get('database')->get('user'), $config->get('database')->get('password'));
        
        /*
            * We will fetch user id and password fields for the given username
            */
        $sql = <<<EOL
        SELECT id,
                password
        FROM   users
        WHERE  username = ?
EOL;
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $rs = $stmt->fetch();
        
        if ($rs) {
            if (password_verify($password, $rs['password'])) {
                
                $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
                $issuedAt   = time();
                $notBefore  = $issuedAt + 5;  //Adding 5 seconds
                $expire     = $notBefore + 240; // Adding n seconds
                $serverName = $config->get('serverName');
                
                /*
                    * Create the token as an array
                    */
                $data = [
                    'iat'  => $issuedAt,         // Issued at: time when the token was generated
                    'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                    'iss'  => $serverName,       // Issuer
                    'nbf'  => $notBefore,        // Not before
                    'exp'  => $expire,           // Expire
                    'data' => [                  // Data related to the signer user
                        'userId'   => $rs['id'], // userid from the users table
                        'userName' => $username, // User name
                    ]
                ];
                
                $secretKey = base64_decode($config->get('jwt')->get('key'));
                
                /*
                    * Extract the algorithm from the config file too
                    */
                $algorithm = $config->get('jwt')->get('algorithm');
                
                /*
                    * Encode the array to a JWT string.
                    * Second parameter is the key to encode the token.
                    * 
                    * The output string can be validated at http://jwt.io/
                    */
                    
                $jwt = JWT::encode(
                    $data,      //Data to be encoded in the JWT
                    $secretKey, // The signing key
                    $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                    );
                    
                return $jwt;
            } else{
                throw new UserException('Password doesn\'t match');
            }
        } else {
            throw new UserException('User doesn\'t exist');
        }

    }
    
}
function decodeJWT($jwt)
{
    $config = Factory::fromFile('config/config.php', true);
    $secretKey = base64_decode($config->get('jwt')->get('key'));
    $algorithm = $config->get('jwt')->get('algorithm');
    try{
        $decoded = JWT::decode($jwt, $secretKey,array($algorithm));
    }
    catch (Firebase\JWT\ExpiredException $e){
        throw new UserException("Token Expired");
    }
    catch(Firebase\JWT\SignatureInvalidException $e){
        throw new UserException("Signature Invalid");
    }
    catch(Firebase\JWT\BeforeValidException $e){
        throw new UserException("Before Valid");
    }
    return $decoded;
}

class GetUsername implements Resolver
{
    public function resolve($root, $args, $context)
    {   
        if (!array_key_exists('token', $args)){
            throw new UserException('Field token is empty!');
        }
        $jwt = (array) decodeJWT($args['token']);

        $config = Factory::fromFile('config/config.php', true);
        $dsn = 'pgsql:host=' . $config->get('database')->get('host') . ';dbname=' . $config->get('database')->get('name') . ';port=' . $config->get('database')->get('port');
        $db = new PDO($dsn, $config->get('database')->get('user'), $config->get('database')->get('password'));
        $sql = 'SELECT id, username FROM   users WHERE  id = ?';
        $stmt = $db->prepare($sql);
        $data = (array) $jwt["data"];
        $id = $data["userId"];
        $stmt->execute([$id]);
        $rs = $stmt->fetch();
        if ($rs) {
            return $rs['username'];
        }else {
            throw new UserException("Unknown User Id");
        }
    }
    
}
return [
    'createUser' => function($root, $args, $context) {
        $obj = new CreateUser();
        return $obj->resolve($root, $args, $context);
    },
    'createToken' => function($root, $args, $context) {
        $obj = new CreateToken();
        return $obj->resolve($root, $args, $context);
    },
    'getUsername' => function($root, $args, $context) {
        $obj = new GetUsername();
        return $obj->resolve($root, $args, $context);
    },
    'prefix' => '',
];
