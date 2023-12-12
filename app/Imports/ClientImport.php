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

class ClientImport implements ToCollection
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
        $errors = [];

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

            try {
                $client = Client::query()->firstOrNew(['external_id' => $row[0]]);
                if (!empty($client->id) && $client->updated_at > $client->created_at) {
                    continue;
                }

                if (!empty($row[1])) {
                    $client->name = $row[1];
                }
                if (!empty($row[2]) && $row[2] !== 'FALTA' && $row[2] != '999999990') {
                    $client->document = $row[2];
                }
                if (!empty($row[3]) && $row[3] != '1900-01-01 00:00:00.000') {
                    $birthday = substr($row[3], 0, 10);
                    $client->birthdate = $birthday;
                }
                if (!empty($row[4])) {
                    $client->address = $row[4];
                }
                if (!empty($row[5])) {
                    $client->postcode = $row[5];
                }

                $locality = $this->getLocality($row);
                if (!empty($locality)) {
                    $client->locality_id = $locality->id;
                }

                if (!empty($row[8])) {
                    $client->email = $row[8];
                }
                if (!empty($row[12])) {
                    $client->phone = $row[12];
                }

                $client->opt_in = false;
                $client->save();

                dump('Processed Row ' . ($index + 1) . ' Id: ' . $row[0]);
            } catch (\Exception $e) {
                dump('Error ' . $row[0]);
                $errors[] = 'Fila ' . ($index + 1) . ' Id: ' . $e->getMessage();
            }
        }

        dump('Process done!');

        logger('Errors', $errors);
        dump('Errors', $errors);
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
                'province_id' => $localities->first()->province_id,
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
