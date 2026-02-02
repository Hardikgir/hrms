<div>
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0">Leave Balance ({{ $year }})</h5>
        <div class="btn-group btn-group-sm">
            <button type="button" wire:click="$set('year', {{ $year - 1 }})" class="btn btn-outline-secondary">← {{ $year - 1 }}</button>
            <button type="button" class="btn btn-secondary disabled" disabled>{{ $year }}</button>
            <button type="button" wire:click="$set('year', {{ $year + 1 }})" class="btn btn-outline-secondary">{{ $year + 1 }} →</button>
        </div>
    </div>

    @if(count($this->dashboard) > 0)
        <div class="row">
            @foreach($this->dashboard as $item)
                @php
                    $type = $item['leave_type'];
                    $bal = $item['balance'];
                    $pct = $bal['allocated'] > 0 ? min(100, round(($bal['used'] / $bal['allocated']) * 100)) : 0;
                    $isLow = $bal['available'] > 0 && $bal['available'] <= 3;
                    $isZero = $bal['available'] <= 0;
                @endphp
                <div class="col-md-4 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm {{ $isZero ? 'border-danger' : ($isLow ? 'border-warning' : '') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 font-weight-bold text-dark">{{ $type->name }}</h6>
                                @if($bal['carry_forward'] > 0)
                                    <span class="badge badge-info badge-pill">+{{ $bal['carry_forward'] }} carried</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                <span class="h2 font-weight-bold mb-0 {{ $isZero ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success') }}">
                                    {{ $bal['available'] }}
                                </span>
                                <span class="text-muted align-middle ml-1">days left</span>
                            </div>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar {{ $isZero ? 'bg-danger' : ($isLow ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ 100 - $pct }}%;" aria-valuenow="{{ $bal['available'] }}" aria-valuemin="0" aria-valuemax="{{ $bal['allocated'] }}"></div>
                            </div>
                            <p class="mb-0 small text-muted">
                                Used {{ $bal['used'] }} of {{ $bal['allocated'] }} days
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted mb-0">No leave types configured or you are not linked as an employee.</p>
    @endif
</div>
