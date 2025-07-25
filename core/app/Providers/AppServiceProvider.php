<?php

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\AdminNotification;
use App\Models\BalanceTransfer;
use App\Models\Deposit;
use App\Models\Dps;
use App\Models\Fdr;
use App\Models\Frontend;
use App\Models\Loan;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Traits\ApiQuery;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
        Builder::macro('apiQuery', function () {
            return ApiQuery::scopeApiQuery($this);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }

        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),

                'pendingTicketCount'         => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'       => Deposit::pending()->count(),
                'pendingWithdrawCount'       => Withdrawal::pending()->count(),
                'dueFdrCount'                => Fdr::due()->count(),
                'lateInstallmentDpsCount'                => Dps::due()->count(),
                'lateInstallmentLoanCount'               => Loan::due()->count(),
                'pendingLoanCount'           => Loan::pending()->count(),
                'pendingTransferCount'       => BalanceTransfer::pending()->count(),
                'updateAvailable'              => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (str_contains(request()->getHost(), 'ngrok-free.app')) {
            \URL::forceScheme('https');
            \URL::forceRootUrl(request()->getScheme() . '://' . request()->getHost());
            \URL::formatHostUsing(function () {
                return request()->getHost();
            });
        }


        Paginator::useBootstrapFive();

    }
}
