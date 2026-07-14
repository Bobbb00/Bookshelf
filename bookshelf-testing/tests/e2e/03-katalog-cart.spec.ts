/**
 * 03-katalog-cart.spec.ts
 * ─────────────────────────────────────────────────────────────────────────────
 * Skenario 8:  User mencari buku berdasarkan judul
 * Skenario 9:  User menambahkan buku ke keranjang
 * Skenario 10: User mengubah jumlah pembelian di keranjang
 */

import { test, expect } from '@playwright/test';
import { loginAsUser } from './helpers/auth';
import { BUKU } from './fixtures/testData';

test.describe.configure({ mode: 'serial' });

// ─── Skenario 8 ────────────────────────────────────────────────────────────

test('08 - User mencari buku berdasarkan judul', async ({ page }) => {
  await loginAsUser(page);

  // Di dashboard ada form pencarian: input[name="q"] + button Filter
  const searchInput = page.locator('input[name="q"]');
  await expect(searchInput).toBeVisible();

  // Cari buku Alpha
  await searchInput.fill('Playwright Alpha');
  await page.locator('button[type="submit"]').filter({ hasText: 'Filter' }).click();

  // Tunggu halaman ter-filter
  await page.waitForLoadState('networkidle');

  // Verifikasi: buku Alpha muncul
  await expect(page.locator('body')).toContainText(BUKU.alpha.judul);

  // Cari kata kunci yang tidak ada
  await searchInput.fill('KatakunciBukuYangPastiTidakAda99999');
  await page.locator('button[type="submit"]').filter({ hasText: 'Filter' }).click();
  await page.waitForLoadState('networkidle');

  // Verifikasi: tidak ada buku, muncul pesan "belum ada buku"
  await expect(page.locator('body')).toContainText('belum ada buku');
});

// ─── Skenario 9 ────────────────────────────────────────────────────────────

test('09 - User menambahkan buku ke keranjang', async ({ page }) => {
  await loginAsUser(page);

  // Buka halaman detail buku Beta (stok 6, bisa ditambah ke keranjang)
  await page.goto(`/buku/detail/${BUKU.beta.id}`);
  await expect(page.locator('body')).toContainText(BUKU.beta.judul);

  // Verifikasi tombol "Tambah ke Keranjang" visible
  const addToCartBtn = page.locator('button[type="submit"]').filter({ hasText: 'Tambah ke Keranjang' });
  await expect(addToCartBtn).toBeVisible();

  // Klik "Tambah ke Keranjang" (form POST /cart/add)
  await addToCartBtn.click();

  // Verifikasi: flash message sukses
  await expect(page.locator('.alert-success')).toContainText('berhasil ditambahkan ke keranjang');

  // Buka halaman keranjang
  await page.goto('/cart');

  // Verifikasi: buku Beta ada di keranjang
  await expect(page.locator('body')).toContainText(BUKU.beta.judul);
});

// ─── Skenario 10 ───────────────────────────────────────────────────────────

test('10 - User mengubah jumlah pembelian di keranjang', async ({ page }) => {
  await loginAsUser(page);

  // Buka halaman keranjang
  await page.goto('/cart');
  await expect(page.locator('body')).toContainText('Keranjang Belanja');

  // Verifikasi buku Beta ada di keranjang
  await expect(page.locator('body')).toContainText(BUKU.beta.judul);

  // Ubah jumlah: input qty dalam form update
  // Di cart_index.php: <input type="number" name="qty" ...>
  const qtyInput = page.locator('input[name="qty"]').first();
  await qtyInput.fill('');
  await qtyInput.fill('3');

  // Klik tombol update (title="Perbarui Jumlah")
  await page.locator('button[title="Perbarui Jumlah"]').first().click();

  // Verifikasi: flash message sukses
  await expect(page.locator('.alert-success')).toContainText('Jumlah buku berhasil diperbarui!');
});
