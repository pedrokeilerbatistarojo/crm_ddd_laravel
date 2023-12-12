<?php

namespace Support\DataTransferObjects;

abstract class SearchResponse extends Response implements Contracts\SearchResponse
{
    /**
     * @var array
     */
    protected array $meta;

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setMeta(array $data): static
    {
        $this->meta = $data;

        return $this;
    }
}
