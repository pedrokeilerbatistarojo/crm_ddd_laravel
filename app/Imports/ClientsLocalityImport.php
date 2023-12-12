<?php

namespace app\Imports;

use Normalizer;
use Domain\Clients\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Localities\Contracts\Services\LocalitiesService;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Domain\Localities\DataTransferObjects\LocalitySearchRequest;

class ClientsLocalityImport implements ToCollection
{
    /**
     * @var ClientsService
     */
    protected ClientsService $clientsService;
    /**
     * @var LocalitiesService
     */
    protected LocalitiesService $localitiesService;

    /**
     * @param ClientsService $clientsService
     * @param LocalitiesService $localitiesService
     */
    public function __construct(ClientsService $clientsService, LocalitiesService $localitiesService)
    {
        $this->clientsService = $clientsService;
        $this->localitiesService = $localitiesService;
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $index => $row) {
            if ($index < 1) {
                continue;
            }
            if (empty($row[0])) {
                continue;
            }
            if (empty($row[1])) {
                continue;
            }

            if (empty($row[5])) {
                continue;
            }
            try {
                $client = Client::query()->where(['external_id' => $row[0]])->first();

                if (empty($client)) {
                    continue;
                }

                if (!empty($client->locality_id)) {
                    continue;
                }

                $locality = $this->getLocality($row);
                if (!empty($locality)) {
                    $client->locality_id = $locality->id;
                }

                $client->save();

                dump('Processed Row ' . ($index + 1) . ' Id: ' . $row[0]);
            } catch (\Exception $e) {
                dump('Error ' . $row[0]);
                $errors[] = 'Fila ' . ($index + 1) . ' Id: ' . $e->getMessage();
            }
        }

        dump('Process done!');
    }

    private function getLocality($row)
    {
        if (!$row[5]) {
            return null;
        }

        $clientZipCode = (str_pad($row[5], 5, '0', STR_PAD_LEFT));

        $localities = $this->localitiesService->search(
            new LocalitySearchRequest([
                'filters' => ['zip_code' => $clientZipCode],
                'includes' => [],
                'paginateSize' => 1000
            ])
        )->getData();

        if (!$localities->count()) {
            return $this->localitiesService->createLocality([
                'zip_code' => (string) $clientZipCode,
                'locality' => $row[6],
                'singular_entity_name' => $row[6],
                'population_unit_code' => (string) $clientZipCode,
                'population' => $row[6],
                'municipio_id' => (string) $clientZipCode,
                'province_id' => (int) substr('04007', 0, 2),
            ]);
        }

        if ($localities->count() === 1) {
            $locality = $localities->first();
        } else {
            $locality = $localities->first(
                fn ($locality) => $this->prepareStringToCompare($locality->population) === $this->prepareStringToCompare($row[6])
            );
        }

        if (empty($locality)) {
            $locality = $this->localitiesService->createLocality([
                'zip_code' => (string) $clientZipCode,
                'locality' => $row[6],
                'singular_entity_name' => $row[6],
                'population_unit_code' => (string) $clientZipCode,
                'population' => $row[6],
                'municipio_id' => (string) $clientZipCode,
                'province_id' => $localities->first()->province_id,
            ]);
        }

        return $locality;
    }

    private function prepareStringToCompare($str)
    {
        $str = $this->removeAccents($str);
        $str = preg_replace('/[^A-Za-z0-9. -]/', '', $str);
        $str = preg_replace('/  */', '-', $str);

        return strtolower($str);
    }

    private function removeAccents($str)
    {
        return preg_replace('/[\x{0300}-\x{036f}]/u', "", normalizer_normalize($str, Normalizer::FORM_D));
    }
}
