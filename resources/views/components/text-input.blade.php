@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm']) }}>
