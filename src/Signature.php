<?php

namespace ChinaDivisions;

class Signature
{
    public function make($request, $resourceCode)
    {
        return base64_encode(md5($request . $resourceCode, true));
    }
}