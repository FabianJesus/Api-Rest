<?php

namespace App\Service;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;

class JwtTokenService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('lkjsaasdj@alk34567kasjgj76745k@_-asf9878934759-834758@9_f9rufd8'));
    }

    public function generateToken(array $claims, int $expiresInSeconds): Plain
    {
        $now = new \DateTimeImmutable();

        return $this->config->builder()
            ->issuedBy('@pi_t')
            ->permittedFor('@pi_t')
            ->identifiedBy('unique-token-id', true)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify("+{$expiresInSeconds} seconds"))
            ->withClaim('user', $claims)
            ->getToken($this->config->signer(), $this->config->signingKey());
    }

    public function validateToken(string $tokenString): bool
    {
        try {
            $token = $this->config->parser()->parse($tokenString);

            $constraints = [
                new ValidAt(SystemClock::fromUTC()),
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
            ];

            $claims = $token->claims();
            if (!$claims->has('iss') || '@pi_t' !== $claims->get('iss')) {
                throw new \RuntimeException('Invalid token issuer');
            }

            return $this->config->validator()->validate($token, ...$constraints);
        } catch (\Exception $e) {
            return false;
        }
    }
}
