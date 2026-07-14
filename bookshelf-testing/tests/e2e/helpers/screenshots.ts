/**
 * screenshots.ts
 * Helper untuk menyimpan screenshot bernomor ke folder test-results/screenshots/
 *
 * Nama file: NN-slug-nama-skenario.png
 * Contoh:    01-registrasi-berhasil.png
 */

import { Page } from '@playwright/test';
import * as path from 'path';
import * as fs from 'fs';

const SCREENSHOT_DIR = path.join(process.cwd(), 'test-results', 'screenshots');

// Pastikan folder ada
if (!fs.existsSync(SCREENSHOT_DIR)) {
  fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

/**
 * Simpan screenshot bernomor.
 *
 * @param page     Playwright Page object
 * @param number   Nomor skenario (1-16), diformat jadi 2 digit (01, 02, ...)
 * @param name     Nama deskriptif (akan dikonversi ke slug)
 */
export async function takeScreenshot(
  page: Page,
  number: number,
  name: string
): Promise<string> {
  const num = String(number).padStart(2, '0');
  const slug = name
    .toLowerCase()
    .replace(/[^a-z0-9\u00C0-\u017E\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .slice(0, 80);

  const filename = `${num}-${slug}.png`;
  const filepath = path.join(SCREENSHOT_DIR, filename);

  await page.screenshot({
    path: filepath,
    fullPage: false,
  });

  return `test-results/screenshots/${filename}`;
}

/**
 * Simpan screenshot full page (untuk halaman panjang seperti daftar buku).
 */
export async function takeFullPageScreenshot(
  page: Page,
  number: number,
  name: string
): Promise<string> {
  const num = String(number).padStart(2, '0');
  const slug = name
    .toLowerCase()
    .replace(/[^a-z0-9\u00C0-\u017E\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .slice(0, 80);

  const filename = `${num}-${slug}-full.png`;
  const filepath = path.join(SCREENSHOT_DIR, filename);

  await page.screenshot({
    path: filepath,
    fullPage: true,
  });

  return `test-results/screenshots/${filename}`;
}
