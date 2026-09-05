<?php

use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

it('renders qr code markup as svg instead of a data uri', function () {
    $options = new QROptions;
    $options->outputType = QROutputInterface::MARKUP_SVG;
    $options->outputBase64 = false;
    $options->margin = 1;
    $options->scale = 8;

    $svg = (new QRCode($options))->render('https://example.com/trainee/1');

    expect($svg)
        ->toContain('<svg')
        ->not->toContain('data:image/svg+xml');
});
