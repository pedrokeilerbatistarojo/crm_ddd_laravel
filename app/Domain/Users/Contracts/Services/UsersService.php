<?php

namespace Domain\Users\Contracts\Services;

use Domain\Users\DataTransferObjects\UserEntity;
use Domain\Users\DataTransferObjects\UserSearchRequest;
use Domain\Users\DataTransferObjects\UserSearchResponse;

interface UsersService
{
    /**
     * @param array $data
     * @return UserEntity|null
     */
    public function create(array $data): ?UserEntity;

    /**
     * @param array $data
     * @return UserEntity|null
     */
    public function delete(array $data): ?UserEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return UserEntity|null
     */
    public function find(int $id, array $includes = []): ?UserEntity;

    /**
     * @param UserSearchRequest $request
     * @return UserSearchResponse
     */
    public function search(UserSearchRequest $request): UserSearchResponse;

    /**
     * @param array $data
     * @return UserEntity|null
     */
    public function update(array $data): ?UserEntity;
}
