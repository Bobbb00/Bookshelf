import { defineConfig, devices } from '@playwright/test';
import path from 'path';

/**
 * Playwright configuration untuk Bookshelf CI4 Black-Box Testing.
 *
 * Variabel environment yang dapat di-override:
 *   PLAYWRIGHT_BASE_URL  - default: http://localhost:8080
 *   CI                   - jika ada, mode CI (tanpa headed)
 */
const BASE_URL = process.env.PLAYWRIGHT_BASE_URL ?? 'http://localhost:8080';

export default defineConfig({
  // ─── Direktori test ────────────────────────────────────────────────────────
  testDir: './tests/e2e',

  // ─── Output ────────────────────────────────────────────────────────────────
  outputDir: './test-results/artifacts',

  // ─── Timeout ───────────────────────────────────────────────────────────────
  timeout:             30_000,  // Timeout per test
  expect:              { timeout: 8_000 },
  actionTimeout:       10_000,
  navigationTimeout:   20_000,

  // ─── Parallelisme ──────────────────────────────────────────────────────────
  // Jalankan file test secara paralel, namun masing-masing file dijalankan
  // secara serial agar state (session, cart, order) tidak bertabrakan.
  fullyParallel: false,
  workers: 1,  // 1 worker untuk konsistensi dengan shared database

  // ─── Retry ─────────────────────────────────────────────────────────────────
  retries: 1,  // Retry 1x untuk mengatasi flakiness jaringan

  // ─── Reporter ──────────────────────────────────────────────────────────────
  reporter: [
    // Terminal output
    ['list'],
    // HTML report lengkap
    ['html', {
      outputFolder: './test-results/playwright-report',
      open: 'never',
    }],
  ],

  // ─── Konfigurasi global browser ─────────────────────────────────────────
  use: {
    baseURL: BASE_URL,
    browserName: 'chromium',

    // Screenshot: always (setiap test, berhasil maupun gagal)
    screenshot: 'on',

    // Video: hanya saat retry pertama (ketika test gagal)
    video: 'on-first-retry',

    // Trace: hanya saat retry pertama (ketika test gagal)
    trace: 'on-first-retry',

    // Lokasi screenshot per-test (dikelola oleh helper kita)
    // Screenshot bernomor diatur di afterEach hook pada global fixtures

    // Locale & timezone
    locale: 'id-ID',
    timezoneId: 'Asia/Jakarta',

    // Viewport default
    viewport: { width: 1280, height: 720 },

    // Jangan simpan password di screenshot/video — field password ditutupi
    // via page.locator('input[type=password]').fill() tanpa expose di log

    // Extra HTTP headers
    extraHTTPHeaders: {
      'Accept-Language': 'id-ID,id;q=0.9',
    },
  },

  // ─── Hanya Chromium ────────────────────────────────────────────────────────
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        channel: 'chromium',
      },
    },
  ],

  // ─── Folder output terstruktur ─────────────────────────────────────────────
  // Screenshot, video, trace disimpan di test-results/artifacts/<test-title>/
  // Screenshot bernomor per-skenario dikelola di tests/e2e/helpers/screenshots.ts

  // ─── Web Server ────────────────────────────────────────────────────────────
  // Jalankan `php spark serve` otomatis jika server belum berjalan.
  webServer: {
    command: 'php spark serve',
    url: BASE_URL,
    reuseExistingServer: true,  // Gunakan server yang sudah berjalan
    timeout: 30_000,
    stdout: 'ignore',
    stderr: 'pipe',
    cwd: '..',
  },
});
