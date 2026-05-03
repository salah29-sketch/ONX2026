<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Bon de réservation - ONX</title>
    <style>
        @page {
            margin: 24px 18px 16px;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            color:#111;
            font-size:10px;
            line-height:1.35;
            margin:0;
            padding:0;
            background:#fff;
        }

        .page{
            width:86%;
            margin:14px auto 0;
        }

        .header{
            border:1.6px solid #1f1f1f;
            padding:12px 14px 11px;
            margin-bottom:14px;
            background:#fff;
        }

        .header-table{
            width:100%;
            border-collapse:collapse;
        }

        .header-table td{
            border:none;
            padding:0;
            vertical-align:middle;
        }

        .logo-cell{
            width:68px;
        }

        .logo-wrap{
            width:58px;
            height:58px;
        }

        .logo-wrap img{
            width:58px;
            height:auto;
            display:block;
        }

        .brand-cell{
            padding-left:10px;
        }

        .brand{
            margin:0;
            font-size:18px;
            font-weight:bold;
            color:#111;
            letter-spacing:.5px;
        }

        .brand-sub{
            margin:3px 0 0;
            font-size:8px;
            color:#595959;
        }

        .doc-cell{
            text-align:right;
        }

        .doc-title{
            margin:0;
            font-size:18px;
            font-weight:bold;
            color:#111;
        }

        .doc-ref{
            margin-top:4px;
            font-size:8px;
            color:#6a6a6a;
        }

        .notice{
            margin-top:10px;
            padding:7px 9px;
            border-left:4px solid #d65f13;
            background:#fff5ee;
            color:#222;
            font-size:8px;
        }

        .section{
            margin-bottom:12px;
        }

        .section-title{
            margin:0 0 7px;
            font-size:12px;
            font-weight:bold;
            color:#111;
            padding-bottom:4px;
            border-bottom:1px solid #d65f13;
        }

        .two-col{
            width:100%;
            border-collapse:separate;
            border-spacing:8px 0;
        }

        .two-col td{
            width:50%;
            border:none;
            padding:0;
            vertical-align:top;
        }

        .box{
            padding:2px 0 0;
            min-height:126px;
            background:#fff;
        }

        .box-title{
            margin:0 0 8px;
            font-size:10.5px;
            font-weight:bold;
            color:#111;
            padding-bottom:4px;
            border-bottom:1px solid #ededed;
        }

        .item{
            margin-bottom:7px;
        }

        .item:last-child{
            margin-bottom:0;
        }

        .label{
            display:block;
            font-size:7.4px;
            color:#6a6a6a;
            margin-bottom:2px;
            text-transform:uppercase;
            letter-spacing:.35px;
        }

        .value{
            display:block;
            font-size:10px;
            font-weight:bold;
            color:#161616;
            word-break:break-word;
        }

        .money-table{
            width:100%;
            border-collapse:separate;
            border-spacing:8px 0;
        }

        .money-table td{
            width:33.33%;
            border:none;
            padding:0;
            vertical-align:top;
        }

        .money-box{
            border:1.3px solid #262626;
            background:#fff;
            text-align:center;
            padding:10px 8px 10px;
            min-height:64px;
        }

        .money-box.total{
            background:#fff6ef;
            border-color:#d65f13;
        }

        .money-label{
            display:block;
            font-size:7.3px;
            color:#5f5f5f;
            margin-bottom:6px;
            text-transform:uppercase;
            letter-spacing:.4px;
        }

        .money-value{
            font-size:12px;
            font-weight:bold;
            color:#111;
            line-height:1.2;
        }

        .money-box.total .money-value{
            color:#c9540d;
        }

        .money-sub{
            display:block;
            margin-top:4px;
            font-size:7px;
            color:#7b7b7b;
        }

        .placeholder{
            color:#8a8a8a;
            font-weight:normal;
        }

        .notes-box{
            background:#fff;
            padding:2px 0 0;
            min-height:20px;
        }

        .terms{
            background:#fff;
            padding:2px 0 0;
        }

        .terms ul{
            margin:0;
            padding-left:15px;
        }

        .terms li{
            margin:0 0 4px;
            font-size:8px;
            color:#151515;
        }

        .sign-table{
            width:100%;
            border-collapse:separate;
            border-spacing:8px 0;
            margin-top:10px;
        }

        .sign-table td{
            width:50%;
            border:none;
            padding:0;
            vertical-align:top;
        }

        .sign-box{
            border:1.2px solid #2a2a2a;
            padding:10px;
            min-height:66px;
            background:#fff;
        }

        .sign-title{
            font-size:10px;
            font-weight:bold;
            margin-bottom:22px;
            color:#111;
        }

        .sign-line{
            border-top:1px solid #555;
            padding-top:4px;
            font-size:7.4px;
            color:#666;
        }

        .client-login-box{
            margin-top:14px;
            padding:10px 12px;
            border:1.5px solid #d65f13;
            background:#fff9f5;
            font-size:9px;
            direction:rtl;
            text-align:right;
        }

        .client-login-box .title{
            font-weight:bold;
            color:#c9540d;
            margin-bottom:6px;
            font-size:10px;
        }

        .client-login-box .row{
            margin-bottom:4px;
        }

        .client-login-box .row:last-child{
            margin-bottom:0;
        }

        .footer{
            margin-top:11px;
            padding-top:7px;
            border-top:1px solid #d65f13;
            font-size:7.5px;
            color:#4d4d4d;
        }

        .footer-table{
            width:100%;
            border-collapse:collapse;
        }

        .footer-table td{
            border:none;
            padding:0;
            vertical-align:top;
        }

        .text-right{
            text-align:right;
        }

        .accent{
            color:#d65f13;
        }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div class="logo-wrap">
                        <img src="file://{{ public_path('img/front/booking/logo-pdf.png') }}" alt="ONX Logo">
                    </div>
                </td>

                <td class="brand-cell">
                    <p class="brand">ONX EDGE</p>
                    <p class="brand-sub">Production visuelle • Événementiel • Publicité</p>
                </td>

                <td class="doc-cell">
                    <p class="doc-title">Bon de réservation</p>
                    <div class="doc-ref">Référence #{{ $booking->id }}</div>
                </td>
            </tr>
        </table>

        <div class="notice">
            Ce document confirme l’enregistrement de la réservation. La validation finale est effectuée après versement de l’acompte et confirmation par ONX.
        </div>
    </div>

    <div class="section">
        <p class="section-title">Informations</p>

        <table class="two-col">
            <tr>
                <td>
                    <div class="box">
                        <p class="box-title">Informations du client</p>

                        <div class="item">
                            <span class="label">Nom complet</span>
                            <span class="value">{{ $booking->name }}</span>
                        </div>

                        <div class="item">
                            <span class="label">Téléphone</span>
                            <span class="value">{{ $booking->phone }}</span>
                        </div>

                        <div class="item">
                            <span class="label">E-mail</span>
                            <span class="value">{{ $booking->email ?: 'Non renseigné' }}</span>
                        </div>

                        @if(!empty($booking->business_name))
                        <div class="item">
                            <span class="label">Activité</span>
                            <span class="value">{{ $booking->business_name }}</span>
                        </div>
                        @endif
                    </div>
                </td>

                <td>
                    <div class="box">
                        <p class="box-title">Informations de réservation</p>

                        <div class="item">
                            <span class="label">Service</span>
                            <span class="value">{{ $booking->service?->name ?? '—' }}</span>
                        </div>

                        <div class="item">
                            <span class="label">Pack</span>
                            <span class="value">{{ $packageName ?: 'À confirmer' }}</span>
                        </div>

                        @if(!empty($booking->ads_type))
                        <div class="item">
                            <span class="label">Type de publicité</span>
                            <span class="value">
                                {{ $booking->ads_type === 'monthly' ? 'Mensuel' : ($booking->ads_type === 'custom' ? 'Sur mesure' : 'À confirmer') }}
                            </span>
                        </div>
                        @endif

                        @if($booking->event_date)
                        <div class="item">
                            <span class="label">Date</span>
                            <span class="value">{{ $booking->event_date ?: 'À confirmer' }}</span>
                        </div>

                        <div class="item">
                            <span class="label">Lieu</span>
                            <span class="value">{{ $locationName ?: 'À confirmer' }}</span>
                        </div>
                        @endif

                        @if($booking->budget)
                        <div class="item">
                            <span class="label">Budget estimatif</span>
                            <span class="value">{{ number_format((float) $booking->budget) . ' DA' }}</span>
                        </div>
                        @endif

                        @if($booking->deadline)
                        <div class="item">
                            <span class="label">Date souhaitée</span>
                            <span class="value">{{ $booking->deadline }}</span>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Montant</p>

        <table class="money-table">
            <tr>
                <td>
                    <div class="money-box total">
                        <span class="money-label">Prix total</span>
                        <div class="money-value">
                            @if(!empty($packagePrice))
                                {{ number_format((float) $packagePrice) }} DA
                            @else
                                <span class="placeholder">À compléter</span>
                            @endif
                        </div>
                        <span class="money-sub">Montant global</span>
                    </div>
                </td>

                <td>
                    <div class="money-box">
                        <span class="money-label">Acompte</span>
                        <div class="money-value">
                            <span class="placeholder">................</span>
                        </div>
                        <span class="money-sub">Montant versé</span>
                    </div>
                </td>

                <td>
                    <div class="money-box">
                        <span class="money-label">Reste à payer</span>
                        <div class="money-value">
                            <span class="placeholder">................</span>
                        </div>
                        <span class="money-sub">Solde restant</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if(!empty($booking->notes))
    <div class="section">
        <p class="section-title">Notes</p>
        <div class="notes-box">
            {{ $booking->notes }}
        </div>
    </div>
    @endif

    <div class="section">
    <p class="section-title">Conditions</p>
    <div class="terms">
        <ul>
            @if($booking->booking_type === 'event')
                <li>La réservation doit être confirmée dans un délai de 7 jours.</li>
                <li>L’acompte versé n’est pas remboursable.</li>
                <li>Le client doit respecter les horaires convenus.</li>
                <li>Tout dépassement horaire après 04h00 entraîne des frais par tranche de 30 minutes.</li>
            @else
    <li>La commande publicitaire est confirmée après validation du devis, du brief client et versement de l’acompte convenu.</li>
    <li>L’acompte versé n’est pas remboursable dès le lancement de la prestation.</li>
    <li>Le client s’engage à fournir tous les éléments nécessaires à la réalisation du projet dans les délais convenus.</li>
    <li>Toute modification demandée après validation peut entraîner des frais et délais supplémentaires.</li>
    <li>La livraison finale est effectuée après règlement complet, sauf accord écrit contraire.</li>
    <li>Les créations réalisées restent la propriété d’ONX jusqu’au paiement intégral de la prestation.</li>
@endif
        </ul>
    </div>
</div>

    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-box">
                    <div class="sign-title">Signature du client</div>
                    <div class="sign-line">Nom / Signature / Date</div>
                </div>
            </td>

            <td>
                <div class="sign-box">
                    <div class="sign-title">Validation ONX</div>
                    <div class="sign-line">Cachet / Signature / Date</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="client-login-box">
        <div class="title">منطقة العملاء — بيانات الدخول</div>
        <div class="row"><strong>اسم المستخدم (البريد أو الهاتف):</strong> {{ $clientLogin ?? '—' }}</div>
        <div class="row"><strong>كلمة المرور:</strong> {{ $clientPassword ?? '— (تواصل معنا لإعادة التعيين)' }}</div>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <strong>ONX EDGE</strong><br>
                    <span class="accent">Bon de réservation</span>
                </td>
                <td class="text-right">
                    Instagram: @onx.edge<br>
                    YouTube: @onxedge<br>
                    WhatsApp: +213 540 57 35 18
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>