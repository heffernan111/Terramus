<?php

namespace Framework\Middlewares;
use Framework\Routing\Request;

class Auth
{
    protected $request;

	public function __construct(Request $request)
    {
        $this->request = $request;
        echo "auth";
    }
}