const selectors = {
    settingsButton: 'role=link[name="Settings"i]',
    trusttxtSettings: 'role=link[name="Trust.txt"i]',
    headingTrustPage: "div[class='wrap'] h2" ,
    inputFieldSelector: "div[class='CodeMirror-lines']",
    submitButtonSelector: "#submit",
    noticeSelector:  "div[class='notice notice-success trusttxt-notice trusttxt-saved'] p",
    browserVersionLink: 'role=link[name="Browse revisions"i]'

}

module.exports = { selectors };