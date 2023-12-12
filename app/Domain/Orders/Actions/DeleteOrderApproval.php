<?php

namespace Domain\Orders\Actions;

use Domain\Orders\Contracts\Repositories\OrdersApprovalRepository;
use Domain\Orders\Models\OrderApproval;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteOrderApproval
{

    /**
     * @param OrdersApprovalRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly OrdersApprovalRepository $repository,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return OrderApproval
     * @throws ValidationException
     */
    public function __invoke(array $data): OrderApproval
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:orders_approval,id'
        ];
    }
}
