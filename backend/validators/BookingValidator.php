<?php
/**
 * BookingValidator - Kiểm tra business rules khi tạo booking (Module 3)
 *
 * Đây là file quan trọng khi defense — mỗi rule đều có comment giải thích.
 */
class BookingValidator
{
    private BookingDetailModel $detailModel;

    public function __construct()
    {
        $this->detailModel = new BookingDetailModel();
    }

    /**
     * Validate toàn bộ booking trước khi INSERT vào database
     *
     * @param array $data  facility_id, booking_date, start_time, end_time, participant_count, purpose
     * @param array|null $facility  facility kèm rule (từ FacilityModel::findWithRule)
     */
    public function validate(array $data, ?array $facility): array
    {
        $errors = [];

        // --- Rule 1: Facility phải tồn tại ---
        if (!$facility) {
            $errors[] = 'Facility not found.';
            return $errors;
        }

        // --- Rule 2: Facility phải available ---
        if ($facility['facility_status'] !== 'available') {
            $errors[] = 'This facility is currently unavailable for booking.';
        }

        $date = $data['booking_date'] ?? '';
        $start = $data['start_time'] ?? '';
        $end = $data['end_time'] ?? '';
        $participants = (int) ($data['participant_count'] ?? 0);

        // --- Rule 3: Không đặt thời gian quá khứ ---
        if (strtotime("$date $start") < time()) {
            $errors[] = 'Cannot book a time in the past.';
        }

        // --- Rule 4: Start time < End time ---
        if ($start >= $end) {
            $errors[] = 'Start time must be before end time.';
        }

        // --- Rule 5: Trong giờ mở cửa của facility ---
        if ($start < $facility['open_time'] || $end > $facility['close_time']) {
            $errors[] = 'Booking time must be within facility operating hours ('
                . formatTime($facility['open_time']) . ' - ' . formatTime($facility['close_time']) . ').';
        }

        // --- Rule 6: Số người <= capacity ---
        if ($participants <= 0) {
            $errors[] = 'Number of participants must be greater than 0.';
        } elseif ($participants > (int) $facility['capacity']) {
            $errors[] = 'Number of participants exceeds facility capacity (' . $facility['capacity'] . ').';
        }

        // --- Rule 7: Không vượt max_booking_hours (từ facility_rules) ---
        $durationHours = (strtotime("$date $end") - strtotime("$date $start")) / 3600;
        $maxHours = (int) ($facility['max_booking_hours'] ?? 3);
        if ($durationHours > $maxHours) {
            $errors[] = "Maximum booking duration is {$maxHours} hour(s).";
        }

        // --- Rule 8: Phải đặt trước min_notice_hours ---
        $minNotice = (int) ($facility['min_notice_hours'] ?? 0);
        $hoursUntil = (strtotime("$date $start") - time()) / 3600;
        if ($hoursUntil < $minNotice) {
            $errors[] = "You must book at least {$minNotice} hour(s) in advance.";
        }

        // --- Rule 9: Không trùng lịch (PENDING + APPROVED) ---
        if ($this->detailModel->hasConflict(
            (int) $data['facility_id'],
            $date,
            $start,
            $end,
            $data['exclude_booking_id'] ?? null
        )) {
            $errors[] = 'Booking conflict: this time slot is already taken.';
        }

        // --- Rule 10: Purpose bắt buộc ---
        if (trim($data['purpose'] ?? '') === '') {
            $errors[] = 'Purpose is required.';
        }

        return $errors;
    }
}
