{{--
    Progress Bar Partial
    @param int $currentStep
    @param int $totalSteps
--}}
<div class="w-full h-1 bg-white/[0.08] rounded-full mt-6 overflow-hidden">
    <div class="h-full bg-gradient-to-l from-orange-600 to-orange-400 rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]" style="width:{{ round(($currentStep / $totalSteps) * 100) }}%"></div>
</div>
