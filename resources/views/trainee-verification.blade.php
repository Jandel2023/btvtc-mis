<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trainee Verification</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Georgia, 'Times New Roman', serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #edf5ef;
            color: #173122;
            display: grid;
            place-items: center;
            padding: 1.5rem;
        }

        main {
            width: min(100%, 28rem);
            background: #fff;
            border: 1px solid #c9dccd;
            border-radius: 1rem;
            box-shadow: 0 1rem 2.5rem rgb(25 58 37 / 0.12);
            padding: 2rem;
            text-align: center;
        }

        img {
            width: 5rem;
            height: 5rem;
            object-fit: contain;
        }

        h1 {
            color: #075f39;
            font-size: 1.5rem;
            margin: 1rem 0 0.35rem;
        }

        p {
            color: #617067;
            line-height: 1.5;
        }

        dl {
            display: grid;
            gap: 0.75rem;
            margin: 1.5rem 0 0;
            text-align: left;
        }

        dl div {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #e2ece4;
            padding-bottom: 0.5rem;
        }

        dt {
            color: #617067;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        dd {
            margin: 0;
            font-weight: 700;
            text-align: right;
        }
    </style>
</head>
<body>
    <main>
        <img src="{{ asset('images/btvtc-logo.png') }}" alt="BTVTC logo">
        <h1>Trainee Record Verified</h1>
        <p>This QR code belongs to an active BTVTC trainee record.</p>

        <dl>
            <div>
                <dt>Name</dt>
                <dd>{{ $screening->full_name }}</dd>
            </div>
            <div>
                <dt>Trainee ID</dt>
                <dd>{{ str_pad((string) $screening->id, 6, '0', STR_PAD_LEFT) }}</dd>
            </div>
            <div>
                <dt>Batch</dt>
                <dd>{{ $screening->batch?->batch_name ?? 'Not assigned' }}</dd>
            </div>
            <div>
                <dt>Status</dt>
                <dd>{{ $screening->id_status ?: 'Active' }}</dd>
            </div>
        </dl>
    </main>
</body>
</html>
