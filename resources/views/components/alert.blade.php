@props(['type' => 'success', 'message'])

@php
    $bgColor = '';
    $borderColor = '';
    $textColor = '';

    switch ($type) {
        case 'success':
            $bgColor = 'bg-green-100';
            $borderColor = 'border-green-400';
            $textColor = 'text-green-700';
            break;
        case 'error':
            $bgColor = 'bg-red-100';
            $borderColor = 'border-red-400';
            $textColor = 'text-red-700';
            break;
        case 'warning':
            $bgColor = 'bg-yellow-100';
            $borderColor = 'border-yellow-400';
            $textColor = 'text-yellow-700';
            break;
        case 'info':
            $bgColor = 'bg-blue-100';
            $borderColor = 'border-blue-400';
            $textColor = 'text-blue-700';
            break;
        default:
            $bgColor = 'bg-gray-100';
            $borderColor = 'border-gray-400';
            $textColor = 'text-gray-700';
            break;
    }
@endphp

<div class="{{ $bgColor }} border {{ $borderColor }} {{ $textColor }} px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ $message ?? $slot }}</span>
</div>