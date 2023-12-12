<?php

namespace Domain\Users\Actions;

use Domain\Users\Contracts\Repositories\UsersRepository;
use Domain\Users\Models\User;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertUser
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): User
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $method = array_key_exists('id', $data) ? 'edit' : 'add';

        return $this->repository->$method($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email',
            'default_company_id' => 'required|numeric',
            'password_confirmation' => 'required_with:password',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:users';
            $rules['username'] .= ',' . $data['id'] . ',id';
            $rules['password'] = 'nullable|confirmed';
        } else {
            $rules['password'] = 'required|confirmed';
        }

        return $rules;
    }
}
