<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PerformanceMatrix;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\MultiAdminController;



use App\Http\Controllers\BuisnessProfileController;

use App\Http\Controllers\ManufactureController;

use App\Http\Controllers\ServiceAreaController;

use App\Http\Controllers\DispatcherController;

use App\Http\Controllers\AdminProfileController;





use App\Http\Controllers\ProductCategoryController;



use App\Http\Controllers\ServiceCategoryController;



use App\Http\Controllers\ServicesController;

use App\Http\Controllers\EstimateController;

use App\Http\Controllers\EstimateCategoryController;





use App\Http\Controllers\CrmController;



use App\Http\Controllers\HomeController;



use App\Http\Controllers\UserController;



use App\Http\Controllers\AdminController;



use App\Http\Controllers\TicketController;



use App\Http\Controllers\ProfileController;



use App\Http\Controllers\ProgressController;



use App\Http\Controllers\ScheduleController;



use App\Http\Controllers\TechnicianController;



use App\Http\Controllers\AppointmentController;



use App\Http\Controllers\TaskController;

use App\Http\Controllers\TaxController;



use App\Http\Controllers\LeadsourceController;



use App\Http\Controllers\TagsController;



use App\Http\Controllers\JobfieldsController;



use App\Http\Controllers\BusinessHoursController;



use App\Http\Controllers\SiteLeadSourceController;



use App\Http\Controllers\SiteTagsController;



use App\Http\Controllers\SiteJobFieldsController;



use App\Http\Controllers\MapController;

use App\Http\Controllers\ChatSupportController;

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\TimezoneController;

/*



|--------------------------------------------------------------------------



| Web Routes



|--------------------------------------------------------------------------



|



| Here is where you can register web routes for your application. These



| routes are loaded by the RouteServiceProvider and all of them will



| be assigned to the "web" middleware group. Make something great!



|



*/

// Catch all routes
Route::fallback(function () {
    return response()->view('404', [], 404);
});

Route::middleware('guest')->group(function () {



    Route::get('/auth', function () {



        return view('login');
    });



    Route::get('/', function () {



        return view('auth.login');
    });
});



