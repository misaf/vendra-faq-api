<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\VendraFaq\Faqs;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class FaqCollectionQuery extends ResourceQuery
{
    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'fields'      => ['nullable', 'array', JsonApiRule::fieldSets()],
            'filter'      => ['nullable', 'array', JsonApiRule::filter()],
            'include'     => ['nullable', 'string', JsonApiRule::includePaths()],
            'page'        => ['nullable', 'array', JsonApiRule::page()],
            'page.number' => ['nullable', 'integer', 'min:1'],
            'page.size'   => ['nullable', 'integer', 'between:1,100'],
            'sort'        => ['nullable', 'string', JsonApiRule::sort()],
        ];
    }
}
