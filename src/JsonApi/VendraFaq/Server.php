<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\VendraFaq;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraFaqApi\JsonApi\VendraFaq\Categories\FaqCategorySchema;
use Misaf\VendraFaqApi\JsonApi\VendraFaq\Faqs\FaqSchema;

final class Server extends BaseServer
{
    protected string $baseUri = '/api/v1/vendra-faq';

    public function authorizable(): bool
    {
        return false;
    }

    /**
     * @return list<class-string>
     */
    public function allSchemas(): array
    {
        return [
            FaqCategorySchema::class,
            FaqSchema::class,
        ];
    }
}
