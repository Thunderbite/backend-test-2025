@csrf

@include('backstage.partials.forms.text', [
    'field' => 'name',
    'label' => 'Name',
    'value' => old('name') ?? $campaign->name,
])

@include('backstage.partials.forms.select', [
    'field' => 'timezone',
    'label' => 'Timezone',
    'value' => old('timezone') ?? $campaign->timezone,
    'options' => $campaign->getAvailableTimezones(),
])

@include('backstage.partials.forms.starts-ends', [
    'starts_at' => old('starts_at') ?? ($campaign->starts_at === null ? $campaign->starts_at : $campaign->starts_at->format('d-m-Y H:i:s')),
    'ends_at' => old('ends_at') ?? ($campaign->ends_at === null ? $campaign->ends_at : $campaign->ends_at->format('d-m-Y H:i:s')),
])

@if (isset($withTemplates))
    <template-select
        url="https://static.thunderbite.com/frontend/frontend-thunderbite-wheel-v4/templates/"
        name="template"
        :old="{{old('template')}}"
    ></template-select>
@endif

@include('backstage.partials.forms.submit')
