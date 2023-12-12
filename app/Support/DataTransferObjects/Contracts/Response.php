<?php

namespace Support\DataTransferObjects\Contracts;

use Support\Exceptions\InvalidStatusException;

interface Response
{
    public const STATUSES = [
        'OK' => 'Ok',
        'ERROR' => 'Error',
    ];

    /**
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * @return ?array
     */
    public function getErrors(): ?array;

    /**
     * @return mixed
     */
    public function getMetaData(): mixed;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isError(): bool;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): static;

    /**
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors(array $errors): static;

    /**
     * @param mixed $metaData
     *
     * @return $this
     */
    public function setMetaData(mixed $metaData): static;

    /**
     * @param string $status
     *
     * @return $this
     * @throws InvalidStatusException
     */
    public function setStatus(string $status): static;

    /**
     * @return array
     */
    public function toArray(): array;
}
