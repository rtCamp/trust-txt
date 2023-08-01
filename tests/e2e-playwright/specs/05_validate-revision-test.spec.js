/**
 * WordPress dependencies
 */
const { test, expect } = require("@wordpress/e2e-test-utils-playwright");
const { commonFunction } = require( "../page/commonFunction" );
const { selectors } = require("../utils/selectors");


test.describe("Validate the Revisions settings", () => {

  test("Should able to add the .txt extension file before revision", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    const commonfunction = new commonFunction(page)
    await commonfunction.navigateToTrusttxtSettings();

    await page.click( selectors.inputFieldSelector );

    await page.keyboard.press( 'Enter' );

    await page.type(
      selectors.inputFieldSelector,
      "social=https://facebook.com/page2"
    );

    await page.click(selectors.submitButtonSelector);
  });

  test("Should able to validate revision settings", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    const commonfunction = new commonFunction(page)
    await commonfunction.navigateToTrusttxtSettings();

    if( await page.locator(selectors.browserVersionLink).count > 0){
      await page.click(selectors.browserVersionLink);

      await page.waitForTimeout(1000);
  
      await expect(page.locator(".long-header")).toHaveText(
        /Compare Revisions of “Trust.txt”/
      );
    }
  
  });

  test("Should able to browse the revision", async ({ admin, page }) => {
    await admin.visitAdminPage("/");

    const commonfunction = new commonFunction(page)
    await commonfunction.navigateToTrusttxtSettings();
    
    if( await page.locator(selectors.browserVersionLink).count > 0){
    await page.click(selectors.browserVersionLink);

    await page.waitForTimeout(1000);

    await expect(page.locator(".long-header")).toHaveText(
      /Compare Revisions of “Trust.txt”/
    );

    await page.click('role=button[name="Previous"i]');

    await page.click('role=checkbox[name="Compare any two revisions"i]');

    }
  });
});
