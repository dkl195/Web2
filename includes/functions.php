<?php
/**
 * @deprecated Logic đã chuyển sang backend/services/ và backend/validators/
 * File này giữ lại wrapper cho code View cũ.
 */
require_once __DIR__ . '/auth.php';

function validateBooking(PDO $pdo, array $data): array
{
    $facilityModel = new FacilityModel();
    $facility = $facilityModel->findWithRule((int) $data['facility_id']);
    $validator = new BookingValidator();
    return $validator->validate($data, $facility);
}

function hasBookingConflict(PDO $pdo, int $facilityId, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
{
    $detailModel = new BookingDetailModel();
    return $detailModel->hasConflict($facilityId, $date, $startTime, $endTime, $excludeBookingId);
}

function generateBookingCode(PDO $pdo): string
{
    $bookingModel = new BookingModel();
    return $bookingModel->generateCode();
}

function getStatusIdByCode(PDO $pdo, string $code): ?int
{
    $statusModel = new BookingStatusModel();
    return $statusModel->getIdByCode($code);
}
