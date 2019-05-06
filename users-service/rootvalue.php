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
interface Resolver {
    public function resolve($root, $args, $context);
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
                $expire     = $notBefore + 100; // Adding n seconds
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
                
                header('Content-type: application/json');
                
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
                throw new UserException('User doesn\'t exist');
            }
        } else {
            throw new Exception('DB connection failed');
        }

    }
    
}
return [
    'createToken' => function($root, $args, $context) {
        $obj = new CreateToken();
        return $obj->resolve($root, $args, $context);
    },
    'getId' => function($root, $args, $context) {
        return $root['prefix']."hi";
    },
    'prefix' => '',
];
