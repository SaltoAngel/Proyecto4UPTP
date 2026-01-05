@props(['title', 'value', 'icon', 'color' => 'primary', 'change' => null, 'footer' => null])

<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-{{ $color }} shadow-{{ $color }} text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">{{ $icon }}</i>
            </div>
            <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">{{ $title }}</p>
                <h4 class="mb-0">{{ $value }}</h4>
                @if($change)
                    <p class="text-xs mb-0">
                        <span class="{{ $change['isPositive'] ? 'text-success' : 'text-danger' }} font-weight-bolder">
                            {{ $change['isPositive'] ? '+' : '' }}{{ $change['value'] }}%
                            <i class="material-icons text-sm">{{ $change['isPositive'] ? 'arrow_upward' : 'arrow_downward' }}</i>
                        </span>
                    </p>
                @endif
            </div>
        </div>
        <hr class="dark horizontal my-0">
        @if($footer)
            <div class="card-footer p-3">
                <p class="mb-0 text-xs">{{ $footer }}</p>
            </div>
        @endif
    </div>
</div>