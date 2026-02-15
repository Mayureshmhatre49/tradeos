<?php

namespace App\Services;

use App\Repositories\ShipmentRepository;
use App\Models\Shipment;
use Illuminate\Pagination\LengthAwarePaginator;

class ShipmentService
{
    protected $repository;

    public function __construct(ShipmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllShipments(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function createShipment(array $data): Shipment
    {
        $data['risk_flag'] = $this->assessRisk($data);
        return $this->repository->create($data);
    }

    public function updateShipment(Shipment $shipment, array $data): Shipment
    {
        if (isset($data['port_of_loading']) || isset($data['port_of_discharge'])) {
             $riskData = array_merge($shipment->toArray(), $data);
             $data['risk_flag'] = $this->assessRisk($riskData);
        }
        return $this->repository->update($shipment, $data);
    }

    protected function assessRisk(array $data): bool
    {
        // Simple risk logic: Flags if origin/destination are high risk (Mocked list)
        $highRiskPorts = ['Test Port', 'Embargoed Port'];
        
        if (in_array($data['port_of_loading'], $highRiskPorts) || in_array($data['port_of_discharge'], $highRiskPorts)) {
            return true;
        }

        return false;
    }

    public function findByUuid(string $uuid): ?Shipment
    {
        return $this->repository->findByUuid($uuid);
    }

    public function deleteShipment(Shipment $shipment): bool
    {
        return $this->repository->delete($shipment);
    }
}
