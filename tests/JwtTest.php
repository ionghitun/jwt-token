<?php
declare(strict_types=1);

namespace IonGhitun\JwtToken\Tests;

use IonGhitun\JwtToken\Exceptions\JwtException;
use IonGhitun\JwtToken\Jwt;
use PHPUnit\Framework\TestCase;

/**
 * Class JtwTest
 *
 * @package IonGhitun\JwtToken\Tests
 */
class JtwTest extends TestCase
{
    /**
     * Test generate and validate token
     *
     * @throws JwtException
     */
    public function testGenerateToken()
    {
        $payload = ['test' => 'phpunit'];

        $token = Jwt::generateToken($payload);

        $this->assertArrayHasKey('test', Jwt::validateToken($token));
    }

    /**
     * Test not valid token
     *
     * @throws JwtException
     */
    public function testJwtException()
    {
        $this->expectException(JwtException::class);

        $payload = Jwt::validateToken('token');
    }
}
