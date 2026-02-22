<?php

declare(strict_types=1);

it('lists faqs with include, filtering and sorting', function (): void {
    $publicCategory = createFaqCategory([
        'name'        => ['en' => 'Public Category'],
        'description' => ['en' => 'Public category'],
        'slug'        => ['en' => 'public-category'],
        'position'    => 1,
        'status'      => true,
    ]);

    $otherCategory = createFaqCategory([
        'name'        => ['en' => 'Other Category'],
        'description' => ['en' => 'Other category'],
        'slug'        => ['en' => 'other-category'],
        'position'    => 2,
        'status'      => true,
    ]);

    $disabledCategory = createFaqCategory([
        'name'        => ['en' => 'Disabled Category'],
        'description' => ['en' => 'Disabled category'],
        'slug'        => ['en' => 'disabled-category'],
        'position'    => 3,
        'status'      => false,
    ]);

    $firstFaq = createFaq($publicCategory, [
        'name'        => ['en' => 'Getting started'],
        'description' => ['en' => 'Getting started answer'],
        'slug'        => ['en' => 'getting-started'],
        'position'    => 1,
    ]);

    $secondFaq = createFaq($publicCategory, [
        'name'        => ['en' => 'Advanced setup'],
        'description' => ['en' => 'Advanced setup answer'],
        'slug'        => ['en' => 'advanced-setup'],
        'position'    => 3,
    ]);

    createFaq($publicCategory, [
        'name'        => ['en' => 'Hidden FAQ'],
        'description' => ['en' => 'Hidden FAQ answer'],
        'slug'        => ['en' => 'hidden-faq'],
        'position'    => 5,
        'status'      => false,
    ]);

    createFaq($otherCategory, [
        'name'        => ['en' => 'Other FAQ'],
        'description' => ['en' => 'Other FAQ answer'],
        'slug'        => ['en' => 'other-faq'],
        'position'    => 2,
    ]);

    createFaq($disabledCategory, [
        'name'        => ['en' => 'Disabled category FAQ'],
        'description' => ['en' => 'Disabled category FAQ answer'],
        'slug'        => ['en' => 'disabled-category-faq'],
        'position'    => 4,
    ]);

    $response = getFaqApi('faqs', '/api/v1/vendra-faq/faqs', [
        'include' => 'faqCategory',
        'filter'  => ['faqCategoryId' => $publicCategory->id],
        'sort'    => '-position',
        'page'    => ['size' => 10],
    ]);

    $response->assertFetchedManyInOrder([
        $secondFaq,
        $firstFaq,
    ])->assertJsonPath('data.0.id', (string) $secondFaq->id)
        ->assertJsonPath('data.0.relationships.faqCategory.data.id', (string) $publicCategory->id)
        ->assertJsonPath('meta.page.total', 2)
        ->assertIsIncluded('categories', (string) $publicCategory->id);
});
