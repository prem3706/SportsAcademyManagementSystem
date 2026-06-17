<div class="container py-4">

    <form method="POST" id="editSportsLevelsForm">

        @csrf
        @method('PUT')

        <input type="hidden" id="url" value="{{ route('sport-levels.update', $sport->id) }}">

        <!-- Sport Dropdown -->
        <div class="mb-4">

            <label class="form-label fw-semibold">
                Sport Name <span class="text-danger">*</span>
            </label>

            <input type="text" class="form-control bg-light" value="{{ $sport->name }}" readonly>

            <input type="hidden" name="sport_id" value="{{ $sport->id }}">

        </div>
        <!-- Add New Level -->
        <div class="row g-2 align-items-end">

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    Select Level <span class="text-danger">*</span>
                </label>

                <select id="levelDropdown" class="form-select">

                    <option value="">
                        Choose Level
                    </option>

                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}">
                            {{ $level->name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    Fees <span class="text-danger">*</span>
                </label>

                <input type="number" id="levelFees" class="form-control" placeholder="Enter fees">

            </div>

            <div class="col-md-2">

                <button type="button" class="btn btn-dark w-100" id="addNewLevelBtn">

                    Add

                </button>

            </div>

        </div>

        <!-- Levels Table -->
        <div class="mt-4">

            <table class="table table-bordered align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Level
                        </th>

                        <th>
                            Fees
                        </th>

                        <th width="100">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="levelTableBody">

                    @foreach ($sport->levels as $index => $level)
                        <tr id="row_{{ $index }}">

                            <!-- Level -->
                            <td>

                                {{ $level->name }}

                                <input type="hidden" class="level-id-input"
                                    name="levels[{{ $index }}][level_id]" value="{{ $level->id }}">

                            </td>

                            <!-- Fees -->
                            <td>

                                <input type="number" class="form-control" name="levels[{{ $index }}][fees]"
                                    value="{{ $level->pivot->fees }}">

                            </td>

                            <!-- Remove -->
                            <td class="text-center">

                                <button type="button" class="btn btn-danger btn-sm removeLevelBtn"
                                    data-row="{{ $index }}">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        <!-- Update Button -->
        <div class="text mt-4">

            <button type="submit" class="btn btn-success px-4">

                Update Sport Levels

            </button>

        </div>

    </form>

</div>
