<?php

declare(strict_types=1);

it('lists only enabled faq categories in json api format', function (): void {
    $enabledCategory = createFaqCategory([
        'name'        => ['en' => 'General Questions'],
        'description' => ['en' => 'General category'],
        'slug'        => ['en' => 'general-questions'],
        'position'    => 1,
        'status'      => true,
    ]);

    createFaqCategory([
        'name'        => ['en' => 'Hidden Category'],
        'description' => ['en' => 'Hidden category'],
        'slug'        => ['en' => 'hidden-category'],
        'position'    => 2,
        'status'      => false,
    ]);

    $response = getFaqApi('categories', '/api/v1/vendra-faq/categories', [
        'page' => ['size' => 10],
    ]);

    $response->assertFetchedMany([$enabledCategory])
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJsonPath('data.0.attributes.name', 'General Questions')
        ->assertJsonPath('meta.page.total', 1);
});

it('sorts faq categories by position', function (): void {
    $firstCategory = createFaqCategory([
        'name'        => ['en' => 'First Category'],
        'description' => ['en' => 'First'],
        'slug'        => ['en' => 'first-category'],
        'position'    => 1,
    ]);

    $secondCategory = createFaqCategory([
        'name'        => ['en' => 'Second Category'],
        'description' => ['en' => 'Second'],
        'slug'        => ['en' => 'second-category'],
        'position'    => 5,
    ]);

    $thirdCategory = createFaqCategory([
        'name'        => ['en' => 'Third Category'],
        'description' => ['en' => 'Third'],
        'slug'        => ['en' => 'third-category'],
        'position'    => 3,
    ]);

    $response = getFaqApi('categories', '/api/v1/vendra-faq/categories', [
        'sort' => '-position',
        'page' => ['size' => 10],
    ]);

    $expectedOrder = collect([
        $firstCategory->refresh(),
        $secondCategory->refresh(),
        $thirdCategory->refresh(),
    ])->sortByDesc('position')->values()->all();

    $response->assertFetchedManyInOrder($expectedOrder);
});

it('filters faq categories by position', function (): void {
    createFaqCategory([
        'name'        => ['en' => 'General Questions'],
        'description' => ['en' => 'General'],
        'slug'        => ['en' => 'general-questions'],
        'position'    => 1,
    ]);

    $targetCategory = createFaqCategory([
        'name'        => ['en' => 'Billing Questions'],
        'description' => ['en' => 'Billing'],
        'slug'        => ['en' => 'billing-questions'],
        'position'    => 5,
    ]);

    $targetPosition = (int) $targetCategory->position;

    $response = getFaqApi('categories', '/api/v1/vendra-faq/categories', [
        'filter' => ['position' => $targetPosition],
        'page'   => ['size' => 10],
    ]);

    $response->assertFetchedMany([$targetCategory])
        ->assertJsonPath('meta.page.total', 1);
});
