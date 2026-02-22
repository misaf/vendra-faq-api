<?php

declare(strict_types=1);

use LaravelJsonApi\Testing\TestResponse;
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;

if ( ! function_exists('getFaqApi')) {
    function getFaqApi(string $resourceType, string $path, array $query = []): TestResponse
    {
        return test()
            ->jsonApi($resourceType)
            ->query($query)
            ->get($path);
    }
}

if ( ! function_exists('createFaqCategory')) {
    function createFaqCategory(array $attributes = []): FaqCategory
    {
        $faqCategory = new FaqCategory();

        $faqCategory->forceFill(array_merge([
            'tenant_id'   => 1,
            'name'        => ['en' => fake()->sentence(3)],
            'description' => ['en' => fake()->sentence(6)],
            'slug'        => ['en' => fake()->slug()],
            'position'    => fake()->numberBetween(1, 99),
            'status'      => true,
        ], $attributes));

        $faqCategory->save();

        return $faqCategory->refresh();
    }
}

if ( ! function_exists('createFaq')) {
    function createFaq(FaqCategory $faqCategory, array $attributes = []): Faq
    {
        $faq = new Faq();

        $faq->forceFill(array_merge([
            'tenant_id'       => $faqCategory->tenant_id,
            'faq_category_id' => $faqCategory->id,
            'name'            => ['en' => fake()->sentence(4)],
            'description'     => ['en' => fake()->sentence(8)],
            'slug'            => ['en' => fake()->slug()],
            'position'        => fake()->numberBetween(1, 99),
            'status'          => true,
        ], $attributes));

        $faq->save();

        return $faq->refresh();
    }
}
