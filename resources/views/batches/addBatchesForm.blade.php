<div class="container py-4">

    <form method="POST" id="addBatchForm">

        @csrf

        <input type="hidden" id="url" value="{{ route('batches.store') }}">

        <div class="row g-3">

            <!-- Batch Name -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Batch Name
                </label>

                <input type="text" name="name" class="form-control" placeholder="Enter batch name">

                <span class="text-danger small" id="nameError"></span>

            </div>

            <!-- Capacity -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Capacity
                </label>

                <input type="number" name="capacity" class="form-control" placeholder="Enter capacity">

                <span class="text-danger small" id="capacityError"></span>

            </div>

            <!-- Start Time -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Start Time
                </label>

                <input type="text" name="start_time" id="startTime" class="form-control"
                    placeholder="Select start time">

                <span class="text-danger small" id="start_timeError"></span>

            </div>

            <!-- End Time -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    End Time
                </label>

                <input type="text" name="end_time" id="endTime" class="form-control" placeholder="Select end time">

                <span class="text-danger small" id="end_timeError"></span>

            </div>

            <!-- Sport -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Sport
                </label>

                <select name="sport_id" id="sportDropdown" class="form-select ">

                    <option value="">
                        Choose Sport
                    </option>

                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}">

                            {{ $sport->name }}

                        </option>
                    @endforeach

                </select>

                <span class="text-danger small" id="sport_idError"></span>

            </div>

            <!-- Level -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Level
                </label>

                <select name="level_id" id="levelDropdown" class="form-select">

                    <option value="">
                        Choose Level
                    </option>

                </select>

                <span class="text-danger small" id="level_idError"></span>

            </div>

            <!-- Coaches -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Coaches
                </label>

                <select name="coaches[]" class="form-select select2" multiple>

                    @foreach ($coaches as $coach)
                        <option value="{{ $coach->id }}">

                            {{ $coach->firstname }} {{ $coach->lastname }}

                        </option>
                    @endforeach

                </select>

                <span class="text-danger small" id="coachesError"></span>

            </div>

            <!-- Players -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Players
                </label>

                <select name="players[]" class="form-select  select2" multiple>

                    @foreach ($players as $player)
                        <option value="{{ $player->id }}">

                            {{ $player->firstname }} {{ $player->lastname }}

                        </option>
                    @endforeach

                </select>

                <span class="text-danger small" id="playersError"></span>

            </div>

            <!-- Status -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option value="active">

                        Active

                    </option>

                    <option value="inactive">

                        Inactive

                    </option>

                </select>

                <span class="text-danger small" id="statusError"></span>

            </div>

        </div>

        <!-- Submit -->
        <div class="mt-4">

            <button type="submit" class="btn btn-dark px-4">

                Save Batch

            </button>

        </div>

    </form>

</div>
