CookieConsent.run({
    guiOptions: {
        consentModal: {
            layout: "box",
            position: "bottom left",
            equalWeightButtons: true,
            flipButtons: false
        },
        preferencesModal: {
            layout: "box",
            position: "right",
            equalWeightButtons: true,
            flipButtons: false
        }
    },
    categories: {
        necessary: {
            readOnly: true
        },
        analytics: {
            enabled: true
        }
    },
    language: {
        default: "en",
        autoDetect: "browser",
        translations: {
            en: {
                consentModal: {
                    title: "CAG Cookies",
                    description: "By clicking “Accept all cookies”, you agree to the storing of cookies on your device to improve website navigation and analyze website usage.<br><br>By clicking ‘Cookie settings’ you can manage your preferences.",
                    acceptAllBtn: "Accept all cookies",
                    showPreferencesBtn: "Cookie settings",
                    footer: "<a href=\"https://cagnet.be/s/en/page/privacyverklaring\">Privacy policy</a>\n<a href=\"https://cagnet.be/s/en/page/cookies\">Cookies</a>"
                    // acceptNecessaryBtn intentionally omitted here → no reject button on the initial banner
                },
                preferencesModal: {
                    title: "Cookie settings",
                    acceptAllBtn: "Accept all",
                    savePreferencesBtn: "Save settings",
                    closeIconLabel: "Close",
                    serviceCounterLabel: "Service|Services",
                    // acceptNecessaryBtn omitted here too, to match the Dutch version → no "reject all" button, only per-category toggles
                    sections: [
                        {
                            title: "Cookie usage",
                            description: "Our website uses cookies. A cookie is a small text file that a website stores, via the browser, on your hard drive or mobile device when you visit the website. Cookies cannot be used to identify individuals; a cookie can only identify a machine.<br><br>CAG's website uses Google Analytics, a web analytics service provided by Google Inc. (“Google”).<br><br>Strictly necessary cookies are essential for the website to function properly, and these cannot be refused if you wish to visit this site."
                        },
                        {
                            title: "Strictly Necessary Cookies <span class=\"pm__badge\">Always Enabled</span>",
                            description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                            linkedCategory: "necessary"
                        },
                        {
                            title: "Analytics Cookies",
                            description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                            linkedCategory: "analytics"
                        }
                    ]
                }
            },
            nl: {
                consentModal: {
                    title: "CAG Cookies",
                    description: "Door op “Alle cookies toestaan” te klikken gaat u akkoord met het opslaan van cookies op uw apparaat voor het verbeteren van websitenavigatie en het analyseren van websitegebruik.<br><br>Door op 'Cookie-instellingen' te klikken kan u uw voorkeuren beheren.",
                    acceptAllBtn: "Alle cookies toestaan",
                    showPreferencesBtn: "Cookie-instellingen",
                    footer: "<a href=\"https://cagnet.be/page/privacyverklaring\">Privacybeleid</a>\n<a href=\"https://cagnet.be/page/cookies\">Cookies</a>"
                },
                preferencesModal: {
                    title: "Cookie instellingen",
                    acceptAllBtn: "Alles accepteren",
                    savePreferencesBtn: "Instellingen opslaan",
                    closeIconLabel: "Sluiten",
                    serviceCounterLabel: "Dienst|Diensten",
                    sections: [
                        {
                            title: "Cookiegebruik",
                            description: "Wij maken op onze website gebruik van cookies. Een cookie is een eenvoudig klein tekstbestand dat een website via de browser opslaat op uw harde schrijf of uw mobiel apparaat wanneer u de website raadpleegt. Cookies kunnen niet worden gebruikt om personen te identificeren, een cookie kan slechts een machine identificeren.<br><br>De website van CAG maakt gebruik van Google Analytics, een webanalyse-service die wordt aangeboden door Google Inc. (“Google”).<br><br>Strikt noodzakelijke cookies zijn essentieel om de website goed te doen functioneren en kunt u niet weigeren als u deze site wilt bezoeken."
                        },
                        {
                            title: "Strikt noodzakelijke cookies <span class=\"pm__badge\">Altijd ingeschakeld</span>",
                            description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                            linkedCategory: "necessary"
                        },
                        {
                            title: "Analytische cookies",
                            description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                            linkedCategory: "analytics"
                        }
                    ]
                }
            }
        }
    }
});