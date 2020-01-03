<?php

namespace IonGhitun\JwtToken;

use Carbon\Carbon;
use IonGhitun\JwtToken\Exceptions\JwtException;

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
     */
    public static function generateToken(array $payload)
    {
        $header = self::base64EncodeUrlSafe(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));

        if (!isset($payload['expiration'])) {
            $payload['expiration'] = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        }

        $payload = self::base64EncodeUrlSafe(json_encode($payload));
        $signature = self::generateSignature($header, $payload);

        return $header . "." . $payload . "." . $signature;
    }

    /**
     * Url safe base 64 encode.
     *
     * @param string $string
     *
     * @return string|string[]
     */
    private static function base64EncodeUrlSafe(string $string)
    {
        return str_replace('=', '', strtr(base64_encode($string), '+/', '-_'));
    }

    /**
     * Generate signature
     *
     * @param $header
     * @param $payload
     * @param bool $encode
     *
     * @return string|string[]
     */
    private static function generateSignature($header, $payload, $encode = true)
    {
        $signature = hash_hmac('sha256', $header . '.' . $payload, getenv('JWT_SECRET'), true);

        if ($encode) {
            return self::base64EncodeUrlSafe($signature);
        }

        return $signature;
    }

    /**
     * Validate JWT token and return payload
     *
     * @param $token
     * @return mixed
     *
     * @throws JwtException
     */
    public static function validateToken($token)
    {
        $tokenData = explode('.', $token);

        if (count($tokenData) !== 3) {
            throw new JwtException('Not a valid JWT token!');
        }

        list($header64, $payload64, $signature64) = $tokenData;

        $payload = json_decode(self::base64DecodeUrlSafe($payload64), true);

        if (!$payload || !isset($payload['expiration']) || Carbon::parse($payload['expiration']) < Carbon::now()) {
            throw new JwtException('Jwt token expired!');
        }

        $signature = self::base64DecodeUrlSafe($signature64);

        if ($signature !== self::generateSignature($header64, $payload64, false)) {
            throw new JwtException('Could not verify Jwt token signature!');
        }

        return $payload;
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
