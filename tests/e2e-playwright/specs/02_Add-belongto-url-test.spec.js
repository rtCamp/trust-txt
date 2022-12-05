/**
 * WordPress dependencies
 */
const { test, expect } = require("@wordpress/e2e-test-utils-playwright");

const { WP_BASE_URL } = require("../e2e-test-utils-playwright/src/config");

test.describe("Add and validate the belong to URL", () => {
  test("Should able to add the belongto url", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    await page.hover('role=link[name="Settings"i]');

    await page.click('role=link[name="Trust.txt"i]');

    await page.waitForTimeout(1000);
    await expect(page.locator("div[class='wrap'] h2")).toHaveText(
      "Manage Trust.txt"
    );

    await page.click(  "div[class='CodeMirror-lines']" );

    await page.type(
      "div[class='CodeMirror-lines']",
      "belongto=https://example.com"
    );

    await page.click("#submit");

    await page.waitForTimeout(9000);
    expect(
      page.locator(
        "div[class='notice notice-success trusttxt-notice trusttxt-saved'] p"
      )
    ).toHaveText("Trust.txt saved");
  });

  test("Should able to validate the belong to URL", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    await page.goto(WP_BASE_URL + "/trust.txt");

    // Add the page content in array and validate the belong to URL. 
    var pagecontent = [];
    pagecontent = await page.content();
    pagecontent.includes("belongto=https://example.com");

  });
});
