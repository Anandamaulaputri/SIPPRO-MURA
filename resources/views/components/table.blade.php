@props([
    'headers' => [],
])

<div class="overflow-x-auto rounded-2xl border border-[#2A2A2A] bg-[#151515] shadow-md">
    <table class="w-full text-left text-xs">
        @if(count($headers) > 0 || isset($thead))
            <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                @if(isset($thead))
                    {{ $thead }}
                @else
                    <tr>
                        @foreach($headers as $header)
                            <th class="py-3.5 px-4">{{ $header }}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
        @endif
        <tbody class="divide-y divide-[#2A2A2A] text-white">
            {{ $slot }}
        </tbody>
        @if(isset($tfoot))
            <tfoot class="bg-[#111111] border-t border-[#2A2A2A] font-bold">
                {{ $tfoot }}
            </tfoot>
        @endif
    </table>
</div>
