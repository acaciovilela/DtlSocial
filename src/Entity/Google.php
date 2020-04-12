<?php

namespace DtlSocial\Entity;

use Doctrine\ORM\Mapping as ORM;
use DtlUser\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="google")
 */
class Google {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="access_token", type="string", length=1024)
     * @var string
     */
    protected $accessToken;

    /**
     * @ORM\Column(name="token_type", type="string", nullable=true)
     * @var string
     */
    protected $refreshToken;

    /**
     * @ORM\Column(name="id_token", type="text", nullable=true)
     * @var string
     */
    protected $idToken;

    /**
     * @ORM\Column(name="refresh_token", type="string", nullable=true)
     * @var int
     */
    protected $expiresIn;

    /**
     * @ORM\ManyToOne(targetEntity="DtlUser\Entity\User", cascade={"persist"})
     * @var User
     */
    protected $user;

    public function getId(): int {
        return $this->id;
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function getRefreshToken(): string {
        return $this->refreshToken;
    }

    public function getIdToken(): string {
        return $this->idToken;
    }

    public function getExpiresIn(): int {
        return $this->expiresIn;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    public function setAccessToken(string $accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setRefreshToken(string $refreshToken) {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function setIdToken(string $idToken) {
        $this->idToken = $idToken;
        return $this;
    }

    public function setExpiresIn(int $expiresIn) {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

}
