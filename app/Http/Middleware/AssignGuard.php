<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Http\Traits\GeneralTraits;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AssignGuard extends BaseMiddleware // Extending BaseMiddleware is important to import (auth->authenticate) method
{
    use GeneralTraits;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard=null)
    {
        if($guard != null){
            auth()->shouldUse($guard);
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException $ex) {
                return  $this->returnException('Un-Authenticated User', $ex->getMessage(), 401);
            } catch (JWTException $ex) {

                return  $this->returnException('Invalid Token', $ex->getMessage());
            }

        }
         return $next($request);
    }
}
