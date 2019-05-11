<?php
return array(
    'jwt' => array(
        'key'       => 'Y29tcHJhdGVj',     // Key for signing the JWT's, I suggest generate it with base64_encode(openssl_random_pseudo_bytes(64))
        'algorithm' => 'HS512' // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        ),
    'database' => array(
        'user'     => 'postgres', // Database username
        'password' => 'compratec', // Database password
        'port'     => '5432', // Database port
        'host'     => 'sql', // Database host
        'name'     => 'postgres', // Database schema name
    ),
    'serverName' => 'compratec.com',
);