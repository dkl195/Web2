<?php
/**
 * FacilityValidator - Kiểm tra dữ liệu facility (Module 2)
 */
class FacilityValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (trim($data['facility_name'] ?? '') === '') {
            $errors[] = 'Facility name is required.';
        }
        if (trim($data['location'] ?? '') === '') {
            $errors[] = 'Location is required.';
        }

        // Rule: capacity > 0
        if ((int) ($data['capacity'] ?? 0) <= 0) {
            $errors[] = 'Capacity must be greater than 0.';
        }

        // Rule: open_time < close_time
        if (($data['open_time'] ?? '') >= ($data['close_time'] ?? '')) {
            $errors[] = 'Open time must be before close time.';
        }

        return $errors;
    }

    public static function validateCategory(string $name): array
    {
        return trim($name) === '' ? ['Category name is required.'] : [];
    }
}
