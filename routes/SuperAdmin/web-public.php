<?php

use App\Http\Controllers\SuperAdmin\CompanyRegisterController;
use App\Http\Controllers\SuperAdmin\FrontendController;
use App\Http\Controllers\SuperAdmin\PaypalIPNController;
use Illuminate\Support\Facades\Route;

Route::post('verify-billing-ipn', [PaypalIPNController::class, 'verifyBillingIPN'])->name('verify-billing-ipn');
Route::get('payroll/download/{id}', [FrontendController::class, 'downloadPdfMobile'])->name('payroll.download_pdf');

Route::group(['as' => 'front.', 'middleware' => ['disable-frontend', 'web']], function () {
    Route::get('apply/{id?}', [FrontendController::class, 'apply'])->name('apply');
    Route::post('apply', [FrontendController::class, 'submitApply'])->name('applySubmit');
    Route::get('/', [FrontendController::class, 'index'])->name('home');
    Route::post('contact-us', [FrontendController::class, 'contactUs'])->name('contact-us');
    Route::get('contact', [FrontendController::class, 'contact'])->name('contact');
    Route::resource('signup', CompanyRegisterController::class, ['only' => ['index', 'store']]);
    Route::get('features', [FrontendController::class, 'feature'])->name('feature');
    Route::get('pricing', [FrontendController::class, 'pricing'])->name('pricing');
    Route::get('pricing-plan', [FrontendController::class, 'pricingPlan'])->name('pricing_plan');
    Route::get('language/{lang}', [FrontendController::class, 'changeLanguage'])->name('language.lang');
    Route::get('page/{slug?}', [FrontendController::class, 'page'])->name('page');
    Route::get('captcha/{tmp}', [CompanyRegisterController::class,'captcha']);
    Route::get('front/read-more', [FrontendController::class, 'readMore'])->name('read-more');
    Route::get('front/enquiry', [FrontendController::class, 'enquiry'])->name('enquiry');
    Route::Post('front/enquiry-us', [FrontendController::class, 'enquiryUs'])->name('enquiry-us');
    Route::get('blog', [FrontendController::class, 'blog'])->name('blog');
    Route::get('single-blog/{id}', [FrontendController::class, 'singleBlog'])->name('single-blog');

});

Route::group(['as' => 'front.', 'middleware' => ['web']], function () {
    Route::get('client-signup/{company:hash}', [FrontendController::class, 'clientSignup'])->name('client-signup');
    Route::middleware('guest')->group(function () {
        Route::post('client-signup/{company:hash}', [FrontendController::class, 'clientRegister'])->name('client-register');
    });
});
