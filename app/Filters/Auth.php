<?php

namespace App\Filters;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if(!$session->has('logged')){
            return redirect()->route('/');
        }else{
            if(!in_array($session->role, $arguments)){
                //return redirect()->route('productos');
                throw PageNotFoundException::forPageNotFound();
            }
        } 
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}