/**
 * WordPress dependencies
 */
const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );
const { commonFunction } = require( "../page/commonFunction" );
const { selectors } = require('../utils/selectors');

test.describe( 'Validate the random text in trust.txt', () => {

test( 'Should able to validate and give error for random text', async ( { admin,page } ) => {

    await admin.visitAdminPage( '/' );

    const commonfunction = new commonFunction(page)
    await commonfunction.navigateToTrusttxtSettings();

    await page.click( selectors.inputFieldSelector );

    await page.keyboard.press( 'Enter' );

    await page.type(
     selectors.inputFieldSelector,
      "randomtext"
    );

    await page.click(selectors.submitButtonSelector);

    await page.waitForTimeout(6000);
    expect(
      page.locator(
        "div[class='notice notice-error trusttxt-notice trusttxt-errors'] p strong"
      )
    ).toHaveText("Your Trust.txt contains the following issues:");

    } );
} );