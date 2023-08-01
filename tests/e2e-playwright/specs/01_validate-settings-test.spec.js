/**
 * WordPress dependencies
 */
 const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );
const { selectors } = require('../utils/selectors');
const { commonFunction } = require( "../page/commonFunction" )

test.describe( 'Validate the trust.txt settings', () => {

test( 'Should able to validate trust.txt settings', async ( { admin,page } ) => {

    await admin.visitAdminPage( '/' );
    const commonfunction = new commonFunction(page)
    await commonfunction.navigateToTrusttxtSettings();
  
    } );
} );