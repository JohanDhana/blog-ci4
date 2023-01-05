<?php


namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login?return_url=' . current_url(true));
        }
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}
