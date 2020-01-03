[![Latest Stable Version](https://poser.pugx.org/ionghitun/jwt-token/v/stable)](https://packagist.org/packages/ionghitun/jwt-token)
[![Build Status](https://travis-ci.com/ionghitun/jwt-token.svg?branch=master)](https://travis-ci.com/ionghitun/jwt-token)
[![Total Downloads](https://poser.pugx.org/ionghitun/jwt-token/downloads)](https://packagist.org/packages/ionghitun/jwt-token)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ionghitun/jwt-token/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ionghitun/jwt-token/?branch=master)
[![License](https://poser.pugx.org/ionghitun/jwt-token/license)](https://packagist.org/packages/ionghitun/jwt-token)

# Jwt Token

Json web token generation and validation.

## Instalation notes

`$ composer require ionghitun/jwt-token`

## Dependencies

- php >= 7.2

## Documentation:

You need to add `JWT_SECRET` to your `.env` file.

Import `Jwt` from `IonGhitun\JwtToken`

- use `Jwt::generateToken($payload)` to generate a token, `$payload` should be an array.
- use `Jwt::validateToken($token)` to validate a token.

Validity of the token is default one day.
It can be overwritten by adding expiration to `$payload`:

        $payload['expiration'] = Carbon::now()->addDay()->format('Y-m-d H:i:s');

In case `$token` is not a valid Jwt token, expired or could not verify signature with secret a `IonGhitun\JwtToken\Exceptions\JwtException` will be thrown on `validateToken` method.

_Happy coding!_
