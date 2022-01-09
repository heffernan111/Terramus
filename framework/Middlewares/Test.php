<?php

namespace Framework\Middlewares;
use Framework\Routing\Request;

class Test
{
    protected $request;

	public function __construct(Request $request)
    {
        $this->request = $request;
        echo "test";
    }
}