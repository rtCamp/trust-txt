/**
 * WordPress dependencies
 */
const { test, expect } = require("@wordpress/e2e-test-utils-playwright");

test.describe("Validate the Revisions settings", () => {
  test("Should able to validate revision settings", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    await page.hover('role=link[name="Settings"i]');

    await page.click('role=link[name="Trust.txt"i]');

    await page.waitForTimeout(1000);
    await expect(page.locator("div[class='wrap'] h2")).toHaveText(
      "Manage Trust.txt"
    );

    await page.screenshot({path: "uploads/browse.png"});

    await page.click('role=link[name="Browse revisions"i]');

    await page.waitForTimeout(1000);

    await expect(page.locator(".long-header")).toHaveText(
      /Compare Revisions of “Trust.txt”/
    );
  });

  test("Should able to browse the revision", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    await page.hover('role=link[name="Settings"i]');

    await page.click('role=link[name="Trust.txt"i]');

    await page.waitForTimeout(1000);
    await expect(page.locator("div[class='wrap'] h2")).toHaveText(
      "Manage Trust.txt"
    );

    await page.screenshot({path: "uploads/browse.png"});

    await page.click('role=link[name="Browse revisions"i]');

    await page.waitForTimeout(1000);

    await expect(page.locator(".long-header")).toHaveText(
      /Compare Revisions of “Trust.txt”/
    );

    await page.click('role=button[name="Previous"i]');

    await page.click('role=checkbox[name="Compare any two revisions"i]');
  });
});
