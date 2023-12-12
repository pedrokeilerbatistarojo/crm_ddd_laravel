<?php

namespace Support\Core\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class FractalDataArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return ($resourceKey) ? array($resourceKey => $data) : $data;
    }

    public function item($resourceKey, array $data): array
    {
        return ($resourceKey) ? array($resourceKey => $data) : $data;
    }
}
