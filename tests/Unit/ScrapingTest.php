<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Goutte\Client as Scrapper;
use Illuminate\Http\Request;
#use PHPUnit\Framework\TestCase;

class ScrapingTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testScraping()
    {
        $scrap = new Scrapper();

        $url = 'https://www.worldometers.info/coronavirus/';
        //$url = 'http://127.0.0.1:8001/works';
        $urlFails = [];

        try {

            $crawler = $scrap->request('GET', $url);

            $response = $scrap->getInternalResponse();

            $response->getStatusCode();

            $body = $crawler->filter("body")->nodeName();

            if ( $body != "body" || $response->getStatusCode() != 200 ) {

                $urlFails = (object)[
                    'url'  => $url,
                    'code' => $response->getStatusCode()
                ];

            }

        } catch (\Throwable $e) {

            $e->getMessage();

            $urlFails = (object)[
              'url'  => $url,
              'code' => 'no code'
            ];

        }

        dd($urlFails);

        $this->assertTrue(true);
    }

    public function testServiceScraper()
    {

        $query = DB::table('sites')
            ->select(DB::raw('
                idSite,
                siteUrl
            '));

        $query->whereRaw('siteStatus = 1');

        $datas = $query->get();

        $urlFails = [];

        foreach ( $datas as $key => $record ) {

            $result = __consumeScraperService( $record->siteUrl );

            if ( $result ) {

                $urlFails[$key] = (object)[
                    'idSite'  => $record->idSite,
                    'siteUrl' => $result->url,
                    'status'  => $result->code
                ];

            }
        }

        dd($urlFails);

    }

}
