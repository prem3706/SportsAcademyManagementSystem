<div class="container py-4 ">

    <form id="addFeesGenerateForm" method="POST" with="80%">

        @csrf

        <input type="hidden" id="url" value="{{ route('fees-generates.store') }}">

        <div class="row g-3">

            <!-- Month -->
            <div class="col-md-12">

                <label class="form-label fw-semibold">

                    Select Month

                </label>

                <select name="month" class="form-select">

                    <option value="">
                        Choose Month
                    </option>

                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">

                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}

                        </option>
                    @endfor

                </select>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="monthError"></p>
                </div>

            </div>

            <!-- Year -->
            <div class="col-md-12">

                <label class="form-label fw-semibold">

                    Select Year

                </label>

                <input type="number" name="year" class="form-control" value="{{ date('Y') }}">

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="yearError"></p>
                </div>

            </div>

        </div>

        <div class="mt-4">

            <button type="submit" class="btn btn-dark">

                Generate Fees

            </button>

        </div>

    </form>

</div>
