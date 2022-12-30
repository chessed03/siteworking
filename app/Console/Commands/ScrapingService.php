<?php

namespace App\Console\Commands;

use App\Mail\ScrapingMail;
use App\Models\Email;
use App\Models\Site;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ScrapingService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command return bad urls on did not run the scraping service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sites     = Site::getActivesSites();

        $emails    = Email::getActivesEmails();

        $toMailers = [];

        foreach ( $emails as $key => $email) {

            array_push($toMailers, $email->emailAddress);

        }

        if ( !empty( $toMailers ) ) {

            $mailersToSend = explode(';', implode(';', $toMailers));

            $urlFails = [];

            foreach ( $sites as $key => $site ) {

                $result = __consumeScraperService( $site->siteUrl );

                if ( $result ) {

                    $urlFails[$key] = (object)[
                        'idSite'  => $site->idSite,
                        'siteUrl' => $result->url,
                        'status'  => $result->code
                    ];

                }

            }

            if ( !empty($urlFails) ) {

                $contentMail = $urlFails;

                Site::changeSiteHealtForScrapingProcess( $urlFails );

                Mail::to( $mailersToSend )->send( new ScrapingMail( $contentMail ) );

            }

        }

        return 0;
    }
}
