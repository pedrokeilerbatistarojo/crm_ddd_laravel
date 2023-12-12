<?php

namespace Domain\Users\Actions;

use Domain\Users\Contracts\Repositories\UsersRepository;
use Domain\Users\Models\User;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteUser
{
    private UsersRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param UsersRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        UsersRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function __invoke(array $data): User
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:users'
        ];
    }
}
