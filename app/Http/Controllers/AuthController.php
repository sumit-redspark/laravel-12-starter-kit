<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;

class AuthController extends Controller
{
    private const SUCCESS_MESSAGE = 'Authentication successful';
    private const INVALID_CREDENTIALS_MESSAGE = 'Invalid email or password.';
    private const THROTTLE_MESSAGE = 'Too many login attempts.';
    private const SESSION_EXPIRED_MESSAGE = 'Your session has expired. Please try again.';
    private const GENERAL_ERROR_MESSAGE = 'An error occurred during authentication.';

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function authenticate(LoginRequest $request): JsonResponse|RedirectResponse
    {
        try
        {
            $request->authenticate();
            $request->session()->regenerate();

            return $this->handleSuccessfulAuthentication($request);
        }
        catch (ValidationException $e)
        {
            return $this->handleValidationException($e, $request);
        }
        catch (\Exception $e)
        {
            return $this->handleGeneralException($e, $request);
        }
    }

    /**
     * Handle successful authentication response.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    private function handleSuccessfulAuthentication(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->ajax())
        {
            return response()->json([
                'success' => true,
                'message' => self::SUCCESS_MESSAGE,
                'redirect' => '/admin/'
            ]);
        }

        return redirect()->intended('/');
    }

    /**
     * Handle validation exception during authentication.
     *
     * @param ValidationException $e
     * @param Request $request
     * @return JsonResponse
     */
    private function handleValidationException(ValidationException $e, Request $request): JsonResponse
    {
        if (!$request->ajax())
        {
            throw $e;
        }

        $errors = $e->errors();
        $response = [
            'success' => false,
            'message' => 'Please correct the errors below.',
            'errors' => $errors
        ];

        if (isset($errors['email']))
        {
            $emailError = $errors['email'][0];

            if (str_contains($emailError, 'credentials do not match'))
            {
                $response = [
                    'success' => false,
                    'message' => self::INVALID_CREDENTIALS_MESSAGE,
                    'errors' => [
                        'email' => ['The email address or password is incorrect.'],
                        'password' => ['Please check your credentials and try again.']
                    ]
                ];
            }
            elseif (str_contains($emailError, 'throttle'))
            {
                $response = [
                    'success' => false,
                    'message' => self::THROTTLE_MESSAGE,
                    'errors' => ['email' => [$emailError]]
                ];
            }
        }

        return response()->json($response, 422);
    }

    /**
     * Handle general exceptions during authentication.
     *
     * @param \Exception $e
     * @param Request $request
     * @return JsonResponse
     */
    private function handleGeneralException(\Exception $e, Request $request): JsonResponse
    {
        if (!$request->ajax())
        {
            throw $e;
        }

        $response = [
            'success' => false,
            'message' => self::GENERAL_ERROR_MESSAGE,
            'errors' => []
        ];

        if ($e instanceof AuthenticationException)
        {
            $response['message'] = 'Authentication failed.';
            $response['errors'] = [
                'email' => ['Please check your credentials.'],
                'password' => ['The password you entered is incorrect.']
            ];
        }
        elseif ($e instanceof TokenMismatchException)
        {
            $response['message'] = self::SESSION_EXPIRED_MESSAGE;
        }

        return response()->json($response, 500);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
