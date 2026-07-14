/**
 * 02-admin-buku.spec.ts
 * ─────────────────────────────────────────────────────────────────────────────
 * Skenario 5: Admin menambahkan buku dengan data lengkap
 * Skenario 6: Admin mengubah harga dan stok buku
 * Skenario 7: Admin menghapus buku
 *
 * Tes ini berjalan serial karena skenario saling bergantung:
 *   tambah → edit → hapus
 */

import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers/auth';
import { NEW_BUKU, EDIT_BUKU } from './fixtures/testData';

test.describe.configure({ mode: 'serial' });

// Variabel untuk menyimpan ID buku yang ditambahkan
let addedBookId: string;

// ─── Skenario 5 ────────────────────────────────────────────────────────────

test('05 - Admin menambahkan buku dengan data lengkap', async ({ page }) => {
  await loginAsAdmin(page);

  // Navigasi ke halaman tambah buku
  await page.goto('/buku/create');
  await expect(page.locator('#judul')).toBeVisible();

  // Isi form tambah buku (sesuai view admin/buku/create.php)
  await page.locator('#judul').fill(NEW_BUKU.judul);
  await page.locator('#pengarang').fill(NEW_BUKU.pengarang);
  await page.locator('#penerbit').fill(NEW_BUKU.penerbit);
  await page.locator('#isbn').fill(NEW_BUKU.isbn);
  await page.locator('#genre').fill(NEW_BUKU.genre);
  await page.locator('#harga').fill(NEW_BUKU.harga);
  await page.locator('#stok').fill(NEW_BUKU.stok);
  await page.locator('#deskripsi').fill(NEW_BUKU.deskripsi);

  // Submit form (tombol "Simpan")
  await page.locator('button[type="submit"]').click();

  // Verifikasi: redirect ke /buku dengan flash message
  await expect(page).toHaveURL(/\/buku$/);
  await expect(page.locator('.alert-success')).toContainText('Buku berhasil ditambahkan!');

  // Verifikasi: buku muncul di daftar (judul ada di tabel)
  await expect(page.locator('#tabelBuku')).toContainText(NEW_BUKU.judul);

  // Ambil ID buku yang baru ditambahkan dari href tombol Edit
  // Tombol edit: <a href="/buku/edit/{id}">
  const editLink = page.locator(`a[href*="/buku/edit/"]`).filter({ hasText: 'Edit' }).first();
  const href = await editLink.getAttribute('href');
  addedBookId = href!.split('/').pop()!;
});

// ─── Skenario 6 ────────────────────────────────────────────────────────────

test('06 - Admin mengubah harga dan stok buku', async ({ page }) => {
  await loginAsAdmin(page);

  // Navigasi ke halaman edit buku (buku yang baru ditambahkan)
  await page.goto(`/buku/edit/${addedBookId}`);
  await expect(page.locator('#judul')).toBeVisible();

  // Ubah harga dan stok
  await page.locator('#harga').fill('');
  await page.locator('#harga').fill(EDIT_BUKU.harga);
  await page.locator('#stok').fill('');
  await page.locator('#stok').fill(EDIT_BUKU.stok);

  // Submit form (tombol "Update")
  await page.locator('button[type="submit"]').click();

  // Verifikasi: redirect ke /buku dengan flash message
  await expect(page).toHaveURL(/\/buku$/);
  await expect(page.locator('.alert-success')).toContainText('Buku berhasil diperbarui!');
});

// ─── Skenario 7 ────────────────────────────────────────────────────────────

test('07 - Admin menghapus buku', async ({ page }) => {
  await loginAsAdmin(page);

  // Buka halaman daftar buku
  await page.goto('/buku');
  await expect(page.locator('#tabelBuku')).toBeVisible();

  // Verifikasi buku ada sebelum dihapus
  await expect(page.locator('#tabelBuku')).toContainText(NEW_BUKU.judul);

  // Klik tombol Hapus — handle dialog confirm() dari onclick
  page.on('dialog', async (dialog) => {
    expect(dialog.message()).toContain('Yakin ingin menghapus buku ini?');
    await dialog.accept();
  });

  // Klik tombol hapus untuk buku yang ditambahkan
  await page.locator(`a[href*="/buku/delete/${addedBookId}"]`).click();

  // Tunggu redirect ke /buku setelah delete
  await expect(page).toHaveURL(/\/buku$/);

  // Verifikasi: flash message sukses
  await expect(page.locator('.alert-success')).toContainText('Buku berhasil dihapus!');

  // Verifikasi: buku sudah tidak ada di tabel (berdasarkan tombol Hapus ID tersebut)
  await expect(page.locator(`a[href*="/buku/delete/${addedBookId}"]`)).not.toBeVisible();
});
