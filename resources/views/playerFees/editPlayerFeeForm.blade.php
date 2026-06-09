<div class="container py-3">

    <form id="editPlayerFeeForm" method="POST">

        @csrf
        @method('PUT')

        <input type="hidden" id="url" value="{{ route('player-fees.update', $playerFee->id) }}">

        <!-- Player -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Player
            </label>

            <input type="text" class="form-control"
                value="{{ $playerFee->user->firstname }} {{ $playerFee->user->lastname }}" readonly>

        </div>

        <!-- Sport -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Sport
            </label>

            <input type="text" class="form-control" value="{{ $playerFee->sport->name }}" readonly>

        </div>

        <!-- Amount -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Amount
            </label>

            <input type="text" class="form-control" value="₹ {{ $playerFee->amount }}" readonly>

        </div>

        <!-- Status -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Status
            </label>

            <select name="status" class="form-select">

                <option value="unpaid" {{ $playerFee->status == 'unpaid' ? 'selected' : '' }}>
                    Unpaid
                </option>

                <option value="paid" {{ $playerFee->status == 'paid' ? 'selected' : '' }}>
                    Paid
                </option>

            </select>

        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-dark w-100">

            Update Fees

        </button>

    </form>

</div>
