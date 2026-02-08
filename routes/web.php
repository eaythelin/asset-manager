<?php
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\System\AssetsController;
use App\Http\Controllers\System\CategoriesController;
use App\Http\Controllers\System\DepartmentsController;
use App\Http\Controllers\System\EmployeesController;
use App\Http\Controllers\System\RequestsController;
use App\Http\Controllers\System\SubCategoriesController;
use App\Http\Controllers\System\SuppliersController;
use App\Http\Controllers\System\UsersController;
use App\Http\Controllers\System\DashboardController;
use App\Http\Controllers\System\WorkordersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //Dashboard
    Route::group(["prefix" => "/dashboard", "middleware" => "check.permission:view dashboard"], function(){
      Route::get('/', [DashboardController::class, 'getDashboard'])->name('dashboard.index');
      Route::get('/subcategories/{category}', [DashboardController::class, 'getSubcategoryCount'])->name('subcategorycount.get');
      Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chartData');
    });

    //Assets
    Route::group(["prefix" => "/assets", "middleware" => "check.permission:view assets"], function(){
      Route::get('/', [AssetsController::class, 'getAssets'])->name('assets.index');
      //static routes before dynamic routes!
      Route::group(["prefix" => "/create", "middleware" => "check.permission:manage assets"], function(){
        Route::get('/', [AssetsController::class, 'getCreateAsset'])->name('assets.create');
        Route::post('/store', [AssetsController::class, "storeAsset"])->name("assets.store");
      });

      Route::group(["prefix" => "/edit", "middleware" => "check.permission:manage assets"], function(){
        Route::get('/{id}', [AssetsController::class, 'getEditAsset'])->name('assets.edit');
        Route::put('/{id}/update', [AssetsController::class, 'updateAsset'])->name('assets.update');
      });

      //individual page
      Route::get('/{id}', [AssetsController::class, 'getAsset'])->name('assets.show');

      Route::get('/subcategories/{categoryID}', [AssetsController::class, 'getSubcategories'])
        ->middleware('check.permission:manage assets')
        ->name('assets.subcategories.get');
      
      Route::delete('/{id}/dispose', [AssetsController::class, 'disposeAsset'])
        ->middleware('check.permission:manage assets')
        ->name('assets.dispose');
    });

    //Requests
    Route::group(["prefix" => "/requests", "middleware" => "check.permission:view requests"], function(){
      Route::get('/', [RequestsController::class, "getRequests"])->name('requests.index');
      Route::group(["prefix" => "/create", "middleware" => "check.permission:view requests"], function(){
        Route::get('/', [RequestsController::class, "getCreateRequest"])->name('requests.create');
        Route::post('/', [RequestsController::class, "storeRequest"])->name('requests.store');
      });

      Route::get('/subcategories/{categoryID}', [RequestsController::class, 'getSubcategories'])
        ->middleware('check.permission:view requests')
        ->name('requests.subcategories.get');
    });

    //Workorders
    Route::group(["prefix" => "/workorders", "middleware" => "check.permission:view workorders"], function(){
      Route::get('/', [WorkordersController::class, 'getWorkOrders'])->name('workorders.index');
    });

    //employees
    Route::group(["prefix" => "/employees", "middleware" => "check.permission:view employees"], function(){
      Route::get('/', [EmployeesController::class, "getEmployees"])->name('employees.index');
      Route::get('/{id}',[EmployeesController::class,"getEmployee"])->name('employees.show');
      Route::middleware('check.permission:manage employees')->group(function(){
        Route::post('/', [EmployeesController::class, 'storeEmployees'])->name('employees.store');
        Route::put('/{id}', [EmployeesController::class, 'updateEmployee'])->name('employees.update');
        Route::delete('/{id}',[EmployeesController::class, 'deleteEmployee'])->name('employees.delete');
      });
    });

    //Configurations!
    Route::group(["prefix" => "/configs"], function(){
      //Departments
      Route::group(['prefix' => "/departments", "middleware" => "check.permission:view categories"], function(){
        Route::get('/', [DepartmentsController::class, 'getDepartments'])->name('department.index');
        Route::middleware("check.permission:manage departments")->group(function(){
          Route::post('/', [DepartmentsController::class, 'storeDepartments'])->name('departments.store');
          Route::put('/{id}', [DepartmentsController::class, 'updateDepartment'])->name('departments.update');
          Route::delete('/{id}',[DepartmentsController::class, 'deleteDepartment'])->name('departments.delete');
        });
      });

      //Categories
      Route::group(['prefix' => "/categories", "middleware" => "check.permission:view categories"], function(){
        Route::get('/', [CategoriesController::class, "getCategories"])->name('category.index');
        Route::middleware('check.permission:manage categories')->group(function(){
          Route::post('/', [CategoriesController::class, "storeCategory"])->name("category.store");
          Route::put('/{id}', [CategoriesController::class, "updateCategory"])->name("category.update");
          Route::delete('/{id}', [CategoriesController::class, "deleteCategory"])->name("category.delete");
        });
      });

      //Sub-categories
      Route::group(['prefix' => "/sub-categories", "middleware" => "check.permission:view sub-categories"], function(){
        Route::get('/', [SubCategoriesController::class, "getSubCategories"])->name('subcategory.index');
        Route::middleware('check.permission:manage sub-categories')->group(function(){
          Route::post('/', [SubCategoriesController::class, "storeSubCategory"])->name("subcategory.store");
          Route::put('/{id}', [SubCategoriesController::class, "updateSubCategory"])->name("subcategory.update");
          Route::delete('/{id}', [SubCategoriesController::class, "deleteSubCategory"])->name("subcategory.delete");
        });
      });

      //Suppliers
      Route::group(['prefix' => "/suppliers", "middleware" => "check.permission:view suppliers"], function(){
        Route::get('/', [SuppliersController::class, "getSuppliers"])->name('suppliers.index');
        Route::middleware('check.permission:manage suppliers')->group(function(){
          Route::post('/', [SuppliersController::class, "storeSupplier"])->name("suppliers.store");
          Route::put('/{id}', [SuppliersController::class, "updateSupplier"])->name("suppliers.update");
          Route::delete('/{id}', [SuppliersController::class, "deleteSupplier"])->name("suppliers.delete");
        });
      });
    });

    //users
    Route::group(['prefix' => '/users', 'middleware'=> 'check.permission:view users'], function(){
      Route::get('/', [UsersController::class, 'getUsers'])->name('users.index');
      Route::middleware('check.permission:manage users')->group(function(){
        Route::post('/', [UsersController::class, 'storeUser'])->name('users.store');
        Route::put('/{id}', [UsersController::class, 'updateUser'])->name('users.update');
        Route::put('/{id}/update',[UsersController::class, 'toggleUser'])->name('users.toggle');
        Route::put('/{id}/restore',[UsersController::class, 'restoreUser'])->name('users.restore');
        Route::delete('/{id}', [UsersController::class, 'softDeleteUser'])->name('users.soft-delete');
        Route::delete('/{id}/force-delete', [UsersController::class, 'forceDelete'])->name('users.force-delete');
      });
    });

    //logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logoutUser');

    Route::get('/placeholder', [PlaceholderController::class, 'getPlaceholder'])->name('placeholder');

});


require __DIR__.'/auth.php';