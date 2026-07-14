/**
 * 04-checkout.spec.ts
 * ─────────────────────────────────────────────────────────────────────────────
 * Skenario 11: User checkout berhasil
 *
 * Prasyarat: Buku Beta sudah di keranjang (dari 03-katalog-cart.spec.ts)
 * Jika keranjang kosong, test ini akan menambahkan buku dulu.
 */

import { test, expect } from '@playwright/test';
import { loginAsUser } from './helpers/auth';
import { BUKU, CHECKOUT_DATA } from './fixtures/testData';

test('11 - User checkout berhasil', async ({ page }) => {
  await loginAsUser(page);

  // Pastikan ada buku di keranjang — tambahkan jika kosong
  await page.goto('/cart');
  const isCartEmpty = await page.locator('body').textContent();
  if (isCartEmpty?.includes('Keranjang belanja Anda masih kosong')) {
    // Tambah buku Beta ke keranjang dulu
    await page.goto(`/buku/detail/${BUKU.beta.id}`);
    await page.locator('button[type="submit"]').filter({ hasText: 'Tambah ke Keranjang' }).click();
    await expect(page.locator('.alert-success')).toContainText('berhasil ditambahkan ke keranjang');
    await page.goto('/cart');
  }

  // Verifikasi ada buku di keranjang
  await expect(page.locator('body')).toContainText(BUKU.beta.judul);

  // Klik "Lanjutkan ke Checkout"
  await page.locator('a').filter({ hasText: 'Lanjutkan ke Checkout' }).click();

  // Verifikasi: halaman checkout terbuka
  await expect(page).toHaveURL(/\/checkout/);
  await expect(page.locator('body')).toContainText('Checkout');

  // Isi form pengiriman (sesuai checkout_index.php)
  await page.locator('#nama_penerima').fill('');
  await page.locator('#nama_penerima').fill(CHECKOUT_DATA.nama_penerima);
  await page.locator('#no_hp').fill('');
  await page.locator('#no_hp').fill(CHECKOUT_DATA.no_hp);
  await page.locator('#alamat').fill('');
  await page.locator('#alamat').fill(CHECKOUT_DATA.alamat);
  await page.locator('#catatan').fill(CHECKOUT_DATA.catatan);

  // Submit: Klik "Buat Pesanan Sekarang"
  await page.locator('button[type="submit"]').filter({ hasText: 'Buat Pesanan Sekarang' }).click();

  // Verifikasi: redirect ke /orders dengan flash sukses
  await expect(page).toHaveURL(/\/orders/);
  await expect(page.locator('.alert-success')).toContainText('Checkout berhasil!');
});
