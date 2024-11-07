<?php

namespace App\Services\interface;

interface BillServiceInterface
{
    function getById(string $id);
    function getAll($timeFrame = null, $page = 1, $limit = 50);
    function getAllByUser(string $userId, $timeFrame = null, $page = 1, $limit = 10);
    function createBill(string $userId, float $amount, string $companyId);
}
