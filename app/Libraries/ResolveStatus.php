<?php

namespace App\Libraries;
class ResolveStatus
{
    public static function siteHealth( $idStatus )
    {
        $status = [];

        switch ( $idStatus ) {
            case 0:

                $status = (object)[
                    'type' => 'secondary',
                    'icon' => 'bx bx-fw bx-power-off',
                    'text' => 'Sin procesar'
                ];

                break;
            case 1:

                $status = (object)[
                    'type' => 'success',
                    'icon' => 'bx bx-fw bx-check-circle',
                    'text' => 'Correcto'
                ];

                break;
            case 2:

                $status = (object)[
                    'type' => 'danger',
                    'icon' => 'bx bx-fw bx-error',
                    'text' => 'Error'
                ];

                break;
            default:

                $status = (object)[
                    'type' => 'secondary',
                    'icon' => 'bx bx-fw bx-bell-off',
                    'text' => 'No verificado'
                ];
        }

        return $status;
    }
}

