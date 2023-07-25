/**
 * WordPress dependencies
 */
const { test, expect } = require("@wordpress/e2e-test-utils-playwright");
const { commonFunction } = require( "../page/commonFunction" )
const { WP_BASE_URL } = require("../e2e-test-utils-playwright/src/config");
const { selectors } = require("../utils/selectors");
 
 test.describe("Add and validate the .txt extension file", () => {
   test("Should able to add the .txt extension file", async ({ admin, page }) => {
     await admin.visitAdminPage("/");
 
     const commonfunction = new commonFunction(page)
     await commonfunction.navigateToTrusttxtSettings();
 
     await page.click( selectors.inputFieldSelector);
 
     await page.type(
       selectors.inputFieldSelector,
       "disclosure=https://test.com/abc.txt"
     );
 
     await page.click(selectors.submitButtonSelector);
   });
 
   test("Should able to validate the .txt extension file", async ({ admin, page }) => {
     await admin.visitAdminPage("/");
 
     await page.goto(WP_BASE_URL + "/trust.txt");
 
    //validate the belong to URL. 
    await page.waitForTimeout(2000);
    expect(page.locator( "body pre" )).toHaveText( /example/ )
 
   });
 });
 