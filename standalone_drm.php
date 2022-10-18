<html>
    <head>
        <title></title>
        <!-- Your JWPlayer library url goes here-->
        <script src="https://cdn.jwplayer.com/libraries/156EkXiN.js"></script>
    </head>    
<body >
    <div id="container">
        <div id="jwplayer"></div>
    </div>
    <script>
        <?php
        $api_key = "We will provide you with this Studio DRM key"
        $url_hls = "THIS COMES FROM YOUR BCL DASHBOARD.  BCL > LIVE > Event > Distribution > Streaming URLs: HLS"
        $url_dash = "THIS COMES FROM YOUR BCL DASHBOARD.  BCL > LIVE > Event > Distribution > Streaming URLs: DASH"
        $fairplay_cert_url = "This is your fairplay certificate URL"
        $drm_client_name = "We will provide you this client name"

        // Make call to API and request token
        $curl = curl_init();
        $data = '{"clientName": "'.$drm_client_name.'","policy": {"rental_duration_seconds":"3600"}}'
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://token.vudrm.tech/v2/generate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "x-api-key: ".$api_key
            ],
        ]);
    
        $token = curl_exec($curl);
    
        curl_close($curl);

        ?>

    (function() {
    // Set the mpeg-dash stream URL.
    var dashStreamURL = <?php echo "'".$url_dash."';"?>;
    // Set the hls stream URL.
    var hlsStreamURL = <?php echo "'".$url_hls."';"?>;

    // Set the URL to retrieve the fairplay certificate from.
    var fairplayCertURL = <?php echo "'".$fairplay_cert_url."';"?>;

    // Please login to https://admin.vudrm.tech to generate a VUDRM token.
    var vudrmToken = <?php echo "'".$token."';"?>;

    // setup jwplayer, passing the stream URLs and DRM configurations.  
    jwplayer("jwplayer").setup({
        "playlist": [{
            "sources": [{
                "file": dashStreamURL,
                "drm": {
                    "widevine": {
                        "url": "https://widevine-license.vudrm.tech/proxy",
                        "headers": [{
                            "name": "X-VUDRM-TOKEN",
                            "value": vudrmToken
                        }]
                    },
                    "playready": {
                        "url": "https://playready-license.vudrm.tech/rightsmanager.asmx",
                        "headers": [{
                            "name": "X-VUDRM-TOKEN",
                            "value": vudrmToken
                        }]
                    }
                }
            }, 
            {
                "file": hlsStreamURL,
                "drm": {
                    "fairplay": {
                        "certificateUrl": fairplayCertURL,
                        "processSpcUrl": function (initData) {
                            return "https://" + initData.split("skd://").pop();
                        },
                        "licenseRequestHeaders": [
                            {
                                "name": "Content-type",
                                "value": "arraybuffer"
                            },
                            {
                                "name": "X-VUDRM-TOKEN",
                                "value": vudrmToken
                            }
                        ]
                    }
                }
            }]
        }]
    });
})();

    </script>
</body>

</html>
