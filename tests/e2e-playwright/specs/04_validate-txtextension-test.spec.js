/**
 * WordPress dependencies
 */
 const { test, expect } = require("@wordpress/e2e-test-utils-playwright");

 const { WP_BASE_URL } = require("@wordpress/e2e-test-utils-playwright/src/config");
 
 test.describe("Add and validate the .txt extension file", () => {
   test("Should able to add the .txt extension file", async ({ admin, page }) => {
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
       "disclosure=https://test.com/abc.txt"
     );
 
     await page.click("#submit");
   });
 
   test("Should able to validate the .txt extension file", async ({ admin, page }) => {
     await admin.visitAdminPage("/");
 
     await page.goto(WP_BASE_URL + "/trust.txt");
 
    //validate the belong to URL. 
    await page.waitForTimeout(2000);
    expect(page.locator( "body pre" )).toHaveText( /example/ )
 
   });
 });
 