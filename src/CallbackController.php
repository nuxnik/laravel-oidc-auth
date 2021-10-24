<?php

namespace LaravelOIDCAuth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CallbackController extends Controller
{
    protected $provider;

    public function __construct(OIDCProviderService $service)
    {
        $this->provider = $service->getProvider();
    }

    public function callback(Request $request)
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->get('code'),
        ]);
        $claims = $token->getIdToken()->getClaims();

        session(['oidc-auth.claims' => $claims]);

        Auth::login(new OIDCUser($claims));

        return redirect()->intended();
    }
}