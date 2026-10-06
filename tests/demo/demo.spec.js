const { test, expect } = require('@playwright/test');

for (const route of ['/', '/vente', '/location', '/lagence', '/contact', '/vente/appartement/bien-demo-1']) {
    test(`French demo renders ${route}`, async ({ page }) => {
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        const response = await page.goto(route);
        expect(response.status()).toBe(200);
        await expect(page.locator('.demo-notice')).toContainText('données fictives');
        await expect(page).toHaveTitle(/.+ \| Agence Démo/);
        await page.waitForLoadState('networkidle');
        expect(errors).toEqual([]);
        const text = await page.locator('body').innerText();
        expect(text).not.toMatch(/(?:fr|en)\.(?:text|articlebody|ouropinion)\./);
    });
}

test('English homepage and property use English content', async ({ page }) => {
    await page.goto('/en');
    await expect(page).toHaveTitle(/Home/);
    await expect(page.locator('.demo-notice')).toContainText('fictitious data');
    await page.goto('/en/selling/appartement/bien-demo-1');
    await expect(page).toHaveTitle(/Demo apartment/);
});

test('Budget filtering excludes the more expensive fictitious property', async ({ page }) => {
    await page.goto('/vente');
    const results = page.locator('.container-list');
    await expect(results.locator('.masonry-item')).toHaveCount(2);
    const form = page.locator('.accommodation-list-search');
    await form.getByLabel('Budget maxi.').fill('200000');
    await form.locator('button[type="submit"]').click();
    await expect(results.locator('.masonry-item')).toHaveCount(1);
    await expect(results).toContainText('DEMO-1');
    await expect(results).not.toContainText('DEMO-2');
});

test('Demo blocks messages, email confirmations and contact submission', async ({ page, request }) => {
    for (const route of ['/api/messages', '/email/confirmation-contact']) {
        const response = await request.post(route, { data: { message: 'Fictitious test' } });
        expect(response.status()).toBe(403);
    }
    await page.goto('/contact');
    await expect(page.locator('.contact-form button[type="submit"]').first()).toBeDisabled();
});

test('Small-screen homepage has no application error', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    await expect(page.locator('.demo-notice')).toBeVisible();
    await expect(page.locator('.nuxt-error')).toHaveCount(0);
});

test('Media transformations serve fictitious property artwork', async ({ request }) => {
    const response = await request.get('/media/cache/grid_nostamp/uploads/media/files/demo.webp');
    expect(response.status()).toBe(200);
    expect(await response.text()).toContain('Bien immobilier fictif');
});
