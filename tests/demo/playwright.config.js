const { defineConfig } = require('@playwright/test');
module.exports = defineConfig({
    testDir: '.',
    testMatch: 'demo.spec.js',
    timeout: 60000,
    workers: 1,
    reporter: 'list',
    use: { baseURL: process.env.DEMO_BASE_URL || 'http://127.0.0.1:3000', browserName: 'chromium' }
});
