import { expect } from "@playwright/test";
import { selectors } from "../utils/selectors";

export class commonFunction {

    constructor(page) {
        this.page = page;

    }
    // Function used to navigate to the Trust.txt settings Page. 
    async navigateToTrusttxtSettings(){
        await this.page.hover(selectors.settingsButton);

        await this.page.click( selectors.trusttxtSettings );

        await expect(this.page.locator( selectors.headingTrustPage )).toHaveText( 'Manage Trust.txt' );
    }
    
}