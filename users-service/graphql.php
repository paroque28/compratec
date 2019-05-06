<?php
require_once __DIR__ . '/vendor/autoload.php';
use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;
use Zend\Config\Factory;
use Firebase\JWT\JWT;
$config = Factory::fromFile('config/config.php', true);
    $secretKey = base64_decode($config->get('jwt')->get('key'));
    $algorithm = $config->get('jwt')->get('algorithm');
    // $decoded = (array) JWT::decode("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTcxMTAwNjcsImp0aSI6IlNGWDc3WmxiRnRHUUlFOFpSQ2hIQzlOaDRTdHBkVjdOeDl2bjkwT1BGekk9IiwiaXNzIjoieW91cmRvbWFpbi5jb20iLCJuYmYiOjE1NTcxMTAwNzIsImV4cCI6MTU1NzExMDI3MiwiZGF0YSI6eyJ1c2VySWQiOjIsInVzZXJOYW1lIjoicGFibG8ifX0.OyfiU22zGke1dDhOM_wWOJm5uY4VcdpiN7O5VmHmpC-smL0VRVgtPSCeg_y61Qb3SsjFHHC2Z5XkkiyLu46dMQ", $secretKey,array($algorithm));
    // $data = json_decode($decoded["data"],true);
    // $id = $data["userId"];
try {
    $schema = BuildSchema::build(file_get_contents(__DIR__ . '/../schema.graphqls'));
    $rootValue = include __DIR__ . '/rootvalue.php';
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
} catch (\Exception $e) {
    $result = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($result);