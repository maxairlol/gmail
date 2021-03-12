<?php

namespace App\Exceptions;

use Exception;

class NoAccessTokenException extends Exception
{
    /**
     * Redirect user to login page
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function render()
    {
        return redirect('login');
    }
}
