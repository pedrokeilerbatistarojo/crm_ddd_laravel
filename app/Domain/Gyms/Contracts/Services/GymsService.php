<?php

namespace Domain\Gyms\Contracts\Services;

use Domain\Gyms\DataTransferObjects\GymFeeTypeEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaEntity;
use Domain\Gyms\DataTransferObjects\GymFeeTypeSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaSearchRequest;
use Domain\Gyms\DataTransferObjects\GymFeeTypeSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaSearchResponse;

interface GymsService
{
    /**
     * @param array $data
     * @return GymFeeTypeEntity|null
     */
    public function createGymFeeType(array $data): ?GymFeeTypeEntity;

    /**
     * @param array $data
     * @return GymSubscriptionEntity|null
     */
    public function createGymSubscription(array $data): ?GymSubscriptionEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberEntity|null
     */
    public function createGymSubscriptionMember(array $data): ?GymSubscriptionMemberEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessEntity|null
     */
    public function createGymSubscriptionMemberAccess(array $data): ?GymSubscriptionMemberAccessEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRightEntity|null
     */
    public function createGymSubscriptionMemberAccessRight(array $data): ?GymSubscriptionMemberAccessRightEntity;

    /**
     * @param array $data
     * @return GymSubscriptionQuotaEntity|null
     */
    public function createGymSubscriptionQuota(array $data): ?GymSubscriptionQuotaEntity;


    /**
     * @param int $id
     * @param array $includes
     * @return GymFeeTypeEntity|null
     */
    public function findGymFeeType(int $id, array $includes = []): ?GymFeeTypeEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return GymSubscriptionEntity|null
     */
    public function findGymSubscription(int $id, array $includes = []): ?GymSubscriptionEntity;
    
    /**
     * @param int $id
     * @param array $includes
     * @return GymSubscriptionMemberEntity|null
     */
    public function findGymSubscriptionMember(int $id, array $includes = []): ?GymSubscriptionMemberEntity;
    
    /**
     * @param int $id
     * @param array $includes
     * @return GymSubscriptionMemberAccessEntity|null
     */
    public function findGymSubscriptionMemberAccess(int $id, array $includes = []): ?GymSubscriptionMemberAccessEntity;
    
    /**
     * @param int $id
     * @param array $includes
     * @return GymSubscriptionMemberAccessRightEntity|null
     */
    public function findGymSubscriptionMemberAccessRight(int $id, array $includes = []): ?GymSubscriptionMemberAccessRightEntity;
    
    /**
     * @param int $id
     * @param array $includes
     * @return GymSubscriptionQuotaEntity|null
     */
    public function findGymSubscriptionQuota(int $id, array $includes = []): ?GymSubscriptionQuotaEntity;


    /**
     * @param GymFeeTypeSearchRequest $request
     * @return GymFeeTypeSearchResponse
     */
    public function searchGymFeeTypes(GymFeeTypeSearchRequest $request): GymFeeTypeSearchResponse;

    /**
     * @param GymSubscriptionSearchRequest $request
     * @return GymSubscriptionSearchResponse
     */
    public function searchGymSubscriptions(GymSubscriptionSearchRequest $request): GymSubscriptionSearchResponse;

    /**
     * @param GymSubscriptionMemberSearchRequest $request
     * @return GymSubscriptionMemberSearchResponse
     */
    public function searchGymSubscriptionMembers(GymSubscriptionMemberSearchRequest $request): GymSubscriptionMemberSearchResponse;

    /**
     * @param GymSubscriptionMemberAccessSearchRequest $request
     * @return GymSubscriptionMemberAccessSearchResponse
     */
    public function searchGymSubscriptionMemberAccess(GymSubscriptionMemberAccessSearchRequest $request): GymSubscriptionMemberAccessSearchResponse;

    /**
     * @param GymSubscriptionMemberAccessRightSearchRequest $request
     * @return GymSubscriptionMemberAccessRightSearchResponse
     */
    public function searchGymSubscriptionMemberAccessRights(GymSubscriptionMemberAccessRightSearchRequest $request): GymSubscriptionMemberAccessRightSearchResponse;

    /**
     * @param GymSubscriptionQuotaSearchRequest $request
     * @return GymSubscriptionQuotaSearchResponse
     */
    public function searchGymSubscriptionQuotas(GymSubscriptionQuotaSearchRequest $request): GymSubscriptionQuotaSearchResponse;


    /**
     * @param array $data
     * @return GymFeeTypeEntity|null
     */
    public function updateGymFeeType(array $data): ?GymFeeTypeEntity;

    /**
     * @param array $data
     * @return GymSubscriptionEntity|null
     */
    public function updateGymSubscription(array $data): ?GymSubscriptionEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberEntity|null
     */
    public function updateGymSubscriptionMember(array $data): ?GymSubscriptionMemberEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessEntity|null
     */
    public function updateGymSubscriptionMemberAccess(array $data): ?GymSubscriptionMemberAccessEntity;

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRightEntity|null
     */
    public function updateGymSubscriptionMemberAccessRight(array $data): ?GymSubscriptionMemberAccessRightEntity;

    /**
     * @param array $data
     * @return GymSubscriptionQuotaEntity|null
     */
    public function updateGymSubscriptionQuota(array $data): ?GymSubscriptionQuotaEntity;
}
