<?php

function api_auth_function($url, $donnees, $headers) {
    $options = [
        'http' => [
            'header' => implode("\r\n", $headers),
            'method' => 'POST',
            'content' => json_encode($donnees)
        ]
    ];
    $context = stream_context_create($options);
    $resultat = file_get_contents($url, false, $context);
    return $resultat;
}

function api_data_function($url, $headers) {
    $options = [
        'http' => [
            'header' => implode("\r\n", $headers),
            'method' => 'GET',
        ]
    ];
    $context = stream_context_create($options);
    $resultat = file_get_contents($url, false, $context);
    return $resultat;
}

function api_data_array_function(array $urls, $headers) {
    $resultats = [];
    
    foreach ($urls as $url) {
        $options = [
            'http' => [
                'header' => implode("\r\n", $headers),
                'method' => 'GET',
            ]
        ];
        $context = stream_context_create($options);
        $resultat = file_get_contents($url, false, $context);
        $resultats[] = $resultat; 
    }
    
    return $resultats;
}

?>
