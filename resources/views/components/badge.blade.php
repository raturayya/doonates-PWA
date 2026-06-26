@props(['status'])

@php
$styles = [
    'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
    'Verified' => 'bg-green-100 text-green-700 border-green-200',
    'Available' => 'bg-green-100 text-green-700 border-green-200',
    'Requested' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
    'Approved' => 'bg-blue-100 text-blue-700 border-blue-200',
    'Completed' => 'bg-gray-100 text-gray-700 border-gray-200',
    'Finished' => 'bg-green-100 text-green-700 border-green-200',
    'Rejected' => 'bg-red-100 text-red-700 border-red-200',
];

$dotColors = [
    'Pending' => 'bg-yellow-600',
    'Verified' => 'bg-green-600',
    'Available' => 'bg-green-600',
    'Requested' => 'bg-yellow-600',
    'Approved' => 'bg-blue-600',
    'Completed' => 'bg-gray-600',
    'Finished' => 'bg-green-600',
    'Rejected' => 'bg-red-600',
];
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-sm {{ $styles[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$status] ?? 'bg-gray-600' }}"></span>
    {{ $status }}
</span>
