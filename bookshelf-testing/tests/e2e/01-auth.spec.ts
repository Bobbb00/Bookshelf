/**
 * 01-auth.spec.ts
 * ─────────────────────────────────────────────────────────────────────────────
 * Skenario 1: Registrasi dengan data yang benar
 * Skenario 2: Login dengan password salah
 * Skenario 3: Login sebagai admin
 * Skenario 4: Login sebagai user
 */

import { test, expect } from '@playwright/test';
import { loginAsAdmin, loginAsUser, loginWith } from './helpers/auth';
import { NEW_USER, ADMIN, USER } from './fixtures/testData';

// ─── Skenario 1 ────────────────────────────────────────────────────────────

test('01 - Registrasi dengan data yang benar', async ({ page }) => {
  // Buka halaman register
  await page.goto('/register');
  await expect(page.locator('#inputUsername')).toBeVisible();

  const ts = Date.now();
  const newUser = {
    username: `pengguna${ts}`,
    email:    `user_${ts}@testing.test`,
    password: 'RahasiaNegara@2026',
  };

  // Isi form registrasi
  await page.locator('#inputUsername').fill(newUser.username);
  await page.locator('#inputEmail').fill(newUser.email);
  await page.locator('#inputPassword').fill(newUser.password);
  await page.locator('input[name="pass_confirm"]').fill(newUser.password);

  // Submit
  await page.locator('button[type="submit"]').click();

  // Verifikasi: diarahkan ke halaman login (Myth:Auth redirect setelah register)
  await expect(page).toHaveURL(/\/login|\/$/);

  // Login dengan akun yang baru dibuat
  await page.locator('#inputLogin').fill(newUser.email);
  await page.locator('#inputPassword').fill(newUser.password);
  await page.locator('button[type="submit"]').click();

  // Verifikasi: berhasil masuk ke dashboard user
  await expect(page).toHaveURL(/\/dashboard/);
});

// ─── Skenario 2 ────────────────────────────────────────────────────────────

test('02 - Login dengan password salah', async ({ page }) => {
  await loginWith(page, USER.email, 'PasswordSalah123!');

  // Verifikasi: tetap di halaman login, tidak redirect
  await expect(page).toHaveURL(/\/login|\/$/);

  // Verifikasi: ada pesan error dari Myth:Auth
  await expect(page.locator('body')).toContainText(/Invalid|tidak valid|salah|incorrect/i);
});

// ─── Skenario 3 ────────────────────────────────────────────────────────────

test('03 - Login sebagai admin', async ({ page }) => {
  await loginAsAdmin(page);

  // Verifikasi: berada di /admin/dashboard
  await expect(page).toHaveURL(/\/admin\/dashboard/);
});

// ─── Skenario 4 ────────────────────────────────────────────────────────────

test('04 - Login sebagai user', async ({ page }) => {
  await loginAsUser(page);

  // Verifikasi: berada di /dashboard (katalog buku)
  await expect(page).toHaveURL(/\/dashboard/);

  // Verifikasi: halaman berisi teks "Katalog Buku"
  await expect(page.locator('body')).toContainText('Katalog Buku');
});
