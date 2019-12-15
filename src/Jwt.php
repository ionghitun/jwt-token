<?php

namespace IonGhitun\JwtToken;

use Carbon\Carbon;

/**
 * Class Jwt
 *
 * @package IonGhitun\JwtToken
 */
class Jwt
{
    /**
     * Generate a new JWT token.
     *
     * @param array $payload
     *
     * @return string
     *
     * @throws JwtException
     */
    public static function generateToken(array $payload)
    {
        try {
            $header = self::base64EncodeUrlSafe(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));

            if (!isset($payload['expiration'])) {
                $payload['expiration'] = Carbon::now()->addDay()->format('Y-m-d H:i:s');
            }

            $payload = self::base64EncodeUrlSafe(json_encode($payload));

            $signature = self::base64EncodeUrlSafe(hash_hmac('sha256', $header . '.' . $payload, env('JWT_SECRET'), true));

            return $header . "." . $payload . "." . $signature;
        } catch (\Exception $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Url safe base 64 encode.
     *
     * @param string $string
     *
     * @return string
     */
    private static function base64EncodeUrlSafe(string $string)
    {
        return str_replace('=', '', strtr(base64_encode($string), '+/', '-_'));
    }

    /**
     * Validate JWT token and return payload
     *
     * @param $token
     *
     * @return array
     *
     * @throws JwtException
     */
    public static function validateToken($token)
    {
        try {
            $tokenData = explode('.', $token);

            if (count($tokenData) !== 3) {
                throw new JwtException('Not a valid JWT token!');
            }

            list($header64, $payload64, $signature64) = $tokenData;

            $header = json_decode(self::base64DecodeUrlSafe($header64), true);
            $payload = json_decode(self::base64DecodeUrlSafe($payload64), true);

            if (!$header || !$payload || !isset($payload['expiration'])) {
                throw new JwtException('Not a valid JWT token!');
            }

            if (Carbon::parse($payload['expiration']) < Carbon::now()) {
                throw new JwtException('Jwt token expired!');
            }

            $signature = self::base64DecodeUrlSafe($signature64);

            if ($signature !== hash_hmac('sha256', $header64 . '.' . $payload64, env('JWT_SECRET'), true)) {
                throw new JwtException('Could not verify Jwt token signature!');
            }

            return $payload;
        } catch (\Exception $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Url safe base 64 decode.
     *
     * @param $string
     *
     * @return false|string
     */
    private static function base64DecodeUrlSafe($string)
    {
        $mod = strlen($string) % 4;

        if ($mod !== 0) {
            $padlen = 4 - $mod;
            $string .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($string, '-_', '+/'));
    }
}
