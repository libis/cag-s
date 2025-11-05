// Prepare multiple uv4.
document.addEventListener('DOMContentLoaded', function (event) {

    // The config is defined inside the html.
    if (typeof uvConfigs === 'undefined') {
        return;
    }
    console.log("4");
    uvConfigs.forEach(function (uvConfig) {
        const urlAdapter = new UV.IIIFURLAdapter();

        var data = {
            manifest: uvConfig.manifest,
            embedded: uvConfig.embedded,
            collectionIndex: urlAdapter.get('c') !== undefined ? Number(urlAdapter.get('c')) : undefined,
            manifestIndex: Number(urlAdapter.get('m', 0)),
            canvasIndex: urlAdapter.get('searchText') ? undefined : Number(urlAdapter.get('cv', 0)),
            rotation: Number(urlAdapter.get('r', 0)),
            rangeId: urlAdapter.get('rid', ''),
            highlight: urlAdapter.get('searchText'),
            xywh: urlAdapter.get('searchText') ? '' : urlAdapter.get('xywh', ''),
            target: ''
            //target: urlAdapter.get('target', ''),
        };

        uv = UV.init(uvConfig.id, data);
        urlAdapter.bindTo(uv);

       uv.on('searchResultsAvailable', function(searchResults) {
    console.log('Search results available:', searchResults);
    
    // Wait for the UI to be ready
    setTimeout(function() {
        try {
            // Access the extension and search panel
            const ext = uv.extension;
            const searchPanel = ext.searchFooterPanel;
            
            if (searchPanel) {
                console.log('Search panel found');
                
                // Try to trigger selection of first result
                if (searchPanel.selectResult) {
                    searchPanel.selectResult(0);
                } else if (searchPanel.elideResultsTermsCount !== undefined) {
                    // Trigger the result selection event
                    searchPanel.selectIndex(0);
                }
                
                // Also try triggering the canvas change with xywh
                const firstResult = searchResults.resources[0];
                if (firstResult && firstResult.on) {
                    const parts = firstResult.on.split('#xywh=');
                    const canvasUri = parts[0];
                    const xywh = parts[1];
                    
                    console.log('First result canvas:', canvasUri);
                    console.log('First result xywh:', xywh);
                    
                    // Try to navigate directly
                    ext.viewCanvas(canvasUri, xywh);
                }
            }
        } catch (e) {
            console.error('Error navigating to result:', e);
        }
    }, 500);
});
        
        if (uvConfig.configUri) {
            uv.on('configure', function ({ config, cb }) {
                cb(
                    // To increase loading speed, just use the specific settings you require.
                    // {options: { footerPanelEnabled: false, }}
                    // Full config:
                    // @see https://github.com/UniversalViewer/universalviewer/wiki/UV-Examples
                    new Promise(function (resolve) {
                        fetch(uvConfig.configUri).then(function (response) {
                            resolve(response.json());
                        });
                    })
                );
           });

           
       }

        if (!uvConfig.embedded) {
            return;
        }

        const $UV = document.getElementById(uvConfig.id);

        function resize() {
            $UV.setAttribute('style', 'width:' + window.innerWidth + 'px');
            $UV.setAttribute('style', 'height:' + window.innerHeight + 'px');
        }

        document.addEventListener('resize', function () {
            resize();
        });

        resize();
    });

});
