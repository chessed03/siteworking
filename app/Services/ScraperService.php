<?php

use Goutte\Client as Scrapper;
use Illuminate\Http\Request;

function __consumeScraperService( $url_request )
{

    $scrap = new Scrapper();

    $urlFail = [];

    try {

        $crawler = $scrap->request('GET', $url_request);

        $body    = $crawler->filter("body")->nodeName();

        $response = $scrap->getInternalResponse();

        if ( $body != "body" || $response->getStatusCode() != 200 ) {

            $urlFail = (object)[
                'url'  => $url_request,
                'code' => $response->getStatusCode()
            ];

        }

    } catch (\Throwable $e) {

        $e->getMessage();

        $urlFail = (object)[
            'url'  => $url_request,
            'code' => $e->getMessage()
        ];

    }

    return $urlFail;

}


