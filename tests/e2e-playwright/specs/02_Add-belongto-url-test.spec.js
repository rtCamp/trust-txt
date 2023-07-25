/**
 * WordPress dependencies
 */
const { test, expect } = require("@wordpress/e2e-test-utils-playwright");
const { WP_BASE_URL } = require("../e2e-test-utils-playwright/src/config");
const { selectors } = require("../utils/selectors");
const { commonFunction } = require( "../page/commonFunction" )
 
 test.describe("Add and validate the belong to URL", () => {
   test("Should able to add the belongto url", async ({ admin, page }) => {
     await admin.visitAdminPage("/");
 
     const commonfunction = new commonFunction(page)
     await commonfunction.navigateToTrusttxtSettings();
 
     await page.click(  "div[class='CodeMirror-lines']" );
 
     await page.keyboard.press( 'Enter' );
     await page.type(
       selectors.inputFieldSelector,
       "belongto=https://example.com"
     );

     await page.keyboard.press( 'Enter' );

     await page.type(
     selectors.inputFieldSelector,
      "social=https://facebook.com/page"
    );
 
     await page.click(selectors.submitButtonSelector);
 
     await page.waitForTimeout(9000);
     expect(
       page.locator(
        selectors.noticeSelector
       )
     ).toHaveText("Trust.txt saved");
   });
 
   test("Should able to validate the belong to URL", async ({ admin, page }) => {
     await admin.visitAdminPage("/");
 
     await page.goto(WP_BASE_URL + "/trust.txt");
 
     // validate the belong to URL. 

    expect(page.locator( "body pre" )).toHaveText( "belongto=https://example.com social=https://facebook.com/page" )
 
   });
 });