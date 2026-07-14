/**
 * auth.ts
 * Helper login untuk Playwright tests — Bookshelf CI4
 */

import { Page, expect } from '@playwright/test';
import { ADMIN, USER } from '../fixtures/testData';

// ─── Login sebagai Administrator ───────────────────────────────────────────

export async function loginAsAdmin(page: Page): Promise<void> {
  await page.goto('/');

  // Tunggu halaman login muncul
  await expect(page.locator('#inputLogin')).toBeVisible();

  // Isi form login
  await page.locator('#inputLogin').fill(ADMIN.email);
  await page.locator('#inputPassword').fill(ADMIN.password);

  // Submit form
  await page.locator('button[type="submit"]').click();

  // Verifikasi berhasil: admin diarahkan ke /admin/dashboard
  await expect(page).toHaveURL(/\/admin\/dashboard/);
}

// ─── Login sebagai User biasa ──────────────────────────────────────────────

export async function loginAsUser(page: Page): Promise<void> {
  await page.goto('/');

  // Tunggu halaman login muncul
  await expect(page.locator('#inputLogin')).toBeVisible();

  // Isi form login
  await page.locator('#inputLogin').fill(USER.email);
  await page.locator('#inputPassword').fill(USER.password);

  // Submit form
  await page.locator('button[type="submit"]').click();

  // Verifikasi berhasil: user diarahkan ke /dashboard
  await expect(page).toHaveURL(/\/dashboard/);
}

// ─── Login dengan kredensial kustom (untuk skenario negatif) ─────────────

export async function loginWith(
  page: Page,
  login: string,
  password: string
): Promise<void> {
  await page.goto('/');
  await expect(page.locator('#inputLogin')).toBeVisible();
  await page.locator('#inputLogin').fill(login);
  await page.locator('#inputPassword').fill(password);
  await page.locator('button[type="submit"]').click();
}

// ─── Logout ────────────────────────────────────────────────────────────────

export async function logout(page: Page): Promise<void> {
  // Klik dropdown user di topbar (data-testid="user-dropdown")
  await page.locator('[data-testid="user-dropdown"]').click();
  // Klik link logout (data-testid="logout-link")
  await page.locator('[data-testid="logout-link"]').click();
  // Verifikasi diarahkan ke halaman login (redirect dari filter)
  await expect(page).toHaveURL(/\/login/);
}
