<div class="d-flex justify-content-center">

    <form method="POST" id="editExpenseForm" enctype="multipart/form-data" style="max-width: 950px; width:100%;">

        @csrf
        @method('PUT')

        <input type="hidden" name="url" id="url" value="{{ route('expenses.update', $expense->id) }}">

        <div class="row g-3">

            <!-- Category -->
            <div class="col-12">

                <label for="expense_category_id" class="form-label fw-semibold small text-dark mb-2">
                    Expense Category <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="expense_category_id" name="expense_category_id">
                    <option value="" disabled>Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="expense_category_idError"></p>
                </div>

            </div>

            <!-- Expense Date -->
            <div class="col-6">

                <label for="expense_date" class="form-label fw-semibold small text-dark mb-2">
                    Expense Date <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-calendar-event text-secondary"></i>
                    </span>
                    <input type="date" class="form-control py-2" id="expense_date" name="expense_date" value="{{ $expense->expense_date ? $expense->expense_date->format('Y-m-d') : date('Y-m-d') }}">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="expense_dateError"></p>
                </div>

            </div>

            <!-- Amount -->
            <div class="col-6">

                <label for="amount" class="form-label fw-semibold small text-dark mb-2">
                    Amount <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        ₹
                    </span>
                    <input type="number" step="0.01" class="form-control py-2" id="amount" name="amount" value="{{ $expense->amount }}" placeholder="Enter amount">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="amountError"></p>
                </div>

            </div>

            <!-- Payment Mode -->
            <div class="col-6">

                <label for="payment_mode" class="form-label fw-semibold small text-dark mb-2">
                    Payment Mode <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="payment_mode" name="payment_mode">
                    <option value="cash" {{ $expense->payment_mode === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="upi" {{ $expense->payment_mode === 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="card" {{ $expense->payment_mode === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="net_banking" {{ $expense->payment_mode === 'net_banking' ? 'selected' : '' }}>Net Banking</option>
                    <option value="cheque" {{ $expense->payment_mode === 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="payment_modeError"></p>
                </div>

            </div>

            <!-- Reference No -->
            <div class="col-6">

                <label for="reference_no" class="form-label fw-semibold small text-dark mb-2">
                    Reference Number / Txn ID
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-hash text-secondary"></i>
                    </span>
                    <input type="text" class="form-control py-2" id="reference_no" name="reference_no" value="{{ $expense->reference_no }}" placeholder="Enter transaction/reference number">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="reference_noError"></p>
                </div>

            </div>

            <!-- Receipt -->
            <div class="col-12">

                <label for="receipt" class="form-label fw-semibold small text-dark mb-2">
                    Attach New Receipt / Bill (Leave blank to keep current)
                </label>

                @if ($expense->receipt)
                    <div class="mb-2">
                        <a href="{{ asset($expense->receipt) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-3 py-1">
                            <i class="bi bi-file-earmark-text me-1"></i> View Current Receipt
                        </a>
                    </div>
                @endif

                <input type="file" class="form-control py-2" id="receipt" name="receipt" accept="image/*,application/pdf" @if($expense->receipt) data-default-file="{{ asset($expense->receipt) }}" @endif>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="receiptError"></p>
                </div>

            </div>

            <!-- Description -->
            <div class="col-12">

                <label for="description" class="form-label fw-semibold small text-dark mb-2">
                    Description
                </label>

                <textarea class="form-control py-2" id="description" name="description" rows="3" placeholder="Enter description">{{ $expense->description }}</textarea>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="descriptionError"></p>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-3">

                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
                    <i class="bi bi-check-circle me-1"></i>
                    Update Expense
                </button>

            </div>

        </div>

    </form>

</div>
