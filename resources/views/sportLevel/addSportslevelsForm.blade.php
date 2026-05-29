<div class="container py-4">



    <form method="POST" id="addSportsLevelsForm">

        @csrf
        <input type="hidden" name="url" id="url" value="{{ route('sport-levels.store') }}">


        <!-- Sport Dropdown -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Select Sport
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

        </div>

        <!-- Level Dropdown -->
        <div class="row g-2 align-items-end">

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    Select Level
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
                    Fees
                </label>

                <input type="number" id="levelFees" class="form-control" placeholder="Enter fees">

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

        </div>

        <button type="submit" class="btn btn-success">

            Save Sport Levels

        </button>

    </form>


</div>
