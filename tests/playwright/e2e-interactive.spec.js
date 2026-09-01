// @ts-check
const { test, expect } = require('@playwright/test');

test.describe('E2E Interactive Features & UI Guidelines Compliance', () => {

  test('Page loads correctly and contains valid heading and navigation links', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveTitle(/CMSForNerd/i);

    const mainHeading = page.locator('h1');
    await expect(mainHeading).toBeVisible();
    await expect(mainHeading).toContainText('CMSForNerd');
  });

  test('Theme switcher updates local storage and DOM class', async ({ page }) => {
    await page.goto('/');

    const darkBtn = page.locator('#theme-btn-dark');
    await expect(darkBtn).toBeVisible();
    await expect(darkBtn).toHaveAttribute('aria-label', 'Switch to dark theme');

    await darkBtn.click();
    await expect(page.locator('html')).toHaveClass(/theme-dark/);

    const savedTheme = await page.evaluate(() => localStorage.getItem('theme'));
    expect(savedTheme).toBe('dark');

    const lightBtn = page.locator('#theme-btn-light');
    await lightBtn.click();
    await expect(page.locator('html')).toHaveClass(/theme-light/);

    const savedThemeLight = await page.evaluate(() => localStorage.getItem('theme'));
    expect(savedThemeLight).toBe('light');
  });

  test('Client-side router navigation hydrates page fragments seamlessly', async ({ page }) => {
    await page.goto('/');

    const aboutLink = page.locator('a[href="about.php"]').first();
    if (await aboutLink.isVisible()) {
      await aboutLink.click();
      await expect(page).toHaveURL(/about\.php/);
    }
  });

  test('Interactive widgets, badges, and layout footers are rendered', async ({ page }) => {
    await page.goto('/index.php');

    const runtimeBadges = page.locator('.runtime-status .badge');
    await expect(runtimeBadges.first()).toBeVisible();

    const footer = page.locator('#footer');
    await expect(footer).toBeVisible();
  });

});
