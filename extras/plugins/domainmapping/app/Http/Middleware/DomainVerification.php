<?php

namespace extras\plugins\domainmapping\app\Http\Middleware;

use Closure;

class DomainVerification
{
    /**
     * Check & Redirect to the country URL base (If the country URL exists)
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultHost = getHost(env('APP_URL', ''));
        $currentHost = getHost(url()->current());
        
        if ($currentHost == $defaultHost) {
            $domain = collect((array)config('domains'))->firstWhere('country_code', config('country.code'));
            if (!empty($domain['url'])) {
                if ($domain['host'] != $defaultHost) {
	                $firstUriSegmentsToSkip = [
		                'install',
		                'upgrade',
		                config('larapen.localization.countries_list_uri'),
		                'robots',
		                'robots.txt',
		                'lang',
		                'page',
		                'feed',
		                'common',
		                'plugins',
	                ];
					
					if (
						!in_array(request()->segment(1), $firstUriSegmentsToSkip)
						&& (session('countryCode') != config('country.code'))
					) {
						return redirect()->to($domain['url'], 301)->withHeaders(config('larapen.core.noCacheHeaders'));
					}
                }
            }
        }
        
        return $next($request);
    }
}