Route::get('/dashboard', function () {



    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');







require __DIR__ . '/auth.php';



Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');




Route::group(['middleware' => 'role:admin'], function () {



    // Index - Display all admins



    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');



    // Create - Display form to create a new admin



    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');



    // Store - Save a new admin to the database



    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');



    // Show - Display a specific admin



    Route::get('/admins/{admin}', [AdminController::class, 'show'])->name('admins.show');



    // Edit - Display form to edit a specific admin



    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');



    // Update - Update a specific admin in the database



    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');



    // Destroy - Delete a specific admin from the database



    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
});





Route::group(['middleware' => 'role:customer'], function () {



    // Route::resource('/users', UserController::class, );



    Route::get('/users', [UserController::class, 'index'])->name('users.index');



    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

Route::post('/get-user-status', [UserController::class, 'getUserStatus'])->name('get.user.status');


    Route::POST('/users/store', [UserController::class, 'store'])->name('users.store');

    Route::POST('/new/customer/schedule', [UserController::class, 'customer_schedule']);



    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');



    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');



    Route::PUT('/users/{id}/update', [UserController::class, 'update'])->name('users.update');



    Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
});



Route::group(['middleware' => 'role:customer'], function () {



    // Index - Display all technicians



    Route::resource('/technicians', TechnicianController::class,);



    Route::POST('/technicians/store', [TechnicianController::class, 'store'])->name('technicians.store');

    Route::post('/techniciancomment/store', [TechnicianController::class, 'techniciancomment'])->name('techniciancomment.store');

    Route::post('/technicianstaus/update', [TechnicianController::class, 'technicianstaus'])->name('technicianstaus.update');


    Route::POST('/technicians/edit/{id}', [TechnicianController::class, 'edit'])->name('technicians.edit');



    Route::get('/technicians/create/', [TechnicianController::class, 'create'])->name('technicians.create');



    Route::get('/technicians/{id}/edit', [TechnicianController::class, 'edit'])->name('technicians.edit');



    Route::get('/technicians/show/{id}', [TechnicianController::class, 'show'])->name('technicians.show');



    Route::PUT('/technicians/update/{id}', [TechnicianController::class, 'update'])->name('technicians.update');
});



Route::middleware('auth')->group(function () {



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');



    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');



    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    //location



    Route::get('/locations', [TechnicianController::class, 'technicianGet'])->name('technicians.location');



    // Display all tickets



    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');



    // Show the form to create a new ticket



    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');



    // Store a newly created ticket



    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');



    // Show a specific ticket



    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');



    // Show the form to edit a ticket



    Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');



    // Update a specific ticket



    Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');



    // Delete a specific ticket



    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');


    Route::post('add/customer_tags/{id}', [TicketController::class, 'addCustomerTags']);

    Route::post('add/job_tags/{id}', [TicketController::class, 'job_tags']);
    
    Route::post('add/attachment/{id}', [TicketController::class, 'attachment']);

    Route::post('add/leadsource/{id}', [TicketController::class, 'leadSource']);



    // Show tickets assigned to a specific user



    Route::get('/users/{userId}/tickets', [UserController::class, 'showUserTickets'])->name('users.tickets');



    // Assign a ticket to a user



    Route::put('/tickets/{ticketId}/assign', [UserController::class, 'assignTicket'])->name('tickets.assign');



    Route::put('/assign-task/{taskId}/{technicianId}', [ScheduleController::class, 'assignTechnician']);



    // Route for displaying all tasks (GET request)



    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');



    // Route for displaying a form to create a new task (GET request)



    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');



    // Route for storing a new task (POST request)



    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');



    // Route for displaying a form to edit a task (GET request)



    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');



    // Route for updating a task (PUT request)



    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');



    // Route for deleting a task (DELETE request)



    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');



    // Lead Source Route here(st)



    Route::get('/setting/lead/lead-source', [LeadsourceController::class, 'create'])->name('lead.lead-source');

    Route::post('/setting/save-lead-source', [LeadsourceController::class, 'saveLeadSource'])->name('lead-source-add');

    Route::post('/store/leadsource', [LeadsourceController::class, 'store']);

    Route::post('edit/leadsource', [LeadsourceController::class, 'updateLeadSource']);

    Route::get('delete/leadsource/{id}', [LeadsourceController::class, 'deleteLeadSource']);



    // Tags Route here(st)



    Route::get('/setting/tags/tags-list', [TagsController::class, 'create'])->name('tags.tags-list');

    Route::post('/store/tags', [TagsController::class, 'saveTags']);

    Route::post('/edit/tags', [TagsController::class, 'updateTag']);

    Route::get('/delete/tags/{tagId}', [TagsController::class, 'deleteTag']);



    // Job Fields Route here(st)



    Route::get('/setting/jobfields/job-fields-list', [JobfieldsController::class, 'create'])->name('site_job_fields');

    Route::post('/store/jobfield', [JobfieldsController::class, 'saveJobfields']);

    Route::post('/edit/jobfield', [App\Http\Controllers\JobfieldsController::class, 'updateField']);

    Route::get('/delete/jobfield/{jobFieldsId}', [JobfieldsController::class, 'deleteJobFields']);



    // Business Hours Route here(st)



    Route::get('/setting/businessHours/business-hours', [BusinessHoursController::class, 'businesshourspage'])->name('businessHours.business-hours');

    Route::post('/setting/update-business-hours', [BusinessHoursController::class, 'updateBusinessHours'])->name('updateBusinessHours');

    Route::post('/setting/update-online-hours', [BusinessHoursController::class, 'updateOnlineHours'])->name('updateOnlineHours');



    // Site Lead Source Route here(st)



    Route::get('/lead/site-lead-source', [SiteLeadSourceController::class, 'create'])->name('lead.site-lead-source');

    Route::post('save-lead-source', [SiteLeadSourceController::class, 'saveLeadSource'])->name('lead-source-add');

    Route::post('/update-site-lead-source/{source_id}', [SiteLeadSourceController::class, 'updateLeadSource'])->name('update-site-lead-source');

    Route::delete('/delete-site-lead-source/{leadSourceId}', [SiteLeadSourceController::class, 'deleteLeadSource'])->name('delete-site-lead-source');



    //Site Tags Route here(st)



    Route::get('/tags/site-tags', [SiteTagsController::class, 'create'])->name('tags.site-tags');

    Route::post('tags-add', [SiteTagsController::class, 'saveTags'])->name('tags-add');

    Route::post('/update-site-tag/{id}', [SiteTagsController::class, 'updateTag'])->name('update-site-tag');

    Route::delete('/delete-site-tag/{tagId}', [SiteTagsController::class, 'deleteTag'])->name('delete-site-tag');



    //Site Job Fields Route here(st)



    Route::get('/jobfields/site-job-fields', [SiteJobFieldsController::class, 'create'])->name('jobfields.site-job-fields');

    Route::post('job-fields-add', [SiteJobFieldsController::class, 'saveJobfields'])->name('job-fields-add');

    Route::post('/update-site-field/{field_id}', [App\Http\Controllers\SiteJobFieldsController::class, 'updateField'])->name('update-site-field');

    Route::delete('/delete-site-job-fields/{jobFieldsId}', [SiteJobFieldsController::class, 'deleteJobFields'])->name('delete-site-job-fields');



    // Map



    Route::get('map', [MapController::class, 'index'])->name('map');

    Route::get('marker-content', [MapController::class, 'getMarkerDetails'])->name('map.getMarkerDetails');

    Route::get('get/technician-area-wise', [MapController::class, 'getTechnicianAreaWise'])->name('get.technician.area.wise');

    Route::get('get/job-details', [MapController::class, 'getJobDetails'])->name('get.jobDetails');

    Route::post('reschedule-job', [MapController::class, 'rescheduleJob'])->name('reschedule.job');



    //service section route

    Route::get('book-list/services', [ServiceCategoryController::class, 'index'])->name('services.index');

    Route::post('book-list/servicecategory-store', [ServiceCategoryController::class, 'storeServicescategory'])->name('servicecategory.store');

    Route::post('book-list/servicecategory-update', [ServiceCategoryController::class, 'updateServicescategory'])->name('servicecategory.update');

    Route::delete('book-list/servicecategory-delete/{id}', [ServiceCategoryController::class, 'deleteServicescategory'])->name('servicecategory.delete');



    Route::get('book-list/getStoryDetails', [ServiceCategoryController::class, 'getStoryDetails'])->name('getStoryDetails');



    Route::get('book-list/services-list/{category_id?}', [ServicesController::class, 'listingServices'])->name('services.listingServices');

    Route::get('book-list/services-create', [ServicesController::class, 'createServices'])->name('services.createServices');

    Route::post('book-list/services-store', [ServicesController::class, 'storeServices'])->name('services.storeServices');

    Route::get('book-list/services/{service_id}/edit', [ServicesController::class, 'editServices'])->name('services.edit');


    Route::get('inactive/service/{id}', [ServicesController::class, 'inactive']);

    Route::get('active/service/{id}', [ServicesController::class, 'active']);



    Route::delete('book-list/services/{service_id}', [ServicesController::class, 'deleteService'])->name('services.delete');



    Route::post('book-list/services/{id}', [ServicesController::class, 'updateServices']);



    // Schedule

    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');

    Route::get('schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');

    Route::get('autocomplete-customer', [ScheduleController::class, 'autocompleteCustomer'])->name('autocomplete.customer');

    Route::get('autocomplete-technician', [ScheduleController::class, 'autocompleteTechnician'])->name('autocomplete.technician');

    Route::get('autocomplete-services', [ScheduleController::class, 'autocompleteServices'])->name('autocomplete.services');

    Route::get('autocomplete-product', [ScheduleController::class, 'autocompleteProduct'])->name('autocomplete.product');

    Route::get('autocomplete-serchOldJob', [ScheduleController::class, 'autocompletesearchOldJob'])->name('autocomplete.serchOldJob');

    Route::get('get/customer-details', [ScheduleController::class, 'getCustomerDetails'])->name('customer.details');

    Route::get('get/services-products-details', [ScheduleController::class, 'getServicesAndProductDetails'])->name('services.parts.details');

    Route::get('get/product-details', [ScheduleController::class, 'getProductDetails'])->name('product.details');

    Route::get('get/services-details', [ScheduleController::class, 'getServicesDetails'])->name('services.details');

    Route::post('schedule/create/post', [ScheduleController::class, 'createSchedule'])->name('schedule.create.post');

    Route::post('schedule/update/post', [ScheduleController::class, 'update'])->name('schedule.update.post');

   Route::get('schedule/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');

    Route::post('schedule/update', [ScheduleController::class, 'updateSchedule'])->name('schedule.update');

    Route::get('get/existing-schedule', [ScheduleController::class, 'getExistingSchedule'])->name('existing.schedule');

    Route::get('get/pending_jobs', [ScheduleController::class, 'pending_jobs']);

    Route::get('get/user/by_number', [ScheduleController::class, 'get_by_number']);
    Route::get('get_number_customer_one', [UserController::class, 'get_number_customer_one'])->name('get_number_customer_one');

    Route::post('store/event/', [ScheduleController::class, 'store_event']);




    //ProductCategory



    Route::get('parts', [ProductCategoryController::class, 'index'])->name('product.index');

    Route::post('book-list/partscategory-store', [ProductCategoryController::class, 'storeproductcategory'])->name('productcategory.store');

    Route::post('book-list/partscategory-update', [ProductCategoryController::class, 'updateproductcategory'])->name('productcategory.update');

    Route::delete('book-list/partscategory-delete/{id}', [ProductCategoryController::class, 'deleteproductcategory'])->name('productcategory.delete');



    Route::get('book-list/edit-partscategory', [ProductCategoryController::class, 'editproduct'])->name('editproduct');



    Route::get('book-list/parts-create', [productController::class, 'createproduct'])->name('product.createproduct');

    Route::post('book-list/parts-store', [ProductController::class, 'store'])->name('product.store');

    Route::get('book-list/parts/{product_id}/edit', [productController::class, 'edit'])->name('product.edit');

    Route::put('book-list/parts/{id}', [ProductController::class, 'update'])->name('product.update');

    Route::put('inactive/parts/{id}', [ProductController::class, 'inactive']);

    Route::put('active/parts/{id}', [ProductController::class, 'active']);

    Route::get('book-list/parts/{id}/destroy', [productController::class, 'destroy'])->name('product.destroy');

    Route::get('assign_product', [ProductCategoryController::class, 'assign_product'])->name('assign_product');
    
    Route::post('store/assign-product', [ProductCategoryController::class, 'store_assign_product']);




    Route::get('partCategory', [ProductController::class, 'listingproduct'])->name('partCategory');

    Route::get('book-list/parts-list/productsaxaclist', [ProductController::class, 'productsaxaclist'])->name('productsaxaclist');





    //buisnessprofile

    Route::get('/setting/buisness-profile', [BuisnessProfileController::class, 'index'])->name('buisnessprofile.index');



    Route::post('/setting/buisness-profile/update', [BuisnessProfileController::class, 'update'])->name('buisnessprofile.update');

    Route::post('/setting/buisness-profile/bpupdate', [BuisnessProfileController::class, 'bpupdate'])->name('bpcompanydiscription.update');

    Route::post('/setting/buisness-profile/moiupdate', [BuisnessProfileController::class, 'moiupdate'])->name('moicompanydiscription.update');

    Route::post('/setting/buisness-profile/tacupdate', [BuisnessProfileController::class, 'tacupdate'])->name('taccompanydiscription.update');





    //Estimate category



    Route::get('book-list/estimate', [EstimateCategoryController::class, 'index'])->name('estimate.index');

    Route::post('book-list/estimatecategory-store', [EstimateCategoryController::class, 'storeestimatecategory'])->name('estimatecategory.store');

    Route::post('book-list/estimatecategory-update', [EstimateCategoryController::class, 'updateestimatecategory'])->name('estimatecategory.update');

    Route::delete('estimatecategory-delete/{id}', [EstimateCategoryController::class, 'deleteestimatecategory'])->name('estimatecategory.delete');

    Route::get('book-list/estimateDetails', [EstimateCategoryController::class, 'estimateDetails'])->name('estimateDetails');

    // Route::get('estimate/{estimate_id}/edit', [EstimateCategoryController::class, 'editestimate'])->name('estimate.edit');





    Route::get('book-list/estimate-list/{category_id?}', [EstimateController::class, 'listingestimate'])->name('estimate.listingestimate');



    Route::get('book-list/estimate-create', [EstimateController::class, 'createestimate'])->name('estimate.createestimate');

    Route::get('book-list/estimate/{template_id}/edit', [EstimateController::class, 'edit'])->name('estimateservices.edit');

    Route::post('book-list/estimate-store', [EstimateController::class, 'store'])->name('estimate.store');



    Route::delete('book-list/estimate/{template_id}/destroy', [EstimateController::class, 'destroy'])->name('estimate.destroy');

    Route::put('book-list/estimate/{template_id}', [EstimateController::class, 'update'])->name('estimate.update');



    // routes/web.php

    Route::get('book-list/get-service-details/{id}', [EstimateController::class, 'getServiceDetails'])->name('estimate.service');

    Route::get('book-list/get-product-details/{id}', [EstimateController::class, 'getProductDetails'])->name('estimate.product');



    //manufacture add/edit/delete



    Route::get('/setting/manufacturer', [ManufactureController::class, 'mnufacturelist'])->name('manufacturer.index');

    Route::get('/setting/manufacturer-create', [ManufactureController::class, 'create'])->name('manufacturer.create');

    Route::post('/setting/manufacturer-store', [ManufactureController::class, 'store'])->name('manufacture.store');

    Route::get('/setting/manufacturer-edit/{id}/edit', [ManufactureController::class, 'edit'])->name('manufacturer.edit');

    Route::put('/setting/manufacturer-update/{id}/update', [ManufactureController::class, 'update'])->name('manufacture.update');

    Route::get('/setting/manufacturer-enable/{id}', [ManufactureController::class, 'enable']);

    Route::get('/setting/manufacturer-disable/{id}', [ManufactureController::class, 'disable']);



    //servicearea route

    Route::get('/setting/service-area', [ServiceAreaController::class, 'index'])->name('servicearea.index');

    Route::get('/setting/create-service-area', [ServiceAreaController::class, 'create'])->name('servicearea.create');

    Route::post('/setting/store-service-area', [ServiceAreaController::class, 'store'])->name('servicearea.store');



    Route::get('/setting/editservicearea', [ServiceAreaController::class, 'editservicearea'])->name('editservicearea');

    Route::get('/setting/viewservicearea', [ServiceAreaController::class, 'viewservicearea'])->name('viewservicearea');





    Route::put('/setting/update-service-area', [ServiceAreaController::class, 'update'])->name('servicearea.update');





    //Dispatcher

    Route::get('/dispatcher-index', [DispatcherController::class, 'index'])->name('dispatcher.index');



    Route::get('/dispatcher-create', [DispatcherController::class, 'create'])->name('dispatcher.create');



    Route::post('/dispatcher-store', [DispatcherController::class, 'store'])->name('dispatcher.store');

    Route::get('/dispatcher/edit/{id}', [DispatcherController::class, 'edit'])->name('dispatcher.edit');



    Route::put('/dispatcher/update/{id}', [DispatcherController::class, 'update'])->name('dispatcher.update');

    // Route::get('/dispatcher-delete', [DispatcherController::class, 'delete'])->name('dispatcher.delete');

    Route::get('/dispatcher/show/{id}', [DispatcherController::class, 'show'])->name('dispatcher.show');



    //adminprofile

    Route::get('/my-profile', [AdminProfileController::class, 'index'])->name('myprofile.index');

    Route::post('/my-profile-store', [AdminProfileController::class, 'store'])->name('user.adminprofileimg');

    Route::post('/my-profile-password-store', [AdminProfileController::class, 'passstore'])->name('user.passstoreadmin');

    Route::post('/my-profile-info', [AdminProfileController::class, 'infoadmin'])->name('user.infoadmin');





    //multiadmin



    Route::get('/multiadmin-index', [MultiAdminController::class, 'index'])->name('multiadmin.index');



    Route::get('/multiadmin-create', [MultiAdminController::class, 'create'])->name('multiadmin.create');



    Route::post('/multiadmin-store', [MultiAdminController::class, 'store'])->name('multiadmin.store');

    Route::get('/multiadmin/edit/{id}', [MultiAdminController::class, 'edit'])->name('multiadmin.edit');



    Route::put('/multiadmin/update/{id}', [MultiAdminController::class, 'update'])->name('multiadmin.update');

    // Route::get('/dispatcher-delete', [MultiAdminController::class, 'delete'])->name('dispatcher.delete');

    Route::get('/multiadmin/show/{id}', [MultiAdminController::class, 'show'])->name('multiadmin.show');







    //Tax





    Route::get('/setting/tax', [TaxController::class, 'index'])->name('tax.index');

    Route::get('/setting/tax/edit/{id}', [TaxController::class, 'edit'])->name('edit.state');

    Route::get('/setting/get-edit-form', [TaxController::class, 'getEditForm'])->name('get.edit.form');

    Route::post('/setting/tax/update', [TaxController::class, 'update'])->name('tax.update');




    //chat

    Route::get('/app_chats', [ChatSupportController::class, 'index'])->name('app_chats');

    Route::get('/get-chat-messages', [ChatSupportController::class, 'get_chats']);

    Route::post('/store_reply', [ChatSupportController::class, 'store'])->name('store_reply');

    // payments

    Route::get('/payment-list', [PaymentController::class, 'index'])->name('payment-list');

    Route::get('/invoice-detail/{id}', [PaymentController::class, 'invoice_detail'])->name('invoicedetail');

    Route::get('/update/payment/{id}', [PaymentController::class, 'update']);
    
    Route::post('/store/comment/{id}', [PaymentController::class, 'comment']);

    // timezone 

    Route::post('/change_timezone', [TimezoneController::class, 'store']);


    //email send  to users for forget password





Route::get('/performance-matrix', [PerformanceMatrix::class, 'performanncematrix'])->name('performanncematrix');

});

Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');



Route::get('/cities', [UserController::class, 'getCities'])->name('getcities');

Route::get('/citiesanother', [UserController::class, 'getCitiesanother'])->name('getcitiesanother');



Route::get('/update-customer-password', [UserController::class, 'updatePassword'])->name('update-customer-password');

Route::get('/getZipCode', [UserController::class, 'getZipCode'])->name('getZipCode');

Route::get('/getZipCodeanother', [UserController::class, 'getZipCodeanother'])->name('getZipCodeanother');
Route::post('/technician-note-store', [TicketController::class, 'techniciannotestore'])->name('techniciannote');

Route::post('/check-mobile',  [UserController::class, 'checkMobile'])->name('check-mobile');

Route::get('/permission-index',  [AdminController::class, 'permissionindex'])->name('permissionindex');

Route::post('/permission-store',  [AdminController::class, 'permissionstore'])->name('permissions.store');

Route::post('/permission-delete',  [AdminController::class, 'permissiondelete'])->name('permissions.delete');
