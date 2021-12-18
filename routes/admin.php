<?php
//
//use Illuminate\Support\Facades\Route;
//
///*
//|--------------------------------------------------------------------------
//| admin Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "admin" middleware group. Now create something great!
//|
//*/
//
//
////note that the prefix is admin for all file route
//Route::get('hasan',[\App\Http\Controllers\testController::class,'index']);
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::group(['namespace' => 'Dashboard', 'middleware'=>'auth:admin','prefix' => 'admin'], function () {
        Route::get('/', [\App\Http\Controllers\Dashboard\DashboardController::class,'index'])->name('admin.dashboard');  // the first page admin visits if authenticated
        Route::get('logout',[\App\Http\Controllers\Dashboard\LoginController::class,'logout'])->name('admin.logout');
        Route::group(['prefix' => 'settings'], function () {
            Route::get('shipping-methods/{type}', [\App\Http\Controllers\Dashboard\SettingsController::class,'editShippingMethods'])->name('edit.shippings.methods');
            Route::put('shipping-methods/{id}', [\App\Http\Controllers\Dashboard\SettingsController::class,'updateShippingMethods'])->name('update.shippings.methods');
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('edit', [\App\Http\Controllers\Dashboard\ProfileController::class,'editProfile'])->name('edit.profile');
            Route::put('update',[ \App\Http\Controllers\Dashboard\ProfileController::class,'updateProfile'])->name('update.profile');
        });

               ################################## categories routes ######################################
        Route::group(['prefix' => 'main_categories'], function () {
            Route::get('/', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'index'])->name('admin.maincategories');
            Route::get('create', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'create'])->name('admin.maincategories.create');
            Route::post('store', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'store'])->name('admin.maincategories.store');
            Route::get('edit/{id}', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'edit'])->name('admin.maincategories.edit');
            Route::post('update/{id}', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'update'])->name('admin.maincategories.update');
            Route::get('delete/{id}', [\App\Http\Controllers\Dashboard\MainCategoriesController::class,'destroy'])->name('admin.maincategories.delete');
        });

        ################################## sub categories routes ######################################
        Route::group(['prefix' => 'sub_categories'], function () {
            Route::get('/', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'index'])->name('admin.subcategories');
            Route::get('create', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'create'])->name('admin.subcategories.create');
            Route::post('store', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'store'])->name('admin.subcategories.store');
            Route::get('edit/{id}', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'edit'])->name('admin.subcategories.edit');
            Route::post('update/{id}', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'update'])->name('admin.subcategories.update');
            Route::get('delete/{id}', [\App\Http\Controllers\Dashboard\SubCategoriesController::class,'destroy'])->name('admin.subcategories.delete');
        });

        ################################## brands routes ######################################
        Route::group(['prefix' => 'brands'], function () {
            Route::get('/', [\App\Http\Controllers\Dashboard\BrandsController::class,'index'])->name('admin.brands');
            Route::get('create', [\App\Http\Controllers\Dashboard\BrandsController::class,'create'])->name('admin.brands.create');
            Route::post('store', [\App\Http\Controllers\Dashboard\BrandsController::class,'store'])->name('admin.brands.store');
            Route::get('edit/{id}', [\App\Http\Controllers\Dashboard\BrandsController::class,'edit'])->name('admin.brands.edit');
            Route::post('update/{id}', [\App\Http\Controllers\Dashboard\BrandsController::class,'update'])->name('admin.brands.update');
           Route::get('delete/{id}', [\App\Http\Controllers\Dashboard\BrandsController::class,'destroy'])->name('admin.brands.delete');
        });
//        ################################## end brands    #######################################
                Route::group(['prefix' => 'tags'], function () {
            Route::get('/', [\App\Http\Controllers\Dashboard\TagsController::class,'index'])->name('admin.tags');
           Route::get('create', [\App\Http\Controllers\Dashboard\TagsController::class,'create'])->name('admin.tags.create');
          Route::post('store', [\App\Http\Controllers\Dashboard\TagsController::class,'store'])->name('admin.tags.store');
            Route::get('edit/{id}', [\App\Http\Controllers\Dashboard\TagsController::class,'edit'])->name('admin.tags.edit');
           Route::post('update/{id}', [\App\Http\Controllers\Dashboard\TagsController::class,'update'])->name('admin.tags.update');
            Route::get('delete/{id}', [\App\Http\Controllers\Dashboard\TagsController::class,'delete'])->name('admin.tags.delete');
        });
//        ################################## end tags    #######################################

        ################################## products routes ######################################
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [\App\Http\Controllers\Dashboard\ProductsController::class,'index'])->name('admin.products');
            Route::get('general-information', [\App\Http\Controllers\Dashboard\ProductsController::class,'create'])->name('admin.products.general.create');
            Route::post('store-general-information', [\App\Http\Controllers\Dashboard\ProductsController::class,'store'])->name('admin.products.general.store');

            Route::get('price/{id}', [\App\Http\Controllers\Dashboard\ProductsController::class,'getPrice'])->name('admin.products.price');
            Route::post('price/save', [\App\Http\Controllers\Dashboard\ProductsController::class,'saveProductPrice'])->name('admin.products.price.edit');
            Route::post('price/create', [\App\Http\Controllers\Dashboard\ProductsController::class,'createProductPrice'])->name('admin.products.price.create');

            Route::get('stock/{id}', [\App\Http\Controllers\Dashboard\ProductsController::class,'getStock'])->name('admin.products.stock');
            Route::post('stock', [\App\Http\Controllers\Dashboard\ProductsController::class,'saveProductStock'])->name('admin.products.stock.store');

            Route::get('images/{id}', [\App\Http\Controllers\Dashboard\ProductsController::class,'addImages'])->name('admin.products.images');
            Route::post('images', [\App\Http\Controllers\Dashboard\ProductsController::class,'saveProductImages'])->name('admin.products.images.store');
            Route::post('images/db', [\App\Http\Controllers\Dashboard\ProductsController::class,'saveProductImagesDB'])->name('admin.products.images.store.db');
       });
