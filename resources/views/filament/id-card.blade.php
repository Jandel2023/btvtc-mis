@php
    $photoUrl = $record->picture
        ? \Illuminate\Support\Facades\Storage::url($record->picture)
        : asset('images/btvtc-logo.png');
    $fullName = $record->full_name ?: 'Unnamed Trainee';
    $batchName = $record->batch?->batch_name ?? 'Not assigned';
    $qualification = $record->batch?->qualification?->qualification_code ?? 'Not assigned';
    $issuedDate = $record->date_screened
        ? \Illuminate\Support\Carbon::parse($record->date_screened)->format('d M Y')
        : 'Not available';
    $expiryDate = $record->batch?->end_date
        ? \Illuminate\Support\Carbon::parse($record->batch->end_date)->format('d M Y')
        : 'Not specified';
    $trainerName = $record->screened_by ?: 'Training Center';
@endphp

<div class="trainee-id-modal">
    <div class="trainee-id-toolbar">
        <p>Print both sides of the trainee ID card.</p>
        <button type="button" onclick="window.print()">Print ID Card</button>
    </div>

    <div class="trainee-id-cards">
        <article class="trainee-id-card trainee-id-card-front">
            <header class="trainee-id-header">
                <img src="{{ asset('images/btvtc-logo.png') }}" alt="BTVTC logo">
                <div>
                    <h2>BAYBAY CITY<br>TECHNICAL-VOCATIONAL<br>TRAINING CENTER</h2>
                    <p class="trainee-id-kicker">BAYBAY CITY, LEYTE, PHILIPPINES</p>
                </div>
            </header>

            <div class="trainee-id-front-body">
                <div class="trainee-id-front-title">TRAINEE ID CARD</div>
                <div class="trainee-id-photo-wrap">
                    <img class="trainee-id-photo" src="{{ $photoUrl }}" alt="Photo of {{ $fullName }}">
                    <span>PHOTO</span>
                </div>
                <div class="trainee-id-details">
                    <h3>{{ $fullName }}</h3>
                    <div class="trainee-id-number-label">TRAINEE ID NO.</div>
                    <div class="trainee-id-number">TR-{{ str_pad((string) $record->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <dl>
                        <div>
                            <dt>PROGRAM</dt>
                            <dd>{{ $qualification }} - {{ $batchName }}</dd>
                        </div>
                        <div>
                            <dt>DATE ISSUED</dt>
                            <dd>{{ $issuedDate }}</dd>
                        </div>
                        <div>
                            <dt>TRAINER</dt>
                            <dd>{{ $trainerName }}</dd>
                        </div>
                        <div>
                            <dt>EXPIRY DATE</dt>
                            <dd>{{ $expiryDate }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="trainee-id-signatures">
                <div><span></span><small>TRAINEE SIGNATURE</small></div>
                <div><span></span><small>TRAINING CENTER MANAGER</small></div>
            </div>

            <footer class="trainee-id-footer">
                <span>NON-TRANSFERABLE</span>
                <span>PROPERTY OF BTVTC</span>
            </footer>
        </article>

        <article class="trainee-id-card trainee-id-card-back">
            <div class="trainee-id-back-brand">
                <div>
                    <h2>BAYBAY CITY<br>TECHNICAL-VOCATIONAL<br>TRAINING CENTER</h2>
                    <p class="trainee-id-kicker">BAYBAY CITY, LEYTE, PHILIPPINES</p>
                </div>
            </div>

            <p class="trainee-id-certification">This is to certify that the bearer whose name appears<br>on the front is an official trainee of<br>Baybay City Technical-Vocational Training Center (BTVTC).</p>

            <div class="trainee-id-back-content">
                <div class="trainee-id-qr">
                    {!! $qrCode !!}
                </div>
                <div class="trainee-id-verify-copy">
                    <strong>VERIFY THIS ID</strong>
                    <p>Scan the QR code<br>to verify trainee<br>information and<br>attendance records.</p>
                </div>
                <div class="trainee-id-or">OR</div>
                <div class="trainee-id-barcode">BARCODE<br>HERE</div>
            </div>

            <div class="trainee-id-contact-block">
                <strong>IN CASE OF EMERGENCY, PLEASE CONTACT:</strong>
                <p>Baybay City Technical-Vocational Training Center<br>Brgy. Hilapnitan, Baybay City, Leyte, Philippines</p>
                <p>(053) 563-4050 &nbsp; | &nbsp; btvtc.baybaycity@tesda.gov.ph</p>
            </div>

            <div class="trainee-id-reminders">
                <strong>IMPORTANT REMINDERS</strong>
                <ul>
                    <li>This ID card is non-transferable and is valid only for the current training program.</li>
                    <li>Return this card to BTVTC upon completion, withdrawal, or termination.</li>
                    <li>Report loss of this ID card immediately to the Registrar's Office.</li>
                    <li>Misuse of this ID card is subject to disciplinary action.</li>
                </ul>
            </div>

            <footer class="trainee-id-footer">
                <span>NON-TRANSFERABLE</span>
                <span>PROPERTY OF BTVTC</span>
            </footer>
        </article>
    </div>
</div>

<style>
    .trainee-id-modal {
        color: #17231d;
    }

    .trainee-id-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .trainee-id-toolbar p {
        margin: 0;
        color: #617067;
    }

    .trainee-id-toolbar button {
        border: 0;
        border-radius: 0.5rem;
        background: #087443;
        color: #fff;
        cursor: pointer;
        font-weight: 700;
        padding: 0.6rem 0.9rem;
    }

    .trainee-id-cards {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .trainee-id-card {
        aspect-ratio: 1.586 / 1;
        overflow: hidden;
        position: relative;
        border: 1px solid #c8d5cb;
        border-radius: 0.7rem;
        background: #f8fbf8;
        box-shadow: 0 0.5rem 1.25rem rgb(25 58 37 / 0.12);
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .trainee-id-card-front {
        background: linear-gradient(135deg, #f4faf5 0%, #fff 58%, #e4f3e8 100%);
    }

    .trainee-id-card-back {
        background: linear-gradient(135deg, #0a633c 0%, #073d2a 100%);
        color: #fff;
    }

    .trainee-id-header,
    .trainee-id-back-brand {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.7rem 0.8rem 0.55rem;
        border-bottom: 1px solid rgb(17 104 63 / 0.18);
    }

    .trainee-id-header img,
    .trainee-id-back-brand img {
        width: 2.5rem;
        height: 2.5rem;
        object-fit: contain;
        flex: 0 0 auto;
    }

    .trainee-id-header h2,
    .trainee-id-back-brand h2 {
        margin: 0;
        font-size: clamp(0.65rem, 1.7vw, 0.95rem);
        line-height: 1.1;
        font-weight: 800;
    }

    .trainee-id-kicker,
    .trainee-id-title {
        margin: 0;
        font-size: clamp(0.45rem, 1.2vw, 0.65rem);
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .trainee-id-kicker {
        color: #e6a900;
    }

    .trainee-id-title {
        color: #557060;
        margin-top: 0.15rem;
    }

    .trainee-id-front-body {
        align-items: center;
        display: flex;
        flex: 1;
        gap: 0.7rem;
        padding: 0.7rem 0.8rem;
    }

    .trainee-id-photo {
        width: 27%;
        aspect-ratio: 0.82;
        border: 0.2rem solid #fff;
        border-radius: 0.35rem;
        box-shadow: 0 0.2rem 0.5rem rgb(25 58 37 / 0.2);
        object-fit: cover;
        background: #d9e7dc;
    }

    .trainee-id-details {
        min-width: 0;
        flex: 1;
    }

    .trainee-id-details h3 {
        color: #075f39;
        font-size: clamp(0.75rem, 2.2vw, 1.25rem);
        line-height: 1.05;
        margin: 0 0 0.45rem;
        overflow-wrap: anywhere;
    }

    .trainee-id-details dl {
        display: grid;
        gap: 0.22rem;
        margin: 0;
    }

    .trainee-id-details dl div {
        display: grid;
        grid-template-columns: 32% 68%;
        gap: 0.25rem;
    }

    .trainee-id-details dt,
    .trainee-id-details dd {
        font-size: clamp(0.42rem, 1.2vw, 0.65rem);
        line-height: 1.15;
        margin: 0;
    }

    .trainee-id-details dt {
        color: #6c7d71;
        font-weight: 700;
        text-transform: uppercase;
    }

    .trainee-id-details dd {
        color: #233a2b;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .trainee-id-footer {
        align-items: center;
        display: flex;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.45rem 0.8rem;
        background: rgb(7 95 57 / 0.08);
        color: #577061;
        font-size: clamp(0.4rem, 1vw, 0.58rem);
        font-weight: 700;
    }

    .trainee-id-back .trainee-id-footer {
        background: rgb(0 0 0 / 0.18);
        color: #d8eee0;
    }

    .trainee-id-back-content {
        align-items: center;
        display: flex;
        flex: 1;
        gap: 0.7rem;
        padding: 0.8rem;
    }

    .trainee-id-qr {
        align-items: center;
        background: #fff;
        border-radius: 0.35rem;
        color: #243b2c;
        display: flex;
        flex: 0 0 34%;
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.35rem;
        text-align: center;
    }

    .trainee-id-qr svg {
        display: block;
        height: auto;
        width: 100%;
    }

    .trainee-id-qr span {
        font-size: clamp(0.4rem, 1vw, 0.55rem);
        font-weight: 700;
        line-height: 1.1;
    }

    .trainee-id-notice {
        flex: 1;
        font-size: clamp(0.48rem, 1.3vw, 0.7rem);
        line-height: 1.35;
    }

    .trainee-id-notice p {
        margin: 0 0 0.5rem;
    }

    .trainee-id-contact {
        color: #f5cf58;
        font-weight: 700;
    }

    @media (max-width: 700px) {
        .trainee-id-cards {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        body * {
            visibility: hidden !important;
        }

        .trainee-id-modal,
        .trainee-id-modal * {
            visibility: visible !important;
        }

        .trainee-id-modal {
            position: absolute;
            inset: 0;
            padding: 0;
        }

        .trainee-id-toolbar {
            display: none;
        }

        .trainee-id-cards {
            display: grid;
            gap: 0.25in;
            grid-template-columns: 1fr;
        }

        .trainee-id-card {
            width: 3.375in;
            height: 2.125in;
            break-inside: avoid;
            box-shadow: none;
        }
    }

    /* Reference template layout: landscape card, green header, gold rules. */
    .trainee-id-cards {
        gap: 1.25rem;
    }

    .trainee-id-card {
        aspect-ratio: 85.6 / 54;
        border: 0;
        border-radius: 0.8rem;
        box-shadow: 0 0.55rem 1.2rem rgb(19 55 36 / 0.16);
    }

    .trainee-id-card-front {
        background: #fff;
    }

    .trainee-id-card-back {
        background: #fff;
        color: #17231d;
    }

    .trainee-id-header,
    .trainee-id-back-brand {
        align-items: center;
        background: #0e3d2f;
        border-bottom: 0.22rem solid #f2c94c;
        color: #fff;
        gap: 0.75rem;
        min-height: 27%;
        padding: 0.55rem 1rem 0.45rem;
    }

    .trainee-id-header img {
        width: 3.5rem;
        height: 3.5rem;
    }

    .trainee-id-header h2,
    .trainee-id-back-brand h2 {
        font-size: clamp(0.7rem, 2vw, 1.1rem);
        letter-spacing: 0.025em;
        line-height: 1.02;
    }

    .trainee-id-kicker {
        color: #f2c94c;
        font-size: clamp(0.46rem, 1.2vw, 0.7rem);
        margin-top: 0.25rem;
    }

    .trainee-id-front-body {
        align-items: center;
        flex-direction: column;
        gap: 0.35rem;
        padding: 0.45rem 1rem 0.15rem;
    }

    .trainee-id-front-title {
        background: #146c43;
        border-radius: 999px;
        color: #fff;
        font-size: clamp(0.55rem, 1.5vw, 0.8rem);
        font-weight: 800;
        letter-spacing: 0.08em;
        line-height: 1;
        padding: 0.35rem 1.7rem;
    }

    .trainee-id-photo-wrap {
        position: relative;
        width: 27%;
        min-width: 4.2rem;
        max-width: 7rem;
    }

    .trainee-id-photo {
        display: block;
        width: 100%;
        aspect-ratio: 35 / 45;
        border: 0.12rem solid #0e3d2f;
        border-radius: 0.25rem 0.25rem 0 0;
        box-shadow: none;
    }

    .trainee-id-photo-wrap span {
        display: block;
        background: #0e3d2f;
        border-radius: 0 0 0.25rem 0.25rem;
        color: #fff;
        font-size: clamp(0.45rem, 1vw, 0.6rem);
        font-weight: 800;
        letter-spacing: 0.08em;
        padding: 0.18rem;
        text-align: center;
    }

    .trainee-id-details {
        width: 100%;
        text-align: center;
    }

    .trainee-id-details h3 {
        border-bottom: 0.08rem solid #0e3d2f;
        color: #111;
        display: inline-block;
        font-size: clamp(0.85rem, 2.4vw, 1.35rem);
        letter-spacing: 0.04em;
        margin: 0;
        max-width: 90%;
        padding: 0 0.5rem 0.15rem;
        text-transform: uppercase;
    }

    .trainee-id-number-label {
        color: #0e3d2f;
        font-size: clamp(0.42rem, 1vw, 0.58rem);
        font-weight: 800;
        margin-top: 0.12rem;
    }

    .trainee-id-number {
        border: 1px solid #9aa69d;
        border-radius: 0.25rem;
        display: inline-block;
        font-size: clamp(0.55rem, 1.3vw, 0.75rem);
        font-weight: 800;
        margin-top: 0.08rem;
        padding: 0.12rem 1rem;
    }

    .trainee-id-details dl {
        gap: 0.16rem;
        margin-top: 0.35rem;
        text-align: left;
    }

    .trainee-id-details dl div {
        grid-template-columns: 25% 75%;
        padding-left: 0.5rem;
    }

    .trainee-id-details dt,
    .trainee-id-details dd {
        font-size: clamp(0.4rem, 1vw, 0.58rem);
    }

    .trainee-id-details dt {
        color: #0e3d2f;
    }

    .trainee-id-details dd {
        color: #17231d;
    }

    .trainee-id-signatures {
        display: flex;
        gap: 1rem;
        justify-content: space-around;
        padding: 0 1rem 0.3rem;
    }

    .trainee-id-signatures div {
        flex: 1;
        text-align: center;
    }

    .trainee-id-signatures span {
        border-top: 1px solid #25372c;
        display: block;
        margin-bottom: 0.12rem;
    }

    .trainee-id-signatures small {
        color: #17231d;
        font-size: clamp(0.35rem, 0.8vw, 0.5rem);
        font-weight: 700;
    }

    .trainee-id-footer {
        background: #0e3d2f;
        color: #fff;
        font-size: clamp(0.42rem, 1vw, 0.58rem);
        justify-content: center;
        letter-spacing: 0.06em;
        margin-top: auto;
        padding: 0.38rem 0.8rem;
    }

    .trainee-id-footer span + span::before {
        color: #f2c94c;
        content: '•';
        margin: 0 0.45rem;
    }

    .trainee-id-back-brand {
        justify-content: center;
        text-align: center;
    }

    .trainee-id-certification {
        border-bottom: 1px solid #527766;
        color: #17231d;
        font-size: clamp(0.48rem, 1.1vw, 0.65rem);
        line-height: 1.35;
        margin: 0.5rem 1rem 0;
        padding-bottom: 0.5rem;
        text-align: center;
    }

    .trainee-id-back-content {
        border-bottom: 1px solid #527766;
        gap: 0.55rem;
        margin: 0 1rem;
        padding: 0.55rem 0;
    }

    .trainee-id-qr {
        border: 1px solid #527766;
        border-radius: 0;
        flex-basis: 21%;
        padding: 0.25rem;
    }

    .trainee-id-verify-copy {
        color: #17231d;
        flex: 1;
        font-size: clamp(0.45rem, 1.1vw, 0.65rem);
        line-height: 1.25;
    }

    .trainee-id-verify-copy strong,
    .trainee-id-contact-block strong,
    .trainee-id-reminders strong {
        color: #0e3d2f;
        font-size: clamp(0.48rem, 1.2vw, 0.68rem);
        letter-spacing: 0.03em;
    }

    .trainee-id-verify-copy p {
        margin: 0.2rem 0 0;
    }

    .trainee-id-or {
        align-items: center;
        background: #146c43;
        border-radius: 50%;
        color: #fff;
        display: flex;
        flex: 0 0 1.6rem;
        font-size: 0.55rem;
        font-weight: 800;
        height: 1.6rem;
        justify-content: center;
    }

    .trainee-id-barcode {
        align-items: center;
        background: #f5f5f5;
        color: #333;
        display: flex;
        flex: 0 0 24%;
        font-size: clamp(0.48rem, 1.1vw, 0.65rem);
        font-weight: 700;
        height: 3.1rem;
        justify-content: center;
        line-height: 1.2;
        text-align: center;
    }

    .trainee-id-contact-block,
    .trainee-id-reminders {
        color: #17231d;
        font-size: clamp(0.42rem, 1vw, 0.58rem);
        line-height: 1.3;
        margin: 0 1rem;
    }

    .trainee-id-contact-block {
        border-bottom: 1px solid #527766;
        padding: 0.4rem 0;
    }

    .trainee-id-contact-block p {
        margin: 0.2rem 0 0;
    }

    .trainee-id-reminders {
        flex: 1;
        padding: 0.35rem 0 0.2rem;
    }

    .trainee-id-reminders ul {
        margin: 0.2rem 0 0;
        padding-left: 1rem;
    }

    .trainee-id-reminders li {
        margin-bottom: 0.08rem;
    }

    @media print {
        .trainee-id-card {
            width: 3.375in;
            height: 2.125in;
        }
    }

    /* Portrait standard ID proportions: 54mm x 85.6mm. */
    .trainee-id-card {
        aspect-ratio: 54 / 85.6;
    }

    .trainee-id-header,
    .trainee-id-back-brand {
        min-height: 18%;
        padding: 0.45rem 0.65rem 0.35rem;
    }

    .trainee-id-header img {
        width: 2.5rem;
        height: 2.5rem;
    }

    .trainee-id-header h2,
    .trainee-id-back-brand h2 {
        font-size: clamp(0.58rem, 2.8vw, 0.9rem);
    }

    .trainee-id-front-body {
        gap: 0.22rem;
        padding: 0.35rem 0.65rem 0.1rem;
    }

    .trainee-id-front-title {
        font-size: clamp(0.46rem, 2vw, 0.68rem);
        padding: 0.28rem 1rem;
    }

    .trainee-id-photo-wrap {
        width: 38%;
        max-width: 5rem;
    }

    .trainee-id-details h3 {
        font-size: clamp(0.68rem, 3.1vw, 1rem);
        max-width: 98%;
    }

    .trainee-id-details dl {
        gap: 0.1rem;
        margin-top: 0.2rem;
    }

    .trainee-id-details dl div {
        grid-template-columns: 32% 68%;
        padding-left: 0;
    }

    .trainee-id-details dt,
    .trainee-id-details dd {
        font-size: clamp(0.35rem, 1.8vw, 0.52rem);
    }

    .trainee-id-signatures {
        gap: 0.4rem;
        padding: 0 0.65rem 0.18rem;
    }

    .trainee-id-signatures small {
        font-size: clamp(0.29rem, 1.3vw, 0.42rem);
    }

    .trainee-id-footer {
        font-size: clamp(0.34rem, 1.6vw, 0.48rem);
        padding: 0.28rem 0.4rem;
    }

    .trainee-id-certification {
        font-size: clamp(0.38rem, 1.8vw, 0.52rem);
        margin: 0.35rem 0.65rem 0;
        padding-bottom: 0.35rem;
    }

    .trainee-id-back-content {
        gap: 0.3rem;
        margin: 0 0.65rem;
        padding: 0.35rem 0;
    }

    .trainee-id-qr {
        flex-basis: 29%;
    }

    .trainee-id-verify-copy,
    .trainee-id-barcode {
        font-size: clamp(0.36rem, 1.7vw, 0.5rem);
    }

    .trainee-id-or {
        flex-basis: 1.2rem;
        font-size: 0.4rem;
        height: 1.2rem;
    }

    .trainee-id-barcode {
        height: 2.3rem;
    }

    .trainee-id-contact-block,
    .trainee-id-reminders {
        font-size: clamp(0.32rem, 1.5vw, 0.46rem);
        line-height: 1.2;
        margin: 0 0.65rem;
    }

    .trainee-id-contact-block {
        padding: 0.28rem 0;
    }

    .trainee-id-contact-block p {
        margin-top: 0.12rem;
    }

    .trainee-id-reminders {
        padding: 0.22rem 0 0.12rem;
    }

    .trainee-id-reminders ul {
        margin-top: 0.12rem;
        padding-left: 0.7rem;
    }

    @media print {
        .trainee-id-card {
            width: 2.125in;
            height: 3.375in;
        }
    }

    /* Modal presentation layer. */
    .trainee-id-modal,
    .trainee-id-modal * {
        box-sizing: border-box;
    }

    .trainee-id-modal {
        --id-ink: #16372a;
        --id-muted: #607269;
        --id-green: #0e3d2f;
        --id-green-bright: #146c43;
        --id-gold: #f2c94c;
        max-width: 100%;
        padding: 0.25rem;
    }

    .trainee-id-toolbar {
        align-items: center;
        background: #f4f8f5;
        border: 1px solid #dce8df;
        border-radius: 0.75rem;
        box-shadow: 0 0.25rem 0.8rem rgb(19 55 36 / 0.06);
        margin-bottom: 1.25rem;
        padding: 0.65rem 0.75rem 0.65rem 1rem;
    }

    .trainee-id-toolbar p {
        color: var(--id-muted);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .trainee-id-toolbar button {
        background: var(--id-green-bright);
        border-radius: 0.5rem;
        box-shadow: 0 0.2rem 0.45rem rgb(20 108 67 / 0.2);
        font-size: 0.75rem;
        letter-spacing: 0.01em;
        padding: 0.55rem 0.8rem;
        transition: background-color 150ms ease, transform 150ms ease;
    }

    .trainee-id-toolbar button:hover {
        background: var(--id-green);
        transform: translateY(-1px);
    }

    .trainee-id-cards {
        align-items: start;
        background: linear-gradient(135deg, #f7faf8 0%, #eef5f0 100%);
        border: 1px solid #e0ebe3;
        border-radius: 1rem;
        grid-template-columns: repeat(2, minmax(0, 290px));
        justify-content: center;
        padding: 1.5rem;
    }

    .trainee-id-card {
        width: 100%;
        max-width: 290px;
        border: 1px solid #cfddd2;
        border-radius: 0.85rem;
        box-shadow: 0 0.7rem 1.4rem rgb(19 55 36 / 0.15);
    }

    .trainee-id-header,
    .trainee-id-back-brand {
        border-radius: 0.85rem 0.85rem 0 0;
    }

    .trainee-id-card-back {
        color: var(--id-ink);
    }

    .trainee-id-photo {
        background: #e1ebe3;
    }

    .trainee-id-details h3 {
        color: var(--id-ink);
    }

    .trainee-id-details dt,
    .trainee-id-verify-copy strong,
    .trainee-id-contact-block strong,
    .trainee-id-reminders strong {
        color: var(--id-green);
    }

    @media (max-width: 760px) {
        .trainee-id-cards {
            grid-template-columns: minmax(0, 290px);
            padding: 1rem;
        }
    }

    @media print {
        .trainee-id-modal {
            padding: 0;
        }

        .trainee-id-cards {
            background: transparent;
            border: 0;
            padding: 0;
        }
    }
</style>
