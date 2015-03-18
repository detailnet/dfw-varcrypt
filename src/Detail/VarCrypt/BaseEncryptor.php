<?php

namespace Detail\VarCrypt;

use Keboola\Encryption;

abstract class BaseEncryptor
{
    /**
     * @var Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * @param Encryption\EncryptorInterface $encryptor
     */
    public function __construct(Encryption\EncryptorInterface $encryptor)
    {
        $this->setEncryptor($encryptor);
    }

    /**
     * @return Encryption\EncryptorInterface
     */
    public function getEncryptor()
    {
        return $this->encryptor;
    }

    /**
     * @param Encryption\EncryptorInterface $encryptor
     */
    public function setEncryptor(Encryption\EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * Encrypt and encode a value.
     *
     * @param string $value
     * @return string
     */
    public function encode($value)
    {
        $encryptedValue = $this->getEncryptor()->encrypt($value);
        $encodedValue = base64_encode($encryptedValue);

        return $encodedValue;
    }

    /**
     * Decode and decrypt a value.
     *
     * @param string $encodedValue
     * @return string
     */
    public function decode($encodedValue)
    {
        $encryptedValue = base64_decode($encodedValue);
        $value = $this->getEncryptor()->decrypt($encryptedValue);

        return $value;
    }

    /**
     * Set environment variable.
     *
     * @param string $name
     * @param $value
     */
    protected function setEnvironmentVariable($name, $value)
    {
        $result = putenv("$name=$value");

        if ($result === false) {
            throw new Exception\RuntimeException(
                sprintf('Failed to set environment variable "%s"', $name)
            );
        }
    }

    /**
     * Get environment variable.
     *
     * @param string $name
     * @return string|null
     */
    protected function getEnvironmentVariable($name)
    {
        $value = getenv($name);

        if ($value === false) {
            return null;
        }

        return $value;
    }
}
