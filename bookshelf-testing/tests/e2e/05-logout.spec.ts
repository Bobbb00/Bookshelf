/**
 * 05-logout.spec.ts
 * ─────────────────────────────────────────────────────────────────────────────
 * Skenario 12: User logout berhasil
 */

import { test, expect } from '@playwright/test';
import { loginAsUser, logout } from './helpers/auth';

test('12 - User logout berhasil', async ({ page }) => {
  // Login sebagai user
  await loginAsUser(page);
  await expect(page).toHaveURL(/\/dashboard/);

  // Logout via dropdown menu (data-testid="user-dropdown" → data-testid="logout-link")
  await logout(page);

  // Verifikasi: kembali ke halaman login
  // Halaman login mengandung teks "Bookshelf Store"
  await expect(page.locator('body')).toContainText('Bookshelf Store');
});
