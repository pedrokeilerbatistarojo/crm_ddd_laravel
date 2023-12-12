<?php

namespace Support\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Exceptions\InvalidStatusException;

abstract class Response extends DataTransferObject implements Contracts\Response
{
    /**
     * @var mixed
     */
    protected mixed $data;

    /**
     * @var array
     */
    protected array $errors;

    /**
     * @var mixed
     */
    protected mixed $metaData;

    /**
     * @var string
     */
    protected string $status;

    /**
     * @param string $status
     * @param mixed|null $data
     * @param mixed|null $metaData
     * @param array $errors
     *
     * @throws InvalidStatusException
     * @throws UnknownProperties
     */
    public function __construct(string $status, mixed $data = null, mixed $metaData = null, array $errors = [])
    {
        parent::__construct();

        $this->setErrors($errors)->setStatus($status)->setData($data)->setMetaData($metaData);
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getStatus() === self::STATUSES['ERROR'];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     * @throws InvalidStatusException
     */
    public function setStatus(string $status): static
    {
        if (!in_array($status, array_values(static::STATUSES), true)) {
            throw new InvalidStatusException('Invalid status.');
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatus() === self::STATUSES['OK'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'data' => $this->getData(),
            'metadata' => $this->getMEtaData(),
            'errors' => $this->getErrors(),
        ];
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return ?array
     */
    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    /**
     * @param mixed $metaData
     *
     * @return $this
     */
    public function setMetaData(mixed $metaData): static
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return $this
     * @throws InvalidStatusException
     */
    public function setErrors(array $errors): static
    {
        $this->setStatus(self::STATUSES['ERROR']);

        $this->errors = $errors;

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidStatusException
     */
    public function setSuccess(): static
    {
        $this->setStatus(self::STATUSES['OK']);

        return $this;
    }
}
