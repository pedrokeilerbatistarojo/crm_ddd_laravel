<?php

namespace Domain\Clients\Contracts\Services;

use Domain\Clients\DataTransferObjects\ClientSendConsentEmailResponse;
use Domain\Clients\DataTransferObjects\ClientDownloadTemplatePdfResponse;
use Domain\Clients\DataTransferObjects\ClientEntity;
use Domain\Clients\DataTransferObjects\ClientFileContentResponse;
use Domain\Clients\DataTransferObjects\ClientFileEntity;
use Domain\Clients\DataTransferObjects\ClientFileSearchRequest;
use Domain\Clients\DataTransferObjects\ClientFileSearchResponse;
use Domain\Clients\DataTransferObjects\ClientNoteEntity;
use Domain\Clients\DataTransferObjects\ClientNoteSearchRequest;
use Domain\Clients\DataTransferObjects\ClientNoteSearchResponse;
use Domain\Clients\DataTransferObjects\ClientSearchRequest;
use Domain\Clients\DataTransferObjects\ClientSearchResponse;
use Domain\Clients\Models\Client;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

interface ClientsService
{
    /**
     * @param array $data
     * @return ClientEntity|null
     */
    public function create(array $data): ?ClientEntity;

    /**
     * @param array $data
     * @return ClientFileEntity|null
     */
    public function createFile(array $data): ?ClientFileEntity;

    /**
     * @param array $data
     * @return ClientNoteEntity|null
     */
    public function createNote(array $data): ?ClientNoteEntity;

    /**
     * @param array $data
     * @return ClientEntity|null
     */
    public function delete(array $data): ?ClientEntity;

    /**
     * @param array $data
     * @return ClientFileEntity|null
     */
    public function deleteFile(array $data): ?ClientFileEntity;

    /**
     * @param array $data
     * @return ClientNoteEntity|null
     */
    public function deleteNote(array $data): ?ClientNoteEntity;

    /**
     * @param ClientSearchRequest $request
     * @return string|null
     */
    public function exportExcel(ClientSearchRequest $request): ?string;

    /**
     * @param ClientSearchRequest $request
     * @return ClientSearchResponse
     */
    public function duplicates(ClientSearchRequest $request): ClientSearchResponse;

    /**
     * @param int $id
     * @return ClientFileContentResponse
     * @throws UnknownProperties
     */
    public function fileContent(int $id): ClientFileContentResponse;

    /**
     * @param int $id
     * @param array $includes
     * @return ClientEntity|null
     */
    public function find(int $id, array $includes = []): ?ClientEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return ClientFileEntity|null
     */
    public function findFile(int $id, array $includes = []): ?ClientFileEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return ClientNoteEntity|null
     */
    public function findNote(int $id, array $includes = []): ?ClientNoteEntity;

    /**
     * @param ClientSearchRequest $request
     * @return ClientSearchResponse
     */
    public function search(ClientSearchRequest $request): ClientSearchResponse;

    /**
     * @param ClientFileSearchRequest $request
     * @return ClientFileSearchResponse
     */
    public function searchFiles(ClientFileSearchRequest $request): ClientFileSearchResponse;

    /**
     * @param ClientNoteSearchRequest $request
     * @return ClientNoteSearchResponse
     */
    public function searchNotes(ClientNoteSearchRequest $request): ClientNoteSearchResponse;

    /**
     * @param array $data
     * @return ClientEntity|null
     */
    public function update(array $data): ?ClientEntity;

    /**
     * @param array $data
     * @return ClientFileEntity|null
     */
    public function updateFile(array $data): ?ClientFileEntity;

    /**
     * @param array $data
     * @return ClientNoteEntity|null
     */
    public function updateNote(array $data): ?ClientNoteEntity;

    public function downloadTemplate(Client $client): ClientDownloadTemplatePdfResponse;

    public function sendConsentEmail(int $id, string $email): ClientSendConsentEmailResponse;
}
