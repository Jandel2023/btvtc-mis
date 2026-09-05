@php
    $photoUrl = $record->picture
        ? \Illuminate\Support\Facades\Storage::url($record->picture)
        : asset('images/id-picture.jpg');

    $fullName = $record->full_name ?: 'Unnamed Trainee';

    $batchName = $record->batch?->batch_name ?? 'Not assigned';

    $qualification = $record->batch?->qualification?->qualification_title
        ?? 'Not assigned';

    $qualificationlevel =$record->batch?->qualification?->qualification_level_id ?? null;

    $issuedDate = $record->date_screened
        ? \Illuminate\Support\Carbon::parse($record->date_screened)->format('d M Y')
        : 'Not available';

    $expiryDate = $record->batch?->end_date
        ? \Illuminate\Support\Carbon::parse($record->batch->end_date)->format('d M Y')
        : 'Not specified';

    $trainerName = $record->screened_by ?: 'Not specified';

    $traineeId = 'TR-' . str_pad(
        (string) $record->id,
        4,
        '0',
        STR_PAD_LEFT
    );
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $fullName }} - Trainee ID</title>

    <style>
        /* =========================================================
           BTVTC TRAINEE ID CARD
           PAPER  : SHORT BOND PAPER - LANDSCAPE
           PAPER  : 11in x 8.5in
           ID     : PORTRAIT
           ID     : 2.125in x 3.375in
           ========================================================= */

        :root {
            --id-green: #0e3d2f;
            --id-green-light: #146c43;
            --id-gold: #f2c94c;
            --id-dark: #17231d;
            --id-muted: #607269;
            --id-border: #cfddd2;
            --id-light: #f4f8f5;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #e9eeeb;
            color: var(--id-dark);
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        /* =========================================================
           TOOLBAR
           ========================================================= */

        .trainee-id-toolbar {
            width: min(11in, calc(100% - 2rem));
            margin: 1rem auto;
            padding: 0.75rem 1rem;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;

            background: #ffffff;
            border: 1px solid #dce8df;
            border-radius: 0.75rem;

            box-shadow:
                0 0.25rem 0.8rem rgb(19 55 36 / 0.08);
        }

        .trainee-id-toolbar p {
            margin: 0;

            color: var(--id-muted);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .trainee-id-download-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0.6rem 0.9rem;

            color: #ffffff;
            background: var(--id-green-light);

            border-radius: 0.5rem;

            font-size: 0.75rem;
            font-weight: 700;

            text-decoration: none;

            transition:
                background-color 150ms ease,
                transform 150ms ease;
        }

        .trainee-id-download-button:hover {
            background: var(--id-green);
            transform: translateY(-1px);
        }

        /* =========================================================
           SHORT BOND PAPER
           
           IMPORTANT:
           The PAPER is LANDSCAPE.
           
           11in wide
           8.5in high
           ========================================================= */

        .id-paper {
            position: relative;

            width: min(11in, calc(100% - 2rem));
            aspect-ratio: 11 / 8.5;

            margin: 0 auto 2rem;

            background: #ffffff;

            border: 1px solid #d5ded8;

            box-shadow:
                0 0.8rem 2rem rgb(0 0 0 / 0.12);

            overflow: hidden;
        }

        /* =========================================================
           ID CARD AREA
           
           The two cards are centered horizontally.
           
           FRONT       BACK
           ┌───────┐   ┌───────┐
           │       │   │       │
           │PORTRAIT   │PORTRAIT
           │       │   │       │
           └───────┘   └───────┘
           ========================================================= */

        .trainee-id-cards {
            position: absolute;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 0.35in;

            padding: 0.25in;
        }

        /* =========================================================
           PORTRAIT ID CARD
           
           Standard portrait orientation:
           
           Width  = 2.125in
           Height = 3.375in
           
           Ratio = 54mm x 85.6mm
           ========================================================= */

        .trainee-id-card {
            position: relative;

            flex: 0 0 2.125in;

            width: 2.125in;
            height: 3.375in;

            min-width: 2.125in;
            min-height: 3.375in;

            overflow: hidden;

            display: flex;
            flex-direction: column;

            background: #ffffff;

            border: 1px solid var(--id-border);
            border-radius: 0.12in;

            box-shadow:
                0 0.12in 0.25in rgb(19 55 36 / 0.15);
        }

        /* =========================================================
           FRONT CARD
           ========================================================= */

        .trainee-id-card-front {
            color: var(--id-dark);
        }

        /* =========================================================
           FRONT HEADER
           ========================================================= */

        .trainee-id-header {
            flex: 0 0 auto;

            display: flex;
            align-items: center;

            gap: 0.08in;

            min-height: 0.72in;

            padding: 0.08in 0.12in;

            color: #ffffff;
            background: var(--id-green);

            border-bottom:
                0.035in solid var(--id-gold);
        }

        .trainee-id-header img {
            flex: 0 0 auto;

            width: 0.48in;
            height: 0.48in;

            object-fit: contain;
        }

        .trainee-id-header-text {
            min-width: 0;
            flex: 1;
        }

        .trainee-id-header h2 {
            margin: 0;

            font-size: 0.105in;
            line-height: 1.08;

            letter-spacing: 0.015in;

            font-weight: 800;
        }

        .trainee-id-kicker {
            margin: 0.025in 0 0;

            color: var(--id-gold);

            font-size: 0.065in;
            line-height: 1.1;

            font-weight: 700;
            letter-spacing: 0.008in;
        }

        /* =========================================================
           FRONT BODY
           ========================================================= */

      .trainee-id-front-body {
    flex: 1 1 auto;

    display: flex;
    flex-direction: column;
    align-items: center;

    min-height: 0;

    padding: 0.08in 0.12in 0.04in;

    overflow: visible;
}

        /* =========================================================
           CARD TITLE
           ========================================================= */

        .trainee-id-front-title {
            flex: 0 0 auto;

            margin-bottom: 0.06in;

            padding: 0.035in 0.14in;

            color: #ffffff;
            background: var(--id-green-light);

            border-radius: 999px;

            font-size: 0.075in;
            line-height: 1;

            font-weight: 800;
            letter-spacing: 0.012in;
        }

  /* =========================================================
   TRAINEE PHOTO
   ========================================================= */

.trainee-id-photo-wrap {
    flex: 0 0 auto;

       width: 0.82in;
    height: 0.98in;

    margin: 0 0 0.06in;

    overflow: hidden;

    border: 0.018in solid var(--id-green);

    background: #e1ebe3;

    border-radius: 0.035in;
}

.trainee-id-photo {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
}


/* =========================================================
   TRAINEE DETAILS
   ========================================================= */

.trainee-id-details {
    flex: 0 0 auto;

    display: block;

    width: 100%;

    margin: 0;

    padding: 0;

    text-align: center;

    overflow: visible;
}


/* =========================================================
   TRAINEE NAME
   ========================================================= */

.trainee-id-details h3 {
    display: block;

    width: 100%;

    margin: 0;

    padding: 0 0.02in 0.025in;

    color: var(--id-dark);

    border-bottom:
        0.012in solid var(--id-green);

    font-size: 0.105in;
    line-height: 1.15;

    font-weight: 800;

    text-transform: uppercase;

    overflow: visible;

    text-overflow: clip;

    white-space: normal;

    word-break: normal;
}


/* =========================================================
   TRAINEE ID NUMBER
   ========================================================= */

.trainee-id-number {
    display: inline-block;

    margin-top: 0.035in;

    padding: 0.025in 0.12in;

    color: var(--id-dark);

    background: #ffffff;

    border:
        0.008in solid #9aa69d;

    border-radius: 0.025in;

    font-size: 0.07in;
    line-height: 1.2;

    font-weight: 800;

    letter-spacing: 0.008in;

    white-space: nowrap;
}


/* =========================================================
   INFORMATION TABLE
   ========================================================= */

.trainee-id-details dl {
    display: block;

    width: 100%;

    margin: 0.06in 0 0;

    padding: 0;

    text-align: left;
}


/* =========================================================
   INFORMATION ROW
   ========================================================= */

.trainee-id-details dl div {
    display: grid;

    grid-template-columns: 0.55in minmax(0, 1fr);

    width: 100%;

    margin: 0 0 0.028in;

    padding: 0;

    align-items: start;

    column-gap: 0.025in;
}


/* =========================================================
   LABEL
   ========================================================= */

.trainee-id-details dt {
    display: block;

    margin: 0;
    padding: 0;

    color: var(--id-green);

    font-size: 0.055in;
    line-height: 1.2;

    font-weight: 800;

    letter-spacing: 0.002in;

    white-space: nowrap;
}


/* =========================================================
   VALUE
   ========================================================= */

.trainee-id-details dd {
    display: block;

    min-width: 0;

    margin: 0;
    padding: 0;

    color: var(--id-dark);

    font-size: 0.055in;
    line-height: 1.2;

    font-weight: 600;

    white-space: normal;

    overflow: visible;

    text-overflow: clip;

    word-break: break-word;
}


/* =========================================================
   SIGNATURES
   ========================================================= */

.trainee-id-signatures {
    flex: 0 0 auto;

    display: flex;

    width: 100%;

    gap: 0.08in;

    margin-top: auto;

    padding:
        0.025in
        0.12in
        0.055in;
}

.trainee-id-signatures div {
    flex: 1;

    min-width: 0;

    text-align: center;
}

.trainee-id-signatures span {
    display: block;

    width: 100%;

    height: 0.11in;

    margin-bottom: 0.018in;

    border-top:
        0.008in solid #25372c;
}

.trainee-id-signatures small {
    display: block;

    color: var(--id-dark);

    font-size: 0.043in;
    line-height: 1.1;

    font-weight: 700;

    white-space: nowrap;
}

        /* =========================================================
           CARD FOOTER
           ========================================================= */

        .trainee-id-footer {
            flex: 0 0 auto;

            display: flex;

            align-items: center;
            justify-content: center;

            min-height: 0.2in;

            padding: 0.035in 0.06in;

            color: #ffffff;
            background: var(--id-green);

            font-size: 0.05in;
            line-height: 1;

            font-weight: 700;

            letter-spacing: 0.006in;
        }

        .trainee-id-footer span + span::before {
            content: "•";

            margin: 0 0.055in;

            color: var(--id-gold);
        }

        /* =========================================================
           BACK CARD
           ========================================================= */

        .trainee-id-card-back {
            color: var(--id-dark);
        }

        /* =========================================================
           BACK HEADER
           ========================================================= */

        .trainee-id-back-brand {
            flex: 0 0 auto;

            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 0.65in;

            padding: 0.08in;

            color: #ffffff;
            background: var(--id-green);

            border-bottom:
                0.035in solid var(--id-gold);

            text-align: center;
        }

        .trainee-id-back-brand h2 {
            margin: 0;

            font-size: 0.105in;
            line-height: 1.08;

            letter-spacing: 0.015in;

            font-weight: 800;
        }

        /* =========================================================
           CERTIFICATION
           ========================================================= */

        .trainee-id-certification {
            flex: 0 0 auto;

            margin: 0.07in 0.12in 0;

            padding-bottom: 0.055in;

            color: var(--id-dark);

            border-bottom:
                0.008in solid #527766;

            font-size: 0.062in;
            line-height: 1.3;

            text-align: center;
        }

        /* =========================================================
           QR VERIFICATION AREA
           
           QR remains on the BACK of the ID.
           ========================================================= */

        .trainee-id-back-verification {
            flex: 0 0 auto;

            display: flex;
            align-items: center;

            gap: 0.08in;

            margin: 0.06in 0.12in;

            padding-bottom: 0.06in;

            border-bottom:
                0.008in solid #527766;
        }

        .trainee-id-qr {
            flex: 0 0 0.75in;

            width: 0.75in;
            height: 0.75in;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0.035in;

            background: #ffffff;

            border:
                0.008in solid #527766;
        }

        .trainee-id-qr svg,
        .trainee-id-qr img {
            display: block;

            width: 100%;
            height: 100%;

            max-width: 100%;
            max-height: 100%;
        }

        .trainee-id-verify-copy {
            flex: 1;

            color: var(--id-dark);

            font-size: 0.06in;
            line-height: 1.25;

            text-align: center;
        }

        .trainee-id-verify-copy strong {
            display: block;

            margin-bottom: 0.025in;

            color: var(--id-green);

            font-size: 0.07in;
            line-height: 1.1;

            font-weight: 800;

            letter-spacing: 0.005in;
        }

        .trainee-id-verify-copy p {
            margin: 0;
        }

        /* =========================================================
           CONTACT
           ========================================================= */

        .trainee-id-contact-block {
            flex: 0 0 auto;

            margin: 0 0.12in;

            padding:
                0.04in
                0
                0.05in;

            color: var(--id-dark);

            border-bottom:
                0.008in solid #527766;

            font-size: 0.051in;
            line-height: 1.25;

            text-align: center;
        }

        .trainee-id-contact-block strong {
            display: block;

            margin-bottom: 0.025in;

            color: var(--id-green);

            font-size: 0.058in;
            line-height: 1.1;

            font-weight: 800;
        }

        .trainee-id-contact-block p {
            margin: 0.018in 0 0;
        }

        /* =========================================================
           REMINDERS
           ========================================================= */

        .trainee-id-reminders {
            flex: 1;

            min-height: 0;

            margin: 0 0.12in;

            padding: 0.045in 0 0.025in;

            color: var(--id-dark);

            font-size: 0.05in;
            line-height: 1.25;

            overflow: hidden;
        }

        .trainee-id-reminders strong {
            display: block;

            color: var(--id-green);

            font-size: 0.06in;
            line-height: 1.1;

            font-weight: 800;
        }

        .trainee-id-reminders ul {
            margin: 0.025in 0 0;

            padding-left: 0.12in;

            list-style-position: outside;
        }

        .trainee-id-reminders li {
            margin-bottom: 0.018in;

            padding-left: 0.01in;
        }

        /* =========================================================
           SCREEN PREVIEW
           
           Smaller screens can scroll horizontally rather than
           destroying the physical paper proportions.
           ========================================================= */

        @media (max-width: 900px) {
            body {
                overflow-x: auto;
            }

            .trainee-id-toolbar {
                width: calc(100% - 1rem);
            }

            .id-paper {
                width: 11in;
                height: 8.5in;

                margin-left: 1rem;
                margin-right: 1rem;
            }
        }

        /* =========================================================
           PRINT
           
           THIS IS THE IMPORTANT PART.
           
           The physical paper is:
           
           11in x 8.5in
           
           LANDSCAPE.
           
           The cards remain:
           
           2.125in x 3.375in
           
           PORTRAIT.
           ========================================================= */

        @media print {

            @page {
                size: 11in 8.5in;
                margin: 0;
            }

            html,
            body {
                width: 11in;
                height: 8.5in;

                margin: 0 !important;
                padding: 0 !important;

                background: #ffffff !important;
            }

            body {
                overflow: hidden;
            }

            .trainee-id-toolbar {
                display: none !important;
            }

            .id-paper {
                width: 11in !important;
                height: 8.5in !important;

                margin: 0 !important;

                border: 0 !important;

                box-shadow: none !important;

                overflow: hidden !important;

                page-break-after: avoid;
                break-after: avoid;
            }

            .trainee-id-cards {
                position: absolute;

                inset: 0;

                display: flex !important;

                flex-direction: row !important;

                align-items: center !important;
                justify-content: center !important;

                gap: 0.35in !important;

                padding: 0 !important;
            }

            .trainee-id-card {
                flex: 0 0 2.125in !important;

                width: 2.125in !important;
                min-width: 2.125in !important;

                height: 3.375in !important;
                min-height: 3.375in !important;

                max-width: 2.125in !important;
                max-height: 3.375in !important;

                border-radius: 0.08in !important;

                box-shadow: none !important;

                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body>

    <main class="trainee-id-modal">

        <!-- =====================================================
             TOOLBAR
             ===================================================== -->

        <nav class="trainee-id-toolbar" aria-label="ID card actions">

            <p>
                Short bond paper — Landscape |
                ID cards — Portrait
            </p>

            <a
                href="{{ route('screenings.id-card.pdf', ['screening' => $record]) }}"
                target="_blank"
                rel="noopener"
                class="trainee-id-download-button"
            >
                Export ID as PDF
            </a>

        </nav>

        <!-- =====================================================
             SHORT BOND PAPER
             11in x 8.5in LANDSCAPE
             ===================================================== -->

        <section class="id-paper">

            <div class="trainee-id-cards">

                <!-- =================================================
                     FRONT OF ID
                     PORTRAIT
                     2.125in x 3.375in
                     ================================================= -->

                <article
                    class="trainee-id-card trainee-id-card-front"
                    aria-label="Front of trainee ID"
                >

                    <!-- HEADER -->

                    <header class="trainee-id-header">

                        <img
                            src="{{ asset('images/btvtc-logo.png') }}"
                            alt="BTVTC logo"
                        >

                        <div class="trainee-id-header-text">

                            <h2>
                                BAYBAY CITY<br>
                                TECHNICAL-VOCATIONAL<br>
                                TRAINING CENTER
                            </h2>

                            <p class="trainee-id-kicker">
                                BAYBAY CITY, LEYTE, PHILIPPINES
                            </p>

                        </div>

                    </header>


                    <!-- MAIN CONTENT -->

                    <div class="trainee-id-front-body">

                        <div class="trainee-id-front-title">
                            TRAINEE ID CARD
                        </div>


                        <!-- TRAINEE PHOTO -->

                        <figure class="trainee-id-photo-wrap">

                            <img
                                class="trainee-id-photo"
                                src="{{ $photoUrl }}"
                                alt="Photo of {{ $fullName }}"
                            >

                        </figure>


                        <!-- TRAINEE INFORMATION -->

                        <div class="trainee-id-details">

                            <h3>
                                {{ $fullName }}
                            </h3>

                            <div class="trainee-id-number">
                                {{ $traineeId }}
                            </div>


                            <dl>


                                <div>
                                    <dt>BATCH</dt>
                                    <dd>
                                        {{ $batchName }}
                                    </dd>
                                </div>


                                <div>
                                    <dt>TRAINER</dt>
                                    <dd>
                                        {{ $trainerName }}
                                    </dd>
                                </div>

                                <div>
                                    <dt>EXPIRY</dt>
                                    <dd>
                                        {{ $expiryDate }}
                                    </dd>
                                </div>

                            </dl>

                        </div>

                    </div>


                    <!-- SIGNATURES -->

                    <div class="trainee-id-signatures">

                        <div>
                            <span></span>
                            <small>
                                TRAINEE SIGNATURE
                            </small>
                        </div>

                        <div>
                            <span></span>
                            <small>
                                TRAINING CENTER MANAGER
                            </small>
                        </div>

                    </div>


                    <!-- FOOTER -->

                    <footer class="trainee-id-footer">

                        <span>
                            NON-TRANSFERABLE
                        </span>

                        <span>
                            PROPERTY OF BTVTC
                        </span>

                    </footer>

                </article>


                <!-- =================================================
                     BACK OF ID
                     PORTRAIT
                     2.125in x 3.375in
                     ================================================= -->

                <article
                    class="trainee-id-card trainee-id-card-back"
                    aria-label="Back of trainee ID"
                >

                    <!-- BACK HEADER -->

                    <header class="trainee-id-back-brand">

                        <div>

                            <h2>
                                BAYBAY CITY<br>
                                TECHNICAL-VOCATIONAL<br>
                                TRAINING CENTER
                            </h2>

                            <p class="trainee-id-kicker">
                                BAYBAY CITY, LEYTE, PHILIPPINES
                            </p>

                        </div>

                    </header>


                    <!-- CERTIFICATION -->

                    <p class="trainee-id-certification">

                        This is to certify that the bearer whose
                        name appears on the front is an official
                        trainee of Baybay City
                        Technical-Vocational Training Center
                        (BTVTC).

                    </p>


                    <!-- QR VERIFICATION -->

                    <section
                        class="trainee-id-back-verification"
                        aria-label="QR verification"
                    >

                        <div class="trainee-id-qr">

                            {!! $qrCode !!}

                        </div>


                        <div class="trainee-id-verify-copy">

                            <strong>
                                VERIFY THIS ID
                            </strong>

                            <p>
                                Scan the QR code
                                to verify trainee
                                information.
                            </p>

                        </div>

                    </section>


                    <!-- EMERGENCY CONTACT -->

                    <section class="trainee-id-contact-block">

                        <strong>
                            IN CASE OF EMERGENCY,
                            PLEASE CONTACT:
                        </strong>

                        <p>
                            Baybay City
                            Technical-Vocational
                            Training Center
                        </p>

                        <p>
                            Brgy. Cogon, Baybay City,
                            Leyte, Philippines
                        </p>

                        <p>
                            btvassessmentcenter@gmail.com
                        </p>

                        <p>
                            baybaytechvost@gmail.com
                        </p>

                        <p>
                            (0938) 606 5813 |
                            (0997) 254 8867
                        </p>

                    </section>


                    <!-- IMPORTANT REMINDERS -->

                    <section class="trainee-id-reminders">

                        <strong>
                            IMPORTANT REMINDERS
                        </strong>

                        <ul>

                            <li>
                                This ID card is non-transferable
                                and is valid only for the current
                                training program.
                            </li>

                            <li>
                                Return this card to BTVTC upon
                                completion, withdrawal, or
                                termination.
                            </li>

                            <li>
                                Report loss of this ID card
                                immediately to the Registrar's
                                Office.
                            </li>

                            <li>
                                Misuse of this ID card is subject
                                to disciplinary action.
                            </li>

                        </ul>

                    </section>


                    <!-- FOOTER -->

                    <footer class="trainee-id-footer">

                        <span>
                            NON-TRANSFERABLE
                        </span>

                        <span>
                            PROPERTY OF BTVTC
                        </span>

                    </footer>

                </article>

            </div>

        </section>

    </main>

</body>

</html>