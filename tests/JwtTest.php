<?php
declare(strict_types=1);

namespace IonGhitun\JwtToken\Tests;

use Carbon\Carbon;
use IonGhitun\JwtToken\Exceptions\JwtException;
use IonGhitun\JwtToken\Jwt;
use PHPUnit\Framework\TestCase;

/**
 * Class JtwTest
 *
 * @package IonGhitun\JwtToken\Tests
 */
class JwtTest extends TestCase
{
    /**
     * Test generate and validate token
     *
     * @throws JwtException
     */
    public function testGenerateToken()
    {
        $token = Jwt::generateToken(['test' => 'phpunit']);

        $this->assertArrayHasKey('test', Jwt::validateToken($token));
    }

    /**
     * Test not valid jwt token
     *
     * @throws JwtException
     */
    public function testJwtException()
    {
        $this->expectException(JwtException::class);

        Jwt::validateToken('token');
    }

    /**
     * Test jwt token expired
     *
     * @throws JwtException
     */
    public function testJwtExceptionExpired()
    {
        $this->expectException(JwtException::class);

        $payload = ['test' => 'phpunit', 'expiration' => Carbon::now()->subDay()->format('Y-m-d H:i:s')];

        $token = Jwt::generateToken($payload);

        Jwt::validateToken($token);
    }

    /**
     * Test jwt token invalid signature
     *
     * @throws JwtException
     */
    public function testJwtExceptionInvalidSignature()
    {
        $this->expectException(JwtException::class);

        $payload = ['test' => 'phpunit', 'expiration' => Carbon::now()->addDay()->format('Y-m-d H:i:s')];

        $token = Jwt::generateToken($payload);

        $invalidTokenArray = explode('.', $token);

        Jwt::validateToken($invalidTokenArray[0].'.'.$invalidTokenArray[1].'.'.'signature');
    }
}
