<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Information servers</title>

    <style>
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }
        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }
        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }

        header h1 {
            font-size: 70px;
            font-weight: 600;
            background-color: #009879;
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
        }
    </style>

</head>

<body>



    <header>
        <h1 style="color: #FFFFFF">Information servers</h1>
    </header>

        <table class="styled-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>URL</th>
                    <th>Fail status</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $contentMail as $key => $content )

                    <tr>
                        <td>
                            {{ $content->idSite }}
                        </td>

                        <td>
                            {{ $content->siteUrl }}
                        </td>

                        <td>
                            {{ $content->status }}
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

</body>

</html>
