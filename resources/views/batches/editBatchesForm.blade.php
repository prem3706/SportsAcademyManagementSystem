<div class="container py-4 h-auto">

    <form id="editBatchForm">

        @csrf
        @method('PUT')

        <input type="hidden" id="url" value="{{ route('batches.update', $batch->id) }}">

        <div class="row g-3">

            <!-- Batch Name -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Batch Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="name" class="form-control" value="{{ $batch->name }}"
                    placeholder="Enter batch name">

                <span class="text-danger small" id="nameError"></span>

            </div>

            <!-- Capacity -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Capacity <span class="text-danger">*</span>
                </label>

                <input type="number" name="capacity" class="form-control" value="{{ $batch->capacity }}"
                    placeholder="Enter capacity">

                <span class="text-danger small" id="capacityError"></span>

            </div>

            <!-- Start Time -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Start Time <span class="text-danger">*</span>
                </label>

                <input type="text" name="start_time" id="editStartTime" class="form-control"
                    value="{{ \Carbon\Carbon::parse($batch->start_time)->format('h:i A') }}"
                    placeholder="Select start time">

                <span class="text-danger small" id="start_timeError"></span>

            </div>

            <!-- End Time -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    End Time <span class="text-danger">*</span>
                </label>

                <input type="text" name="end_time" id="editEndTime" class="form-control"
                    value="{{ \Carbon\Carbon::parse($batch->end_time)->format('h:i A') }}"
                    placeholder="Select end time">

                <span class="text-danger small" id="end_timeError"></span>

            </div>

            <!-- Sport -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Sport <span class="text-danger">*</span>
                </label>

                <select name="sport_id" id="editSportDropdown" class="form-select">

                    <option value="">
                        Choose Sport
                    </option>

                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}" {{ $batch->sport_id == $sport->id ? 'selected' : '' }}>

                            {{ $sport->name }}

                        </option>
                    @endforeach

                </select>

                <span class="text-danger small" id="sport_idError"></span>

            </div>

            <!-- Level -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Select Level <span class="text-danger">*</span>
                </label>

                <select name="level_id" id="editLevelDropdown" class="form-select">

                    <option value="">
                        Choose Level
                    </option>

                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ $batch->level_id == $level->id ? 'selected' : '' }}>

                            {{ $level->name }}

                        </option>
                    @endforeach

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
                        <option value="{{ $coach->id }}"
                            {{ $batch->coaches->contains($coach->id) ? 'selected' : '' }}>

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

                <select name="players[]" class="form-select select2" multiple>

                    @foreach ($players as $player)
                        <option value="{{ $player->id }}"
                            {{ $batch->players->contains($player->id) ? 'selected' : '' }}>

                            {{ $player->firstname }} {{ $player->lastname }}

                        </option>
                    @endforeach

                </select>

                <span class="text-danger small" id="playersError"></span>

            </div>

            <!-- Status -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Status <span class="text-danger">*</span>
                </label>

                <select name="status" class="form-select">

                    <option value="active" {{ $batch->status == 'active' ? 'selected' : '' }}>

                        Active

                    </option>

                    <option value="inactive" {{ $batch->status == 'inactive' ? 'selected' : '' }}>

                        Inactive

                    </option>

                </select>

                <span class="text-danger small" id="statusError"></span>

            </div>

        </div>

        <!-- Submit -->
        <div class="mt-4">

            <button type="button" id="updateBatchBtn" class="btn btn-dark px-4">

                Update Batch
            </button>

        </div>

    </form>

</div>
