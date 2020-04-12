<?php

namespace DtlSocial\Entity;

use Doctrine\ORM\Mapping as ORM;
use DtlUser\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook")
 */
class Facebook {

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
     * @ORM\Column(name="token_type", type="string")
     * @var string
     */
    protected $tokenType;

    /**
     * @ORM\Column(name="expires_in", type="integer", nullable=true)
     * @var int
     */
    protected $expiresIn;

    /**
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     * @var string
     */
    protected $facebookId;

    /**
     * @ORM\ManyToOne(targetEntity="DtlUser\Entity\User", cascade={"persist", "remove"})
     * @var User
     */
    protected $user;

    public function getId(): int {
        return $this->id;
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function getTokenType(): string {
        return $this->tokenType;
    }

    public function getExpiresIn(): int {
        return $this->expiresIn;
    }

    public function getFacebookId(): string {
        return $this->facebookId;
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

    public function setTokenType(string $tokenType) {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function setExpiresIn(int $expiresIn) {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function setFacebookId(string $facebookId) {
        $this->facebookId = $facebookId;
        return $this;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

}
