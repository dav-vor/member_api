<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\MemberSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class StoreMemberRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create('MemberCreate')
            ->description('Member data')
            ->content(MediaType::json()->schema(MemberSchema::ref()));
    }
}
