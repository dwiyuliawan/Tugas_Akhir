<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kartu Member</title>

    <style>
        .box {
            position: relative;
            
        }
        .card {
            width: 85.60mm;
        }
        .logo {
            top: 1pt;
            right: 0pt;
            font-size: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
            padding-right: 30px;
        }
        .logo p {
            position: absolute;
            text-align: left;
            margin-right: 10pt;
        }
        .logo img {
            position: absolute;
            margin-top: -5pt;
            width: 40px;
            height: 40px;
            right: 16pt;
        }
        .nama {
            position: absolute;
            top: 100pt;
            right: 16pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }
        .telepon {
            position: absolute;
            margin-top: 120pt;
            right: 16pt;
            color: #fff;
        }
        .barcode {
            position: absolute;
            top: 65pt;
            left: .860rem;
            border: 1px solid #fff;
            background: white;
        }
        .barcode img {

        }

        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <section style="border: 1px solid #fff">
        <table width="100%">
            @foreach ($datamember as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center">
                            <div class="box" width="50%">
                                <img src="{{ public_path($setting->path_member_card) }}" alt="card" width="90%">
                                <div class="logo">
                                    <p>{{ $setting->company_name }}</p>
                                    <img src="{{ public_path($setting->path_logo) }}" alt="logo">
                                </div>
                                <div class="nama">{{ $item->name }}</div>
                                <div class="telepon" >{{ $item->phone_number }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ $barcode->getBarcodePNG("$item->member_code", 'QRCODE') }}" alt="qrcode"
                                        height="45"
                                        widht="45">
                                </div>
                            </div>
                        </td>
                        
                        @if (count($datamember) == 1)
                        <td class="text-center" style="width: 50%;"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>
</html>