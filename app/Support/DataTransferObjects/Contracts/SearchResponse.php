<?php

namespace Support\DataTransferObjects\Contracts;

interface SearchResponse extends Response
{
    /**"
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): static;

    /**
     * @return array
     */
    public function getMeta(): array;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setMeta(array $data): static;
}
