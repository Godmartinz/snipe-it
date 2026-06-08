<?php

namespace App\Models\Traits;

use App\Models\CompanyableScope;

trait CompanyableTrait
{
    /**
     * This trait is used to scope models to the current company. To use this scope on companyable models,
     * we use the "use Companyable;" statement at the top of the mode.
     *
     * @see    Company::scopeCompanyables()
     *
     * @return void
     */
    public static function bootCompanyableTrait()
    {
        static::addGlobalScope(new CompanyableScope);
    }
}
