<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $workorder_id
 * @property string $acquisition_date
 * @property numeric $estimated_cost
 * @property int|null $supplier_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereAcquisitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcquisitionWorkorder whereWorkorderId($value)
 */
	class AcquisitionWorkorder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $asset_code
 * @property string $name
 * @property string|null $serial_name
 * @property \App\Enums\AssetStatus $status
 * @property string|null $description
 * @property int $is_depreciable
 * @property string|null $image_path
 * @property int|null $category_id
 * @property int|null $department_id
 * @property int|null $sub_category_id
 * @property int|null $supplier_id
 * @property int|null $custodian_id
 * @property \Illuminate\Support\Carbon|null $acquisition_date
 * @property numeric $cost
 * @property numeric $salvage_value
 * @property int|null $useful_life_in_years
 * @property \Illuminate\Support\Carbon|null $end_of_life_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Employee|null $custodian
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\DisposalWorkorder|null $disposalWorkorder
 * @property-read mixed $book_value
 * @property-read mixed $computed_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $requests
 * @property-read int|null $requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceWorkorder> $serviceWorkorders
 * @property-read int|null $service_workorders_count
 * @property-read \App\Models\SubCategory|null $subCategory
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAcquisitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAssetCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCustodianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereEndOfLifeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereIsDepreciable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSalvageValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSerialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUsefulLifeInYears($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset withoutTrashed()
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $requests
 * @property-read int|null $requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubCategory> $subCategories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $workorder_id
 * @property int|null $asset_id
 * @property \App\Enums\DisposalMethods $disposal_method
 * @property string $disposal_date
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereDisposalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereDisposalMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereWorkorderId($value)
 */
	class DisposalWorkorder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $first_name
 * @property string $last_name
 * @property string $id
 * @property int $department_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \App\Models\Department $department
 * @property-read string $full_name
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $request_code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date_requested
 * @property string|null $date_approved
 * @property string|null $asset_name
 * @property int $requested_by
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $approved_by
 * @property int|null $asset_id
 * @property \App\Enums\RequestTypes $type
 * @property \App\Enums\ServiceTypes|null $service_type
 * @property \App\Enums\RequestStatus $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $requestedBy
 * @property-read \App\Models\SubCategory|null $subCategory
 * @property-read \App\Models\Workorder|null $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereAssetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDateApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDateRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereRequestCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request withoutTrashed()
 */
	class Request extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $workorder_id
 * @property int|null $asset_id
 * @property \App\Enums\ServiceTypes $service_type
 * @property numeric $cost
 * @property string $done_by
 * @property int $is_vehicle
 * @property string $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereDoneBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereIsVehicle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereWorkorderId($value)
 */
	class ServiceWorkorder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $requests
 * @property-read int|null $requests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereUpdatedAt($value)
 */
	class SubCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AcquisitionWorkorder> $acquistionWorkOrders
 * @property-read int|null $acquistion_work_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $is_active
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $employee_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $approvedRequests
 * @property-read int|null $approved_requests_count
 * @property-read \App\Models\Employee $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $requestedRequests
 * @property-read int|null $requested_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $workorder_code
 * @property int|null $request_id
 * @property int|null $completed_by
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \App\Enums\PriorityLevel $priority_level
 * @property \App\Enums\WorkorderType $type
 * @property \App\Enums\WorkorderStatus $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AcquisitionWorkorder|null $acquisitionWorkorder
 * @property-read \App\Models\DisposalWorkorder|null $disposalWorkOrder
 * @property-read \App\Models\Request|null $request
 * @property-read \App\Models\ServiceWorkorder|null $serviceWorkorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereCompletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder wherePriorityLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereWorkorderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder withoutTrashed()
 */
	class Workorder extends \Eloquent {}
}