//        ################################## end products    #######################################
//        ################################## end brands    #######################################
//
//
//        ################################## Tags routes ######################################
//        Route::group(['prefix' => 'tags' ,'middleware' => 'can:tags'], function () {
//            Route::get('/', 'TagsController@index')->name('admin.tags');
//            Route::get('create', 'TagsController@create')->name('admin.tags.create');
//            Route::post('store', 'TagsController@store')->name('admin.tags.store');
//            Route::get('edit/{id}', 'TagsController@edit')->name('admin.tags.edit');
//            Route::post('update/{id}', 'TagsController@update')->name('admin.tags.update');
//            Route::get('delete/{id}', 'TagsController@destroy')->name('admin.tags.delete');
//        });
//        ################################## end brands    #######################################
//
//        ################################## products routes ######################################
//        Route::group(['prefix' => 'products'], function () {
//            Route::get('/', 'ProductsController@index')->name('admin.products');
//            Route::get('general-information', 'ProductsController@create')->name('admin.products.general.create');
//            Route::post('store-general-information', 'ProductsController@store')->name('admin.products.general.store');
//
//            Route::get('price/{id}', 'ProductsController@getPrice')->name('admin.products.price');
//            Route::post('price', 'ProductsController@saveProductPrice')->name('admin.products.price.store');
//
//            Route::get('stock/{id}', 'ProductsController@getStock')->name('admin.products.stock');
//            Route::post('stock', 'ProductsController@saveProductStock')->name('admin.products.stock.store');
//
//            Route::get('images/{id}', 'ProductsController@addImages')->name('admin.products.images');
//            Route::post('images', 'ProductsController@saveProductImages')->name('admin.products.images.store');
//            Route::post('images/db', 'ProductsController@saveProductImagesDB')->name('admin.products.images.store.db');
//        });
//        ################################## end brands    #######################################
//
//        ################################## attrributes routes ######################################
//        Route::group(['prefix' => 'attributes'], function () {
//            Route::get('/', 'AttributesController@index')->name('admin.attributes');
//            Route::get('create', 'AttributesController@create')->name('admin.attributes.create');
//            Route::post('store', 'AttributesController@store')->name('admin.attributes.store');
//            Route::get('delete/{id}', 'AttributesController@destroy')->name('admin.attributes.delete');
//            Route::get('edit/{id}', 'AttributesController@edit')->name('admin.attributes.edit');
//            Route::post('update/{id}', 'AttributesController@update')->name('admin.attributes.update');
//        });
//        ################################## end attributes    #######################################
//
//        ################################## brands options ######################################
//        Route::group(['prefix' => 'options'], function () {
//            Route::get('/', 'OptionsController@index')->name('admin.options');
//            Route::get('create', 'OptionsController@create')->name('admin.options.create');
//            Route::post('store', 'OptionsController@store')->name('admin.options.store');
//            //Route::get('delete/{id}','OptionsController@destroy') -> name('admin.options.delete');
//            Route::get('edit/{id}', 'OptionsController@edit')->name('admin.options.edit');
//            Route::post('update/{id}', 'OptionsController@update')->name('admin.options.update');
//        });
//        ################################## end options    #######################################
//
//
//        ################################## sliders ######################################
//        Route::group(['prefix' => 'sliders'], function () {
//            Route::get('/', 'SliderController@addImages')->name('admin.sliders.create');
//            Route::post('images', 'SliderController@saveSliderImages')->name('admin.sliders.images.store');
//            Route::post('images/db', 'SliderController@saveSliderImagesDB')->name('admin.sliders.images.store.db');
//
//        });
//        ################################## end sliders    #######################################
//
//        ################################## roles ######################################
//        Route::group(['prefix' => 'roles'], function () {
//            Route::get('/', 'RolesController@index')->name('admin.roles.index');
//            Route::get('create', 'RolesController@create')->name('admin.roles.create');
//            Route::post('store', 'RolesController@saveRole')->name('admin.roles.store');
//            Route::get('/edit/{id}', 'RolesController@edit') ->name('admin.roles.edit') ;
//            Route::post('update/{id}', 'RolesController@update')->name('admin.roles.update');
//         });
//        ################################## end roles ######################################
//
//        /**
//         * admins Routes
//         */
//        Route::group(['prefix' => 'users' , 'middleware' => 'can:users'], function () {
//            Route::get('/', 'UsersController@index')->name('admin.users.index');
//            Route::get('/create', 'UsersController@create')->name('admin.users.create');
//            Route::post('/store', 'UsersController@store')->name('admin.users.store');
//        });
//
    });
//

    Route::group(['prefix' => 'admin','middleware'=>'guest:admin'], function () {
//
        Route::get('login', [\App\Http\Controllers\Dashboard\LoginController::class,'login'])->name('admin.login');
        Route::post('login', [\App\Http\Controllers\Dashboard\LoginController::class,'postLogin'])->name('admin.post.login');
//
    });
});
//

