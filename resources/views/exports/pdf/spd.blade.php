@if(($template ?? 'template_1') === 'template_2')
    @include('exports.pdf.spd_template_2')
@else
    @include('exports.pdf.spd_template_1')
@endif
