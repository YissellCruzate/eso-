{{-- Credit Note --}}
@isset($creditNoteLines)
    @php
        $invoiceLines = $creditNoteLines;
    @endphp
@endisset

{{-- Debit Note --}}
@isset($debitNoteLines)
    @php
        $invoiceLines = $debitNoteLines;
    @endphp
@endisset

@isset($requestedMonetaryTotals)
    @php
        $legalMonetaryTotals = $requestedMonetaryTotals;
    @endphp
@endisset

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>{{$resolution->next_consecutive}}</title>
    </head>
    {{-- Styles --}}
    <style>
        body {
            font-family: Arial;
            font-size: 11px;
            padding: 0px;
            margin: 0px;
        }
        
        .bold {
            font-weight: bold;
        }
        
        table, th, td {
            /* border: 1px solid black; */
        }
        
        .table-full {
            border-collapse: collapse;
            padding: 5px;
            margin: 5px;
            width: 100%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-right {
            text-align: right;
        }
        
        .company {
            font-size: 10px;
        }
        
        .company-title {
            font-size: 15px;
        }
        
        .title td {
            padding: 5px;
            margin: 5px;
        }
        
        .detail td {
            font-size: 9.5px;
        }
        
        .small td {
            font-size: 8px;
        }
        
        .detail-lines td {
            border: 0.5px solid black;
        }
        
        .detail-total-line td {
            border-bottom: 0.5px solid black;
        }
        
        .total {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
    <body>
        {{-- Title --}}
        <table class="table-full text-center bold">
            
        </table>
        <hr>
        {{-- Logo and QR --}}
        <table class="table-full title">
            <tr>
                @if ($logoExists)
                    <td class="text-left" width="20%">
                        <img src="{{$logoPath}}" width="150">
                    </td>
                @endif
                <td class="text-left" width="{{($logoExists) ? '60%' : '80%'}}">
                    <div class="company">
                        <strong class="company-title">{{mb_strtoupper($user->name)}}</strong>
                        <p><small><strong>{{$company->type_document_identification->name}}: </strong>{{$company->identification_number}}@if ($company->dv != null)-{{$company->dv}}@endif</small></p>
                        <p><small><strong>Tipo de Contribuyente: </strong>{{$company->type_organization->name}}</small></p>
                        <p><small><strong>Dirección: </strong>{{$company->address}}</small></p>
                        <p><small><strong>Régimen Contable: </strong>{{$company->type_regime->name}}</small></p>
                        <p><small><strong>Municipio: </strong>{{$company->municipality->name}} ({{$company->municipality->department->name}})</small></p>
                        <p><small><strong>Actividad Económica Principal: </strong></small></p>
                        <p><small><strong>Correo: </strong>{{$user->email}}</small></p>
                       <!-- <p><small><strong>Tipo Responsabilidad: </strong>{{$company->type_liability->code}} ({{$company->type_liability->name}})</small></p>-->
                        <p><small><strong>Teléfono: </strong>{{$company->phone}}</small></p>
                    </div>
                </td>
                <td class="text-right" width="20%">
                    <img src="data:image/png;base64, {{base64_encode(QrCode::encoding('UTF-8')->format('png')->margin(0)->backgroundColor(218, 218, 218)->size(146)->generate($signXML->qr_graphic))}}">
                </td>
            </tr>
        </table>
        <table class="table-full title">
            <tr>
                <td>{{mb_strtoupper($typeDocument->name)}} {{$resolution->next_consecutive}}</td>
            </tr>
        </table>
        <table class="table-full detail">
            <tr>
                <td class="bold" width="15%">Fecha de Emisión:</td>
                <td width="35%">{{$date ?? Carbon\Carbon::now()->format('Y-m-d')}}</td>
                <td class="bold" width="15%">Fecha de Vencimiento:</td>
                <td width="35%">{{$paymentForm->payment_due_date}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Tipo de Operación:</td>
                <td width="35%">{{$company->type_operation->name}}</td>
                <td class="bold" width="15%">Prefijo:</td>
                <td width="35%">{{$resolution->prefix}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Tipo de Negociación:</td>
                <td width="35%">{{$paymentForm->name}}</td>
                <td class="bold" width="15%">Medio de Pago:</td>
                <td width="35%">{{$paymentForm->payment_method->name}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Tipo de Entrega:</td>
                <td width="35%">&nbsp;</td>
                <td class="bold" width="15%">Periodo:</td>
                <td width="35%">{{$invoicePeriod->start_date}} {{$invoicePeriod->start_time}} - {{$invoicePeriod->end_date}} {{$invoicePeriod->end_time}}</td>
            </tr>
        </table>
        {{-- Acquirer's Information --}}
        <table class="table-full">
            <tr class="title">
                <td class="bold" width="15%">Adquiriente:</td>
                <td width="35%">{{$customer->company->type_document_identification->name}}: {{$customer->company->identification_number}}@if ($customer->company->dv != null)-{{$customer->company->dv}}@endif</td>
                <td class="bold" width="15%">Razón Social:</td>
                <td width="35%">{{$customer->name}}</td>
            </tr>
        </table>
        <table class="table-full detail">
            <tr>
                <td class="bold" width="15%">Dirección:</td>
                <td width="35%">{{{$customer->company->address}}}</td>
                <td class="bold" width="15%">Número Documento:</td>
                <td width="35%">{{$customer->company->identification_number}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Departamento:</td>
                <td width="35%">{{$customer->company->municipality->department->name}}</td>
                <td class="bold" width="15%">Municipio:</td>
                <td width="35%">{{$customer->company->municipality->name}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Tipo de Contribuyente:</td>
                <td width="35%">{{$customer->company->type_organization->name}}</td>
                <td class="bold" width="15%">Correo:</td>
                <td width="35%">{{$customer->email}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Régimen Contable:</td>
                <td width="35%">{{$customer->company->type_regime->name}}</td>
                <td class="bold" width="15%">Teléfono:</td>
                <td width="35%">{{$customer->company->phone}}</td>
            </tr>
            <tr>
                <td class="bold" width="15%">Tipo de Responsabilidad:</td>
                <td width="35%">{{$customer->company->type_liability->code}} ({{$customer->company->type_liability->name}})</td>
                <td class="bold" width="15%">&nbsp;</td>
                <td width="35%">&nbsp;</td>
            </tr>
        </table>
        <hr>
        <table class="table-full title bold">
            <tr>
                <td>Detalles de Productos</td>
            </tr>
        </table>
        <table class="table-full detail-lines">
            <tr>
                <td colspan="6" width="60%">&nbsp;</td>
                <td class="text-center bold" colspan="2" >Cargos o Descuentos</td>
                <td class="text-center bold" colspan="3" >Impuestos</td>
                <td class="text-center bold" rowspan="2">Total item</td>
            </tr>
            <tr>
                <td class="bold">Nro</td>
                <td class="bold">Código</td>
                <td class="bold">Descripción</td>
                <td class="bold">U/M</td>
                <td class="bold">Cantidad</td>
                <td class="bold">Precio Unitario</td>
                <td class="bold">Descuento</td>
                <td class="bold">Recargo</td>
                <td class="bold">IVA</td>
                <td class="bold">ICA</td>
                <td class="bold">INC</td>
            </tr>
            @foreach ($invoiceLines as $key => $invoiceLine)
                <tr>
                    <td class="text-center">{{($key + 1)}}</td>
                    <td>{{$invoiceLine->code}}</td>
                    <td>{{$invoiceLine->description}}</td>
                    <td>{{$invoiceLine->unit_measure->code}}</td>
                    <td class="text-right">{{number_format($invoiceLine->invoiced_quantity, 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(($invoiceLine->free_of_charge_indicator === 'true') ? 0 : $invoiceLine->price_amount, 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(((count($invoiceLine->allowance_charges) > 0) ? $invoiceLine->allowance_charges->filter(function($allowanceCharge, $key) {return $allowanceCharge->charge_indicator === 'false';})->sum('amount') : 0), 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(((count($invoiceLine->allowance_charges) > 0) ? $invoiceLine->allowance_charges->filter(function($allowanceCharge, $key) {return $allowanceCharge->charge_indicator === 'true';})->sum('amount') : 0), 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(((count($invoiceLine->tax_totals) > 0) ? $invoiceLine->tax_totals->filter(function($tax, $key) {return $tax->tax_id == 1;})->sum('tax_amount') : 0), 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(((count($invoiceLine->tax_totals) > 0) ? $invoiceLine->tax_totals->filter(function($tax, $key) {return $tax->tax_id == 3;})->sum('tax_amount') : 0), 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format(((count($invoiceLine->tax_totals) > 0) ? $invoiceLine->tax_totals->filter(function($tax, $key) {return $tax->tax_id == 4;})->sum('tax_amount') : 0), 2, ',', '.')}}</td>
                    <td class="text-right">${{number_format($invoiceLine->line_extension_amount, 2, ',', '.')}}</td>
                </tr>
            @endforeach
        </table>
        @if ($allowanceCharges->count() > 0)
            <hr>
            <table class="table-full title bold">
                <tr>
                    <td>Descuentos y Recargos Globales</td>
                </tr>
            </table>
            <table class="table-full detail-lines">
                <tr>
                    <td class="bold">Nro</td>
                    <td class="bold">Tipo</td>
                    <td class="bold">Código</td>
                    <td class="bold">Descripción</td>
                    <td class="text-center bold">%</td>
                    <td class="text-center bold">Monto</td>
                </tr>
                @foreach ($allowanceCharges as $key => $allowanceCharge)
                    <tr>
                        <td class="text-center">{{($key + 1)}}</td>
                        <td>@if ($allowanceCharge->charge_indicator === 'false') Descuento @else Cargo @endif</td>
                        <td>@if ($allowanceCharge->charge_indicator === 'false') {{$allowanceCharge->discount->code}} ({{$allowanceCharge->discount->name}}) @endif</td>
                        <td>{{$allowanceCharge->allowance_charge_reason}}</td>
                        <td class="text-center">{{$allowanceCharge->multiplier_factor_numeric}}</td>
                        <td class="text-right">${{number_format($allowanceCharge->amount, 2, ',', '.')}}</td>
                    </tr>
                @endforeach
            </table>
        @endif
        <hr>
        <table class="table-full title bold">
            <tr>
                <td>Totales</td>
            </tr>
        </table>
        <table class="table-full">
            <tr>
                <td width="60%">&nbsp;</td>
                <td width="40%">
                    <table class="table-full">
                        <tr>
                            <td class="text-left">Moneda</td>
                            <td class="text-right">{{$company->type_currency->code}}</td>
                        </tr>
                        <tr class="detail-total-line">
                            <td class="text-left">Tasa de Cambio</td>
                            <td class="text-right">@isset($paymentExchangeRate){{number_format($paymentExchangeRate->calculation_rate, 2, ',', '.')}}@endisset</td>
                        </tr>
                        <tr>
                            <td class="text-left">Subtotal Precio Unitario (=)</td>
                            <td class="text-right">${{number_format($invoiceLines->filter(function($line, $key) {return $line->free_of_charge_indicator === 'false';})->sum(function($line) {return ($line->price_amount * $line->base_quantity);}), 2, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Descuentos Detalle (-)</td>
                            <td class="text-right">${{number_format($invoiceLines->sum(function($line) {return ((count($line->allowance_charges) > 0) ? $line->allowance_charges->filter(function($allowanceCharge, $key) {return $allowanceCharge->charge_indicator === 'false';})->sum('amount') : 0);}), 2, ',', '.')}}</td>
                        </tr>
                        <tr class="detail-total-line">
                            <td class="text-left">Recargos Detalle (+)</td>
                            <td class="text-right">${{number_format($invoiceLines->sum(function($line) {return ((count($line->allowance_charges) > 0) ? $line->allowance_charges->filter(function($allowanceCharge, $key) {return $allowanceCharge->charge_indicator === 'true';})->sum('amount') : 0);}), 2, ',', '.')}}</td>
                        </tr>
                        <tr class="detail-total-line">
                            <td class="text-left">Subtotal Base Gravable (=)</td>
                            <td class="text-right">${{number_format($legalMonetaryTotals->tax_exclusive_amount, 2, ',', '.')}}</td>
                        </tr>
                        @foreach ($taxTotals->groupBy('tax_id') as $id => $taxGroup)
                            <tr>
                                <td class="text-left">Total Impuesto {{$taxGroup->first()->tax->name}} (=)</td>
                                <td class="text-right">${{number_format($taxGroup->sum('tax_amount'), 2, ',', '.')}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left">Total Impuestos (+)</td>
                            <td class="text-right">${{number_format($taxTotals->sum(function($tax) {return $tax->tax_amount ?? 0;}), 2, ',', '.')}}</td>
                        </tr>
                        <tr class="detail-total-line">
                            <td class="text-left">Total Mas Impuesto (=)</td>
                            <td class="text-right">${{number_format($legalMonetaryTotals->tax_inclusive_amount, 2, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Descuento Global (-)</td>
                            <td class="text-right">${{number_format($allowanceCharges->filter(function($charge, $key) {return $charge->charge_indicator === 'false';})->sum('amount'), 2, ',', '.')}}</td>
                        </tr>
                        <tr class="detail-total-line">
                            <td class="text-left">Recargo Global (+)</td>
                            <td class="text-right">${{number_format($allowanceCharges->filter(function($charge, $key) {return $charge->charge_indicator === 'true';})->sum('amount'), 2, ',', '.')}}</td>
                        </tr>
                        @if ($prepaidPayments->count() > 0)
                            <tr class="detail-total-line">
                                <td class="text-left">Total Anticipo (-)</td>
                                <td class="text-right">${{number_format($prepaidPayments->sum('paid_amount'), 2, ',', '.')}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-left">Total a Pagar (=)</td>
                            <td class="text-right total">${{number_format($legalMonetaryTotals->payable_amount, 2, ',', '.')}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
