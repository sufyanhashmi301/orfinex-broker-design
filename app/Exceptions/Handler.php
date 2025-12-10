<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Custom exception handlers for dynamic error pages
        $this->renderable(function (\App\Exceptions\WithdrawDisabledException $e, $request) {
            return $this->renderErrorPage('withdraw_disabled', 'frontend::withdraw.disabled', $e, $request);
        });

        $this->renderable(function (\App\Exceptions\WithdrawOffDayException $e, $request) {
            return $this->renderErrorPage('withdraw_off_day', 'frontend::withdraw.off-day', $e, $request);
        });

        $this->renderable(function (\App\Exceptions\DepositDisabledException $e, $request) {
            return $this->renderErrorPage('deposit_disabled', 'frontend::deposit.disabled', $e, $request);
        });

        $this->renderable(function (\App\Exceptions\SendMoneyDisabledException $e, $request) {
            return $this->renderErrorPage('send_money_disabled', 'frontend::send_money.disabled', $e, $request);
        });
    }

    /**
     * Render error page with dynamic content from database.
     *
     * @param string $type
     * @param string $view
     * @param \Throwable $exception
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function renderErrorPage(string $type, string $view, Throwable $exception, $request)
    {
        // Handle AJAX/JSON requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: __('Access denied'),
                'type' => 'error',
            ], 403);
        }

        // Get dynamic content from database (with error handling)
        try {
            $errorPage = \App\Models\ErrorPage::getByType($type);
        } catch (\Exception $e) {
            // If database query fails (e.g., table doesn't exist), use null
            // Blade files will handle defaults
            $errorPage = null;
        }

        return response()->view($view, [
            'title' => $errorPage?->title,
            'description' => $errorPage?->description,
            'message' => $exception->getMessage() ?: $errorPage?->message,
            'button_text' => $errorPage?->button_text,
            'button_link' => $errorPage?->button_link,
            'button_type' => $errorPage?->button_type,
        ], 403);
    }
}
