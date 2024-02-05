<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="text-[15px] h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container-fluid">
        <h5><a class="btn btn-success mb-2" href="{{ route($routeBack) }}">Back</a></h5>
        <h1 class="text-center">{{ __('REPORT PENJUALAN') }}</h1>

        @if (!empty($date))
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <table class="table-bordered table">
                        @if (!empty($date))
                            <tr>
                                @php
                                    $date1 = date('d/M/Y', strtotime($date[0]));
                                    $date2 = date('d/M/Y', strtotime($date[1]));
                                @endphp
                                <th scope="row">{{ _('Range Date') }}</th>
                                <td>{{ $date1 . ' - ' . $date2 }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table-bordered table">
                        <thead class="thead font-weight-bold text-uppercase bg-dark text-light text-center">
                            <tr>
                                <td scope="col">{{ __('Transaction') }}</td>
                                <td scope="col">{{ __('User') }}</td>
                                <td scope="col">{{ __('Total') }}</td>
                                <td scope="col">{{ __('Date') }}</td>
                                <td scope="col">{{ __('Item') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center">{{ $item->document_label ?? '-' }}</td>
                                    <td class="text-center">{{ $item->user_label ?? '-' }}</td>
                                    <td class="text-center">{{ 'Rp. ' . $item->total_label ?? '-' }}</td>
                                    <td class="text-center">{{ $item->date_label ?? '-' }}</td>
                                    @if ($item->details->isEmpty())
                                        <td class="text-center">{{ '' }}</td>
                                    @else
                                        <td class="text-center">
                                            @foreach ($item->details as $detail)
                                                <div>
                                                    {{ $detail->product?->name . ' X ' ?? '' }}{{ $detail?->quantity }}
                                                </div>
                                            @endforeach
                                        </td>
                                    @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            @if ($paginate->total() > 0)
                <div class="col-auto">
                    {{ __('Total') . ': ' . $paginate->total() }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>
