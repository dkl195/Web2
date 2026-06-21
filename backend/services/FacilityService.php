<?php
/**
 * FacilityService - Business logic Module 2: Facility Management
 *
 * Flow Admin tạo Facility:
 *   1. Validate (FacilityValidator)
 *   2. INSERT facilities
 *   3. INSERT facility_rules (mỗi facility có rule riêng)
 *
 * Business rules:
 *   - Tên facility không trùng tại cùng location (UNIQUE constraint)
 *   - Facility có booking → không xóa cứng, chuyển unavailable
 */
class FacilityService
{
    private FacilityModel $facilityModel;
    private FacilityRuleModel $ruleModel;
    private FacilityCategoryModel $categoryModel;
    private BookingDetailModel $detailModel;

    public function __construct()
    {
        $this->facilityModel = new FacilityModel();
        $this->ruleModel = new FacilityRuleModel();
        $this->categoryModel = new FacilityCategoryModel();
        $this->detailModel = new BookingDetailModel();
    }

    // --- Category CRUD ---
    public function getAllCategories(): array
    {
        return $this->categoryModel->findAll();
    }

    public function getCategoriesSimple(): array
    {
        return $this->categoryModel->findAllSimple();
    }

    public function saveCategory(?int $id, string $name, string $description): array
    {
        $errors = FacilityValidator::validateCategory($name);
        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            if ($id) {
                $this->categoryModel->update($id, $name, $description);
            } else {
                $this->categoryModel->create($name, $description);
            }
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'errors' => ['Category name already exists.']];
        }
    }

    public function deleteCategory(int $id): array
    {
        if ($this->categoryModel->countFacilities($id) > 0) {
            return ['success' => false, 'message' => 'Cannot delete category with existing facilities.'];
        }
        $this->categoryModel->delete($id);
        return ['success' => true, 'message' => 'Category deleted.'];
    }

    // --- Facility CRUD ---
    public function getAllFacilities(): array
    {
        return $this->facilityModel->findAllWithCategory();
    }

    public function getAvailableFacilities(?int $categoryId = null, ?string $keyword = null): array
    {
        return $this->facilityModel->findAvailable($categoryId, $keyword);
    }

    public function getFacilityWithRule(int $id): ?array
    {
        return $this->facilityModel->findWithRule($id);
    }

    public function getAvailableFacility(int $id): ?array
    {
        return $this->facilityModel->findAvailableById($id);
    }

    public function saveFacility(?int $id, array $data): array
    {
        $errors = FacilityValidator::validate($data);
        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            if ($id) {
                $this->facilityModel->update($id, $data);
                $this->ruleModel->upsert($id, $data['max_booking_hours'], $data['min_notice_hours'], $data['requires_approval']);
            } else {
                $newId = $this->facilityModel->create($data);
                $this->ruleModel->create($newId, $data['max_booking_hours'], $data['min_notice_hours'], $data['requires_approval']);
            }
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'errors' => ['Facility name already exists at this location.']];
        }
    }

    public function deleteFacility(int $id): array
    {
        if ($this->detailModel->countByFacility($id) > 0) {
            $this->facilityModel->setUnavailable($id);
            return ['success' => true, 'message' => 'Facility has bookings. Status set to unavailable.'];
        }
        $this->facilityModel->delete($id);
        return ['success' => true, 'message' => 'Facility deleted.'];
    }

    // --- Rules ---
    public function getAllRules(): array
    {
        return $this->ruleModel->findAllWithFacility();
    }

    public function updateRule(int $facilityId, int $maxHours, int $minNotice, int $requiresApproval): void
    {
        $this->ruleModel->upsert($facilityId, $maxHours, $minNotice, $requiresApproval);
    }

    public function countFacilities(): int
    {
        return $this->facilityModel->countAll();
    }
}