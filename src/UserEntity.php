<?php

namespace SlimOpauth;

/**
 * Class UserEntity
 * @package SlimOpauth
 */
class UserEntity
{
    /**
     * @var array
     */
    protected $auth;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var \DateTimeInterface
     */
    protected $expires;

    /**
     * @var array
     */
    protected $raw;

    /**
     * @var \DateTimeInterface
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $signature;

    /**
     * UserEntity constructor.
     * @param array $user_data
     */
    public function __construct(array $user_data)
    {
        $this
            ->setAuth($user_data['auth'])
            ->setProvider($user_data['auth']['provider'])
            ->setUid($user_data['auth']['uid'])
            ->setName(@$user_data['auth']['info']['name'])
            ->setImage(@$user_data['auth']['info']['image'])
            ->setEmail(@$user_data['auth']['info']['email'])
            ->setNickname(@$user_data['auth']['info']['nickname'])
            ->setToken(@$user_data['auth']['credentials']['token'])
            ->setSecret(@$user_data['auth']['credentials']['secret'])
            ->setExpires(new \DateTime($user_data['auth']['credentials']['expires']))
            ->setRaw($user_data['auth']['raw'])
            ->setTimestamp(new \DateTime($user_data['timestamp']))
            ->setSignature($user_data['signature']);
    }

    /**
     * @return array
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param array $auth
     * @return UserEntity
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return UserEntity
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return UserEntity
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UserEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return UserEntity
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserEntity
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return UserEntity
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return UserEntity
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return UserEntity
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param \DateTimeInterface $expires
     * @return UserEntity
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return array
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @param array $raw
     * @return UserEntity
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTimeInterface $timestamp
     * @return UserEntity
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     * @return UserEntity
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }

}
