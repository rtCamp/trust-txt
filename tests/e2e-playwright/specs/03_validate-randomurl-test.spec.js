/**
 * WordPress dependencies
 */
 const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );

test.describe( 'Validate the random text in trust.txt', () => {

test( 'Should able to validate and give error for random text', async ( { admin,page } ) => {

    await admin.visitAdminPage( '/' );

    await page.hover('role=link[name="Settings"i]');

    await page.click( 'role=link[name="Trust.txt"i]' );

    await page.waitForTimeout(1000);
    await expect(page.locator( "div[class='wrap'] h2" )).toHaveText( 'Manage Trust.txt' );

    await page.click(  "div[class='CodeMirror-lines']" );

    await page.keyboard.press( 'Enter' );

    await page.type(
      "div[class='CodeMirror-lines']",
      "randomtext"
    );

    await page.click("#submit");

    await page.waitForTimeout(6000);
    expect(
      page.locator(
        "div[class='notice notice-error trusttxt-notice trusttxt-errors'] p strong"
      )
    ).toHaveText("Your Trust.txt contains the following issues:");

    } );
} );