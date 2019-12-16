# Jwt Token

Json web token generation and validation.

## Instalation notes

`$ composer require ionghitun/jwt-token`

## Documentation:

You need to add `JWT_SECRET` to your `.env` file.

Import `Jwt` from `IonGhitun\JwtToken`

- use `Jwt::generateToken($payload)` to generate a token, `$payload` should be an array.
- use `Jwt::validateToken($token)` to validate a token.

Valability on the token is default one day.
It can be overwritten by adding expiration to `$payload`:

        $payload['expiration'] = Carbon::now()->addDay()->format('Y-m-d H:i:s');

In case `$token` is not a valid Jwt token, expired or could not verify signature with secret a `IonGhitun\JwtToken\Exceptions\JwtException` will be thrown.
