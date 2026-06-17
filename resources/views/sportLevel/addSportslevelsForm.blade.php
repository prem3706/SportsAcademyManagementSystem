<div class="container py-4">

    <form method="POST" id="addSportsLevelsForm">

        @csrf

        <input type="hidden" name="url" id="url" value="{{ route('sport-levels.store') }}">

        <!-- Sport Dropdown -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Select Sport <span class="text-danger">*</span>
            </label>

            <select name="sport_id" class="form-select">

                <option value="">
                    Choose Sport
                </option>

                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}">
                        {{ $sport->name }}
                    </option>
                @endforeach

            </select>

            <!-- Error -->
            <span class="text-danger small" id="sport_idError"></span>

        </div>

        <!-- Level Dropdown -->
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

                <!-- Error -->
                <span class="text-danger small" id="levelDropdownError"></span>

            </div>

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    Fees <span class="text-danger">*</span>
                </label>

                <input type="number" id="levelFees" class="form-control" placeholder="Enter fees">

                <!-- Error -->
                <span class="text-danger small" id="levelFeesError"></span>

            </div>

            <div class="col-md-2">

                <button type="button" class="btn btn-dark w-100" id="addNewLevelBtn">

                    Add

                </button>

            </div>

        </div>

        <!-- Added Levels -->
        <div class="mt-4">

            <table class="table table-bordered align-middle">

                <thead>

                    <tr>

                        <th>
                            Level
                        </th>

                        <th>
                            Fees
                        </th>

                        <th width="80">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="levelTableBody">

                </tbody>

            </table>

            <!-- Levels Array Error -->
            <span class="text-danger small" id="levelsError"></span>

        </div>

        <button type="submit" class="btn btn-success">

            Save Sport Levels

        </button>

    </form>

</div>
