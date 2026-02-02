<div>
    <div class="mb-2 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Leave Balance ({{ $year }})</h5>
        <div>
            <button type="button" wire:click="$set('year', {{ $year - 1 }})" class="btn btn-sm btn-outline-secondary">← Prev</button>
            <span class="mx-2">{{ $year }}</span>
            <button type="button" wire:click="$set('year', {{ $year + 1 }})" class="btn btn-sm btn-outline-secondary">Next →</button>
        </div>
    </div>

    @if(count($this->dashboard) > 0)
        <div class="row">
            @foreach($this->dashboard as $item)
                @php
                    $type = $item['leave_type'];
                    $bal = $item['balance'];
                @endphp
                <div class="col-md-4 col-lg-3 mb-3">
                    <div class="card h-100 {{ $bal['available'] <= 0 ? 'border-warning' : '' }}">
                        <div class="card-body py-3">
                            <h6 class="card-title text-truncate" title="{{ $type->name }}">{{ $type->name }}</h6>
                            <p class="mb-0 small">
                                <strong>Available:</strong> {{ $bal['available'] }} days<br>
                                <span class="text-muted">Used: {{ $bal['used'] }} / Allocated: {{ $bal['allocated'] }}</span>
                                @if($bal['carry_forward'] > 0)
                                    <br><span class="text-info">Carry forward: {{ $bal['carry_forward'] }}</span>
                                @endif
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
