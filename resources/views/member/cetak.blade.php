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
            overflow: hidden;
            
        }
        .card {
            width: 100.60mm;
            height: 100%;
            position: relative;
            background: #f5f5f5; /* Gambar kartu bisa ditambahkan di sini */
        }
        .logo {
            position: absolute;
            top: 10px;
            right: 136px;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #fff;
        }
        .logo p {
            position: absolute;
            text-align: left;
            margin-right: 10pt;
        }
        .logo img {
            width: 80px;
            height: 80px;
        }
        .nama {
            position: absolute;
            top: 80px;
            right: 40px;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff;
        }
        .telepon {
            position: absolute;
            top: 110px;
            right: 40px;
            font-size: 10pt;
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
                            <div class="box">
                                <img src="{{ public_path($setting->path_member_card) }}" alt="card" width="90%">
                                <div class="logo">
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