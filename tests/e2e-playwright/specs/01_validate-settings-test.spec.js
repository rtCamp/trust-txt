/**
 * WordPress dependencies
 */
 const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );

test.describe( 'Validate the trust.txt settings', () => {

test( 'Should able to validate trust.txt settings', async ( { admin,page } ) => {

    await admin.visitAdminPage( '/' );

    await page.hover('role=link[name="Settings"i]');

    await page.click( 'role=link[name="Trust.txt"i]' );

    await expect(page.locator( "div[class='wrap'] h2" )).toHaveText( 'Manage Trust.txt' );
    } );
} );