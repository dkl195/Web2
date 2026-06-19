<?php
/** Model bảng: facility_rules (1 facility - 1 rule) */
class FacilityRuleModel extends BaseModel
{
    public function findAllWithFacility(): array
    {
        return $this->db->query("
            SELECT f.facility_id, f.facility_name, f.location,
                   fr.rule_id, fr.max_booking_hours, fr.min_notice_hours, fr.requires_approval
            FROM facilities f
            LEFT JOIN facility_rules fr ON f.facility_id = fr.facility_id
            ORDER BY f.facility_name
        ")->fetchAll();
    }

    public function findByFacilityId(int $facilityId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM facility_rules WHERE facility_id = ?");
        $stmt->execute([$facilityId]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $facilityId, int $maxHours, int $minNotice, int $requiresApproval): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO facility_rules (facility_id, max_booking_hours, min_notice_hours, requires_approval)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$facilityId, $maxHours, $minNotice, $requiresApproval]);
    }

    public function update(int $facilityId, int $maxHours, int $minNotice, int $requiresApproval): bool
    {
        $stmt = $this->db->prepare("
            UPDATE facility_rules SET max_booking_hours=?, min_notice_hours=?, requires_approval=? WHERE facility_id=?
        ");
        return $stmt->execute([$maxHours, $minNotice, $requiresApproval, $facilityId]);
    }

    public function upsert(int $facilityId, int $maxHours, int $minNotice, int $requiresApproval): bool
    {
        if ($this->findByFacilityId($facilityId)) {
            return $this->update($facilityId, $maxHours, $minNotice, $requiresApproval);
        }
        return $this->create($facilityId, $maxHours, $minNotice, $requiresApproval);
    }
}
