<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ShowMemberParameter extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::path()
                ->name('id')
                ->description('ID of the member')
                ->required(true)
                ->schema(Schema::integer()),
            Parameter::query()
                ->name('tags')
                ->description("Use '1' to include tag names in the response")
                ->required(false)
                ->schema(Schema::string()),

        ];
    }
}
