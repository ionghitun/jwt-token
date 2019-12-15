# Laravel Mysql Encryption

Json web token generation and validation.

## Instalation notes

`$ composer require ionghitun/jwt-token`

## Documentation:

You need to add `JWT_SECRET` to your `.env` file.

Import `Jwt` from `IonGhitun\JwtToken`

- use `Jwt::generateToken($payload)` to generate a token, `$payload` should be an array.
- use `Jwt::validateToken($token)` to validate a token.

In case `$token` is not a valid Jwt token, expired or could not verify signature with secret a `JwtException` will be thrown.
