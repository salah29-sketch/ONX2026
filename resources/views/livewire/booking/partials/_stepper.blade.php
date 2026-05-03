{{--
    Stepper Partial
    @param array $steps — e.g. [['label' => 'الباقة'], ['label' => 'الموعد'], ...]
    @param int $currentStep — 1-based current step index
--}}
@php $totalSteps = count($steps); @endphp
<div class="mb-10 w-full animate-fade-in-up animate-delay-100 px-2 sm:px-4">
    <div class="flex items-center justify-between relative">
        <!-- Connecting Line -->
        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-white/[0.08] -translate-y-1/2 z-0 rounded-full mx-4 sm:mx-6"></div>
        <div class="absolute top-1/2 right-0 h-0.5 bg-orange-500 -translate-y-1/2 z-0 rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]" 
             style="width: {{ $totalSteps > 1 ? (($currentStep - 1) / ($totalSteps - 1)) * 100 : 0 }}%;"></div>

        @foreach($steps as $i => $step)
            @php
                $num = $i + 1;
                $isDone = $num < $currentStep;
                $isActive = $num === $currentStep;
            @endphp
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-xs md:text-sm font-bold transition-all duration-300 {{ 
                    $isDone ? 'bg-orange-500 text-white shadow-[0_0_12px_rgba(234,88,12,0.5)] border-none' : 
                    ($isActive ? 'bg-white/[0.04] border-2 border-orange-500 text-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.6)] box-content scale-110' : 
                    'bg-white/[0.04] border-2 border-white/15 text-white/40')
                }}">
                    @if($isDone)
                        ✓
                    @else
                        {{ $num }}
                    @endif
                </div>
                <div class="absolute top-10 md:top-12 whitespace-nowrap text-[10px] md:text-xs font-semibold mt-1 transition-colors {{ $isActive ? 'text-orange-400 drop-shadow-md' : ($isDone ? 'text-white/80' : 'text-white/40') }}">
                    {{ $step['label'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>
